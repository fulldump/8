[[INCLUDE component=Ajax]]
[[INCLUDE component=GraphicPopup]]
[[INCLUDE component=SimpleList]]

window.addEventListener('load', function(event){
	var elements = document.getElementsByTagName('*');
	var cadena;
	var docs = [];
	for (key in elements) {
		cadena = elements[key].id;
		if (typeof(cadena)=='string') {
			var id = parseInt(cadena.replace('TreeDoc', ''));
			if (!isNaN(id))
				docs[id] = elements[key];
		}
	}
	for (key in docs) 
		newEditor(docs[key]).loadDocument(key);
	
}, true);

var newEditor = function(dom) {
	var last_selected = null; // ultima parte seleccionada
	var mousedown_anchornode = null;

	var logger = document.createElement('div'); dom.appendChild(logger);
	logger.className = 'editor-logger';


	var scroll = document.createElement('div'); dom.appendChild(scroll);
	scroll.className = 'editor-scroll';

	var mainbarframe = document.createElement('div'); scroll.appendChild(mainbarframe);
	mainbarframe.className = 'main-bar-frame';

	var mainbar = document.createElement('div'); mainbarframe.appendChild(mainbar);
	mainbar.className = 'part-toolbar main-bar';

	// Botones del mainbar:
	var b1 = document.createElement('button');
	b1.className = 'icon-title';
	b1.title = 'Titulo (Ctrl+1)';
	b1.setAttribute('style', '');
	b1.addEventListener('click', function(event){
		var part = createPartTitle();

		var ajax = new Ajax('[[AJAX name=insert_title]]');
		ajax.setCallback200(function(text) {
			if (text=='') {
				// TODO: MOVIDA, ha habido algún problema
			} else {
				part.id = text;
			}
		});
		var id_previous_part = 0;
		if (last_selected != null) id_previous_part = last_selected.id;
		ajax.query({'id_document':id_document,'id_part':id_previous_part});

		insertPartAfterFocus(part);
		part.select();
		part.content.text.focus();
	}, true);
	mainbar.appendChild(b1);

	// Botones del mainbar:
	var b1 = document.createElement('button');
	b1.className = 'icon-text';
	b1.title = 'Texto (Ctrl+2)';
	b1.setAttribute('style', '');
	b1.addEventListener('click', function(event){
		var part = createPartText();

		var ajax = new Ajax('[[AJAX name=insert_text]]');
		ajax.setCallback200(function(text) {
			if (text=='') {
				// TODO: MOVIDA, ha habido algún problema
			} else {
				part.id = text;
			}
		});
		var id_previous_part = 0;
		if (last_selected != null) id_previous_part = last_selected.id;
		ajax.query({'id_document':id_document,'id_part':id_previous_part});

		insertPartAfterFocus(part);
		part.select();
		part.content.text.focus();
	}, true);
	mainbar.appendChild(b1);

	var b2 = document.createElement('button');
	b2.className = 'icon-image';
	b2.title = 'Imagen (Ctrl+3)';
	b2.setAttribute('style', '');
	b2.addEventListener('click', function(event){
		var popup = newGraphicPopup();
		var panel = document.createElement('div');
		panel.className = 'panel-insert-image';
		panel.setAttribute('style', 'width:800px; height:400px;');
		popup.appendContent(panel);

		var panel_left = document.createElement('div');
		panel_left.className = 'panel-left';
		panel.appendChild(panel_left);
		
		var option_list = new SimpleList();
		option_list.setParentNode(panel_left);
		option_list.setCallbackClick(function(id) {
			log(id);
		});
		option_list.add(1, 'Buscar en mi disco duro');
		option_list.add(2, 'Pegar URL http://...');
		option_list.add(3, 'Mis imágenes');
		option_list.add(4, 'Imágenes de este documento');
		option_list.select(1);

		var option_fromhdd = document.createElement('div');
		panel.appendChild(option_fromhdd);
		option_fromhdd.className = 'panel-fromhdd';
		option_fromhdd.innerHTML = 'Selecciona una imagen jpeg/png desde tu disco duro:<br><br>';

		var g = document.createElement('div'); option_fromhdd.appendChild(g);
		g.innerHTML = '<iframe id="upload_image_from_hdd" style="display:none;" name="hola"></iframe>';
		var iframe = document.getElementById('upload_image_from_hdd');
		var form = document.createElement('form'); option_fromhdd.appendChild(form);
		form.target = 'hola';
		form.action = '[[AJAX name=upload_image_from_hdd]]';
		form.method = 'post';
		form.enctype = 'multipart/form-data';
		var input_id_document = document.createElement('input'); form.appendChild(input_id_document);
		input_id_document.name = 'id_document';
		input_id_document.value = id_document;

		
		var input_file = document.createElement('input'); form.appendChild(input_file);
		input_file.type = 'file';
		input_file.name = 'image';

		popup.setCallback (function(event) {
			log(input_file.files[0].type);
			if (input_file.files[0].type != 'image/png' && input_file.files[0].type != 'image/jpeg' ) {
				alert('Debe seleccionar una imagen válida');
			} else {
				var part = createPartImage();
				insertPartAfterFocus(part);
				part.select();
				part.content.text.focus();
				form.submit();
				popup.hide();
				iframe.addEventListener('load', function(event) {
					// Actualizo la imagen si lo que me devuelve es mayor de cero
					var text = this.contentDocument.body.innerHTML;
					if (text=='0') {
						// Borro la parte imagen
						doc.removeChild(part);
					} else {
						part.content.image.src='/img/'+text;
					}
				}, true);
			}
		});
		popup.show();
	}, true);
	mainbar.appendChild(b2);


	var textbar = document.createElement('div'); mainbarframe.appendChild(textbar);
	textbar.className = 'part-toolbar main-bar';
	textbar.style.display = 'none';

	// Botones del text:
	var b = document.createElement('button');	// NEGRITA
	b.className = 'icon-bold';
	b.title = 'Negrita';
	b.setAttribute('style', '');
	b.addEventListener('click', function(event){
		document.execCommand('bold', false, null);
		if (last_selected != null) last_selected.select();
	}, true);
	textbar.appendChild(b);

	var b = document.createElement('button');	// CURSIVA
	b.className = 'icon-italic';
	b.title = 'Negrita';
	b.setAttribute('style', '');
	b.addEventListener('click', function(event){
		document.execCommand('italic', false, null);
		if (last_selected != null) last_selected.select();
	}, true);
	textbar.appendChild(b);

	var b = document.createElement('button');	// SUBRAYADO
	b.className = 'icon-underline';
	b.title = 'Negrita';
	b.setAttribute('style', '');
	b.addEventListener('click', function(event){
		document.execCommand('underline', false, null);
		if (last_selected != null) last_selected.select();
	}, true);
	textbar.appendChild(b);


	var imagebar = document.createElement('div'); mainbarframe.appendChild(imagebar);
	imagebar.className = 'part-toolbar main-bar';
	imagebar.style.display = 'none';

	// Botones del text:
	var b = document.createElement('button');	// FLOAT LEFT
	b.className = 'icon-image-float-left';
	b.title = 'Negrita';
	b.setAttribute('style', '');
	b.addEventListener('click', function(event){
		if (last_selected != null) {
			last_selected.setAttribute('style', 'float:left; margin-right:16px;');
			last_selected.content.setAttribute('style', '');
			last_selected.select();
		}
	}, true);
	imagebar.appendChild(b);

	var b = document.createElement('button');	// ALIGN LEFT
	b.className = 'icon-image-left';
	b.title = 'Negrita';
	b.setAttribute('style', '');
	b.addEventListener('click', function(event){
		if (last_selected != null) {
			last_selected.setAttribute('style', '');
			last_selected.content.setAttribute('style', 'text-align:left');
			last_selected.select();
		}
	}, true);
	imagebar.appendChild(b);

	var b = document.createElement('button');	// CENTER
	b.className = 'icon-image-center';
	b.title = 'Negrita';
	b.setAttribute('style', '');
	b.addEventListener('click', function(event){
		if (last_selected != null) {
			last_selected.setAttribute('style', '');
			last_selected.content.setAttribute('style', '');
			last_selected.select();
		}
	}, true);
	imagebar.appendChild(b);

	var b = document.createElement('button');	// ALIGN RIGHT
	b.className = 'icon-image-right';
	b.title = 'Negrita';
	b.setAttribute('style', '');
	b.addEventListener('click', function(event){
		if (last_selected != null) {
			last_selected.setAttribute('style', '');
			last_selected.content.setAttribute('style', 'text-align:right');
			last_selected.select();
		}
	}, true);
	imagebar.appendChild(b);

	var b = document.createElement('button');	// FLOAT RIGHT
	b.className = 'icon-image-float-right';
	b.title = 'Negrita';
	b.setAttribute('style', '');
	b.addEventListener('click', function(event){
		if (last_selected != null) {
			last_selected.setAttribute('style', 'float:right; margin-left:16px;');
			last_selected.content.setAttribute('style', '');
			last_selected.select();
		}
	}, true);
	imagebar.appendChild(b);




	var doc = document.createElement('div'); scroll.appendChild(doc);
	doc.className = 'editor-document';
	doc.type = 'top';
	mainbarframe.topReference = doc.offsetTop;
	mainbarframe.redraw = function(){
		var y = mainbarframe.topReference;
		if (last_scrollTop>y) {
			mainbarframe.style.position = 'fixed';
			mainbarframe.style.top = '64px';
			mainbarframe.style.width = doc.clientWidth+'px';
		} else {
			mainbarframe.style.position = 'absolute';
			mainbarframe.style.top = mainbarframe.topReference+'px';			
		}
	};
	window.addEventListener('scroll', function(event){
		var node = event.target;
		if (event.target.nodeType!=1) {
			if (document.documentElement) {
				last_scrollTop = document.documentElement.scrollTop; // Mozilla
			} else {
				last_scrollTop = document.body.scrollTop; // Webkit
			}
		} else {
			last_scrollTop = event.target.scrollTop;
		}

		if (last_selected!=null)
			mainbarframe.redraw();
	}, true);
	doc.focus();

	
	var last_scrollTop = 0;
	var last_selected = null;
	var last_range = null;
	var selection = window.getSelection();

	var createPartAbstract_enter = function(event) {
		if (event.keyCode == 13) {
			event.preventDefault();
			event.stopPropagation();
			var next = this.part.nextSibling;
			if (next == null) {
				// Inserto un componente de texto ?
			} else {
				// Me desplazo al siguiente componente de texto :)
				next.select();
			}
		}
	}

	var createPartAbstract_keydown = function(event) {
		var part = this.part;
		if (event.keyCode == 38 && event.ctrlKey) {
			if (part.previousSibling != null) {
				if (part.nextSibling == null) {
					doc.appendChild(part.previousSibling);
				} else {
					doc.insertBefore(part.previousSibling, part.nextSibling);
				}
			}
			event.stopPropagation();
			event.preventDefault();
		} else if (event.keyCode == 40 && event.ctrlKey) {
			if (part.nextSibling != null) {
				doc.insertBefore(part.nextSibling, part);
			}
			event.stopPropagation();
			event.preventDefault();
		}

		if (event.keyCode >=37 && event.keyCode <= 40 && !event.ctrlKey) {
			last_range = selection.getRangeAt(0);
		}
	}

	var createPartAbstract_keyup = function(event) {
		var part = this.part;
		if (event.keyCode >=37 && event.keyCode <= 40 && !event.ctrlKey) {
			var current_range = selection.getRangeAt(0);
			if (last_range.startContainer.isEqualNode(current_range.startContainer) &&  last_range.startOffset == current_range.startOffset) {
				if (event.keyCode == 39 || event.keyCode == 40) {
					if (this.part.nextSibling != null)
						this.part.nextSibling.select();
				} else if (event.keyCode == 37 || event.keyCode == 38) {
					if (this.part.previousSibling != null)
						this.part.previousSibling.select();

				}
			}
			last_range = selection.getRangeAt(0);
		}
		if((event.keyCode == 8 || event.keyCode == 46 ) && part.textContent.length==0) {
			log('BORRAR PARTE!!');
			part.style.display = 'none';
			if (part.previousSibling != null) {
				log ('pongo el puntero en la anterior');
				part.previousSibling.select();
			} else if (part.nextSibling != null) {
				log ('pongo el puntero en la siguiente');
				part.nextSibling.select();
			}
			doc.removeChild(part);
		}
	}

	var createPartAbstract_select = function() {
		if (last_selected != null) {
			last_selected.className = 'part';
		}
		last_selected = this;
		this.className = 'part part-selected';
		this.focusableElement.focus();
		this.toolbar();
		mainbarframe.topReference = this.offsetTop;
		mainbarframe.style.top = this.offsetTop+'px';
//		mainbarframe.redraw(doc.scrollTop, mainbarframe.offsetTop);
		mainbarframe.redraw();
	}

	var createPartAbstract_click = function(event) {
		this.select();
	}

	var createPartAbstract_focus = function(event) {
		this.part.select();
	}

	var createPartAbstract = function(class_name, focusable_element, toolbar) {					// PARTE ABSTRACTA
		var part = document.createElement('div');
		focusable_element.part = part;
		focusable_element.addEventListener('keydown', createPartAbstract_keydown, true);
		focusable_element.addEventListener('keyup', createPartAbstract_keyup, true);
		focusable_element.addEventListener('focus', createPartAbstract_focus, true);

		var content = document.createElement('div'); part.appendChild(content);
		content.className = class_name;

		part.className = 'part';
		part.content = content;
		part.toolbar = toolbar;
		part.type = 'abstract';
		part.id = 0;
		part.focusableElement = focusable_element;
		part.select = createPartAbstract_select;
		part.addEventListener('click', createPartAbstract_click, true);
		return part;
	};

	var createPartText_blur = function(event) {
		var part = this.parentNode.parentNode;
		var ajax = new Ajax('[[AJAX name=store_text]]');
		ajax.setCallback200(function(text){
			log('guardado');
			//part.content.text.innerHTML = text;
		});
		ajax.query({'id_part':part.id,'text':part.content.text.innerHTML});
	}

	var createPartText_toolbar = function() {
		textbar.style.display = '';
		imagebar.style.display = 'none';
	}

	var createPartText = function() {								// PART TEXT
		var text = document.createElement('div'); // Focusable element
		text.className = 'text';
		text.innerHTML = '';
		text.setAttribute('contentEditable', 'true');
		text.addEventListener('blur', createPartText_blur, true);

		var part = createPartAbstract('part-text', text, createPartText_toolbar);
		part.content.appendChild(text);	
		part.content.text = text;
		part.type = 'text';
		return part;
	};

	var createPartTitle_blur = function(event) {
		var part = this.parentNode.parentNode;
		var ajax = new Ajax('[[AJAX name=store_title]]');
		ajax.setCallback200(function(text){
			log('guardado');
		});
		ajax.query({'id_part':part.id,'text':part.content.text.innerHTML});
	}

	var createPartTitle_toolbar = function() {
		textbar.style.display = 'none';
		imagebar.style.display = 'none';
	}
	
	var createPartTitle = function() {								// PART TITLE
		var text = document.createElement('div'); // Focusable element

		var part = createPartAbstract('part-title', text, createPartTitle_toolbar);

		part.content.appendChild(text);	
		part.content.text = text;
		part.type = 'title';
		text.className = 'text';
		text.innerHTML = '';
		text.setAttribute('contentEditable', 'true');
		text.addEventListener('keydown', createPartAbstract_enter, true);
		text.addEventListener('blur', createPartTitle_blur, true);
		return part;		
	}

	var createPartImage_toolbar = function() {
		textbar.style.display = 'none';
		imagebar.style.display = '';
	}
	
	var createPartImage_blur = function(event) {
		var part = this.part;
		var ajax = new Ajax('[[AJAX name=store_image]]');
		ajax.setCallback200(function(text){
			log('guardado');
		});
		ajax.query({'id_part':part.id,'text':part.content.text.innerHTML});
	}

	var createPartImage = function() {								// PART IMAGE
		var text = document.createElement('div');
		var part = createPartAbstract('part-image', text, createPartImage_toolbar);
		
		var frame = document.createElement('div');
		part.content.appendChild(frame);
		frame.className = 'image-frame';
		
		var image = document.createElement('img');
		frame.appendChild(image);
		image.src = '';
		image.width = 400;
		image.height = 300;
		part.content.image = image;

		frame.appendChild(text);	
		part.content.text = text;
		part.type = 'image';
		text.className = 'text';
		text.innerHTML = '';
		text.setAttribute('contentEditable', 'true');
		text.addEventListener('keydown', createPartAbstract_enter, true);
		text.addEventListener('blur', createPartImage_blur, true);
		return part;
	};

	var insertPartAfterFocus = function(part) {
		if (last_selected==null) {
			// Inserto al final
			doc.appendChild(part);
		} else {
			if (last_selected.nextSibling == null) {
				// Inserto al final
				doc.appendChild(part);
			} else {
				// Inserto justo después
				doc.insertBefore(part, last_selected.nextSibling);
			}
		}
	}

	// LOGGER:

	var log = function(text) {
		var line = document.createElement('div');
		line.className = 'editor-logger-line';
		line.innerHTML = text;
		if(logger.firstChild==null) {
			logger.appendChild(line);
		} else {
			logger.insertBefore(line, logger.firstChild);
		}
	};

	var log_var = function(v) {
		for (key in v)
		log (key + ' => ' + v[key]);
	}

	var log_clear = function() {
		logger.innerHTML = '';
	}

	// CONSTRUCTOR:
	var id_document=0;

	dom.loadDocument = function(id) {
		id_document = id;
		log('loading document...');
		var ajax = new Ajax('[[AJAX name=load_document]]');
		// TODO: Bloquear edición de partes hasta que termine el proceso de carga
		ajax.setCallback200(function(text){
			var json = eval('('+text+')');
			for (key in json) {
				var part = json[key];
				if (part.type == 'TEXT') {
					var new_part = createPartText();
					new_part.id = part.id;
					new_part.content.text.innerHTML = part.text;
					doc.appendChild(new_part);
				} else if (part.type == 'TITLE') {
					var new_part = createPartTitle();
					new_part.id = part.id;
					new_part.content.text.innerHTML = part.text;
					doc.appendChild(new_part);
				} else if (part.type == 'IMAGE') {
					var new_part = createPartImage();
					new_part.id = part.id;
					new_part.content.text.innerHTML = part.text;
					new_part.content.image.src = part.url;
					doc.appendChild(new_part);
				}
			}
		});
		ajax.query({'id':id});
	}

	dom.getComponentVersion = function() {
		return '0.1.1';
	}

	return dom;
};