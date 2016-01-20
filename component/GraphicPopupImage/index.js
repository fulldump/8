[[INCLUDE component=GraphicPopup]]
[[INCLUDE component=GraphicList]]



var newGraphicPopupImage = function() {

	var dom = newGraphicPopup();
	var callback_image = null;

	var panel = document.createElement('div');
	panel.className = 'panel-insert-image';
	dom.appendContent(panel);

	var panel_left = document.createElement('div');
	panel_left.className = 'panel-left';
	panel.appendChild(panel_left);

	var panel_right = document.createElement('div');
	panel_right.className = 'panel-right';
	panel.appendChild(panel_right);

	var image_sources = newGraphicList();
	image_sources.setDocked(false);
	panel_left.appendChild(image_sources);

	image_sources.setCallbackClick(function(event) {
		sources[this.id]();
	});

	image_sources.add(1, 'Buscar en mi disco duro');
	//image_sources.add(2, 'Pegar URL http://...');
	image_sources.add(3, 'Mis imágenes');
	//image_sources.add(5, 'Búsqueda en Google');

	dom.setCallback (function(event) {
		sources_callback[image_sources.getSelectedId()]();
	});

	dom.setCallbackImage = function(cb) {
		callback_image = cb;
	};

	var sources = new Array();
	var sources_callback = new Array();


	// Source 1: A file from HDD
	var input_file;
	var form;
	var iframe_id = 'upload_image_from_hdd'+(new Date()).getTime();
	var iframe_target = 'target'+(new Date()).getTime();
	sources[1] = function() {
		panel_right.innerHTML = 'Selecciona una imagen jpeg/png desde tu disco duro:<br><br>';
		var g = document.createElement('div'); panel_right.appendChild(g);
		g.innerHTML = '<iframe id="'+iframe_id+'" style="display:none;" name="'+iframe_target+'"></iframe>';
		var iframe;
		form = document.createElement('form'); panel_right.appendChild(form);
		form.target = iframe_target;
		form.action = '[[AJAX name=upload_image_from_hdd]]';
		form.method = 'post';
		form.enctype = 'multipart/form-data';
		input_file = document.createElement('input'); form.appendChild(input_file);
		input_file.type = 'file';
		input_file.name = 'image';

	};

	sources_callback[1] = function() {
		if (input_file.files[0].type != 'image/png' && input_file.files[0].type != 'image/jpeg'  && input_file.files[0].type != 'image/gif' ) {
			alert('Debe seleccionar una imagen válida');
		} else {
			form.submit();
			// ANIMACIÓN DE ENVIANDO...
			iframe = document.getElementById(iframe_id);
			iframe.addEventListener('load', function(event) {
				dom.hide();
				// Actualizo la imagen si lo que me devuelve es mayor de cero
				var text = this.contentDocument.body.textContent;
				// Devuelvo el resultado json evaluado
				var image = eval('('+text+')');
				if (callback_image != null) {
					callback_image(image);
				}
			}, true);
		}
	}

	// Source 2: A file from url
	sources[2] = function() {
		panel_right.innerHTML = 'A partir de una URL :)';
	};

	sources_callback[2] = function() {
		alert('TODO: File from url');
	}

	// Source 3: My images
	var s3_div1;
	var s3_selected = null;
	var s3_click=function(event) {
		if (s3_selected != null)
			s3_selected.className = '';
		s3_selected = this;
		s3_selected.className = 's3_selected';
	};
	var s3_dblclick=function(event) {
		if (s3_selected != null)
			s3_selected.className = '';
		s3_selected = this;
		s3_selected.className = 's3_selected';
		dom.hide();
		if (callback_image != null) {
			callback_image({'id':s3_selected.image_id});
		}
	};
	sources[3] = function() {
		s3_selected = null;
		panel_right.innerHTML = '';

		s3_div1 = document.createElement('div'); panel_right.appendChild(s3_div1);
		s3_div1.className = 'source-3';

		var ajax = new Ajax('[[AJAX name=load_my_images]]');
		ajax.setCallback200(function(text){
			var json = eval('('+text+')');
			for (key in json) {
				var img = document.createElement('img');
				img.image_id = json[key];
				img.addEventListener('click', s3_click, true);
				img.addEventListener('dblclick', s3_dblclick, true);
				img.src = '/img/'+json[key]+'/w:64;h:64;q:50';
				s3_div1.appendChild(img);
			}
		});
		ajax.query({});
	};

	sources_callback[3] = function() {
		dom.hide();
		if (callback_image != null) {
			callback_image({'id':s3_selected.image_id});
		}
	}


	// Source 5: My images
	sources[5] = function() {
		panel_right.innerHTML = 'Búsqueda en Google';
	};

	sources_callback[5] = function() {
		alert('TODO: File from url');
	}



	image_sources.select(1);
	sources[1]();

	return dom;
};