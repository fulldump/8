// Component DeveloperPageEditor

[[INCLUDE component=Ajax]]
[[INCLUDE component=GraphicSimpleTree]]
[[INCLUDE component=GraphicTab]]
[[INCLUDE component=CodeEditor]]
[[INCLUDE component=TrunkDouble]]

newDeveloperPageEditor = function() {
	// Constructor:
	var dom = document.createElement('div');
	dom.setAttribute('class', 'comUserPageEditor');

	var panels = trunk.create('Double');
	dom.appendChild(panels.dom);

	var current = document.createElement('div');
	current.classList.add('DeveloperPageEditor-current');
	panels.current_info.appendChild(current);

	panels.left.classList.add('comUserPageEditor-left');
	panels.right.classList.add('comUserPageEditor-right');

	dom.loadTree = function() {
		// Cargo los nodos:
		var ajax = new Ajax('[[AJAX name=load_pages_tree]]');
		ajax.setCallback200(function(text) {
			panels.left.tree.clear();
			var json = eval('('+text+')');
			dom.loadTreeRec(panels.left.tree, json);
		});
		ajax.query({});
	}

	dom.loadTreeRec = function(node, data) {
		for (key in data) {
			var new_node = panels.left.tree.createNode();
			new_node.setText(data[key]['title']);
			new_node.id = data[key]['id'];
			new_node.className = 'node'+new_node.id;
			node.append(new_node);
			dom.loadTreeRec(new_node, data[key]['children']);
		}
	}

	panels.left.tree = newGraphicSimpleTree();
	panels.left.tree.setCallbackClick(function(event){
		current.innerHTML = this.getText();
		panels.detailed(true);
		event.stopPropagation();
		dom.loadPage(this.id);
	});
	panels.left.tree.setCallbackDelete(function(event){
		event.stopPropagation();
		if (confirm('All subtree will be removed. Are you sure?')) {
			var node = this.parentNode.parentNode;
			var id = node.id;
			var ajax = new Ajax('[[AJAX name=delete_node]]');
			ajax.setCallback200(function(text){
				node.style.display = 'none';
			});
			ajax.query({'id':id});
		}
	});

	dom.loadTree();

	panels.left.bottombar = document.createElement('div');
	panels.left.bottombar.className = 'bottom-bar';
	panels.left.appendChild(panels.left.bottombar);

	var button_new_page = document.createElement('button');
	button_new_page.innerHTML = 'Nueva página';
	button_new_page.className = 'shadow-button shadow-button-blue';
	panels.left.bottombar.appendChild(button_new_page);
	button_new_page.addEventListener('click', function(event){
		var b = panels.left.tree.getSelected();
		var nuevo_id = 0;
		if (b==null) {
		} else {
			nuevo_id = b.id;
		}

		var ajax = new Ajax('[[AJAX name=create_page]]');
		ajax.setCallback200(function(text) {
			dom.loadTree();
		});
		ajax.query({'id':nuevo_id});
	}, true);

	dom.selected_page = null;

	dom.loadPage = function(id) {
		if (id==1) {
			panels.right.style.display = 'none';
		} else {
			panels.right.style.display = '';
		}

		panels.right.innerHTML = '';
		panels.right.top_bar = document.createElement('div');
		panels.right.top_bar.className = 'top-bar';
		var tabber = newGraphicTab();
		panels.right.top_bar.appendChild(tabber);
		panels.right.appendChild(panels.right.top_bar);

		// Meto el cuerpo
		panels.right.center = document.createElement('div');
		panels.right.center.className = 'center';
		panels.right.appendChild(panels.right.center);

		// Meto el bottom bar
		panels.right.bottom_bar = document.createElement('div');
		panels.right.bottom_bar.className = 'bottom-bar';
		panels.right.appendChild(panels.right.bottom_bar);

		if (id != 1) {
			var make_home = document.createElement('button'); panels.right.bottom_bar.appendChild(make_home);
			make_home.className = 'shadow-button';
			make_home.innerHTML = 'Make home';
			make_home.addEventListener('click', function(event){
				if (confirm('Selected page will be home. Continue?')) {
					var ajax = new Ajax('[[AJAX name=make_home]]');
					ajax.setCallback200(function(text){
					});
					ajax.query({'id':id});
				}
			}, true);
		}


		// Preview:
		panels.right.center.preview = document.createElement('iframe');
		panels.right.center.preview.className = 'center-preview';
		panels.right.center.preview.src = '/u/'+id;
		panels.right.center.appendChild(panels.right.center.preview);

		var ajax = new Ajax('[[AJAX name=load_page_code]]');
		ajax.setCallback200(function(text) {
			var json = eval('('+text+')');

			// HTML:
			panels.right.center.edit_html = document.createElement('div');
			panels.right.center.appendChild(panels.right.center.edit_html);
			panels.right.center.edit_html.editor = newCodeEditor();
			panels.right.center.edit_html.appendChild(panels.right.center.edit_html.editor);
			panels.right.center.edit_html.className = 'center-edit';
			panels.right.center.edit_html.editor.setValue(json.html);
			panels.right.center.edit_html.style.display = 'none';
			panels.right.center.edit_html.editor.setBlurCallback(
				function(event) {
					var ajax = new Ajax('[[AJAX name=set_page_html]]');
		this.selectionStart = 0;
		this.selectionEnd = 0;
					ajax.setCallback200(function(text){
						panels.right.center.edit_html.editor.value=text;
					});
					ajax.query({'id':id,'code':this.value});
				}
			);

			// CSS:
			panels.right.center.edit_css = document.createElement('div');
			panels.right.center.appendChild(panels.right.center.edit_css);
			panels.right.center.edit_css.editor = newCodeEditor();
			panels.right.center.edit_css.appendChild(panels.right.center.edit_css.editor);
			panels.right.center.edit_css.className = 'center-edit';
			panels.right.center.edit_css.editor.setValue(json.css);
			panels.right.center.edit_css.style.display = 'none';
			panels.right.center.edit_css.editor.setBlurCallback(
				function(event) {
					var ajax = new Ajax('[[AJAX name=set_page_css]]');
					ajax.query({'id':id,'code':this.value});
				}
			);

			// JS:
			panels.right.center.edit_js = document.createElement('div');
			panels.right.center.appendChild(panels.right.center.edit_js);
			panels.right.center.edit_js.editor = newCodeEditor();
			panels.right.center.edit_js.appendChild(panels.right.center.edit_js.editor);
			panels.right.center.edit_js.className = 'center-edit';
			panels.right.center.edit_js.editor.setValue(json.js);
			panels.right.center.edit_js.style.display = 'none';
			panels.right.center.edit_js.editor.setBlurCallback(
				function(event) {
					var ajax = new Ajax('[[AJAX name=set_page_js]]');
					ajax.query({'id':id,'code':this.value});
				}
			);

			// AJAX:
			panels.right.center.edit_ajax = document.createElement('div');
			panels.right.center.appendChild(panels.right.center.edit_ajax);
			panels.right.center.edit_ajax.appendChild(newCodeEditor());
			panels.right.center.edit_ajax.className = 'center-edit';
			panels.right.center.edit_ajax.style.display = 'none';



		});
		ajax.query({'id':panels.left.tree.getSelected().id});



		// Meta:
		panels.right.center.meta = document.createElement('div');
		panels.right.center.meta.style.display = 'none';
		panels.right.center.meta.className = 'center-meta';
		panels.right.center.appendChild(panels.right.center.meta);
		var meta_table = document.createElement('table');
			var tr = document.createElement('tr');
				var td = document.createElement('td');
				td.innerHTML = 'Title';
				tr.appendChild(td);
				var td = document.createElement('td');
				var input1 = document.createElement('textarea');
				input1.addEventListener('blur',
					function(event) {
						var ajax = new Ajax('[[AJAX name=set_page_title]]');
						var txt = input1.value;
						ajax.setCallback200(function(text) {
							//var json = eval('('+text+')');
							//input1.value = json['title'];
							panels.left.tree.last_selected.setText(txt);
						});
						ajax.query({'id':panels.left.tree.getSelected().id,'title':txt});
					},
					true
				);
				td.appendChild(input1);
				tr.appendChild(td);
				var td = document.createElement('td');
				td.innerHTML = 'Elige un título que indique claramente el tema de la página. <a style="color:blue;cursor:pointer;" onclick="document.getElementById(\'info_title\').style.display=\'block\';this.style.display=\'none\';">Ver más info...</a><div id="info_title" style="display:none;"><p>Evita:<ul><li>Un título que no tenga ninguna relación con el contenido de la página</li><li>El uso de títulos predeterminados o demasiado genéricos como “Sin título” o “Página nueva”</li></ul></p><p>Lo ideal es que cada una de tus páginas tenga un <em>Title</em> único, que ayude a Google a distinguir esa página del resto de páginas de tu sitio.</p><p>Evita:<ul><li>El uso de una sola etiqueta title para todas las páginas de tu sitio, o para muchas de ellas.</li></ul></p><p>Los títulos pueden ser concisos pero informativos. Si el título es demasiado largo, Google mostrará tan solo una parte del mismo en el resultado de búsqueda.</p><p>Evita:<ul><li>Títulos muy largos que no sean útiles para los usuarios</li><li>Rellenar las etiquetas title con palabras clave innecesarias</li></ul></p>';
				tr.appendChild(td);
			meta_table.appendChild(tr);

			var tr = document.createElement('tr');
				var td = document.createElement('td');
				td.innerHTML = 'Keywords';
				tr.appendChild(td);
				var td = document.createElement('td');
				var input2 = document.createElement('textarea');
				input2.addEventListener('blur',
					function(event) {
						var ajax = new Ajax();
						var ajax = new Ajax('[[AJAX name=set_page_keywords]]');
						ajax.setCallback200(function(text) {
							//var json = eval('('+text+')');
							//input1.value = json['title'];
						});
						ajax.query({'id':panels.left.tree.getSelected().id,'keywords':input2.value});
					},
					true
				);
				td.appendChild(input2);
				tr.appendChild(td);
				var td = document.createElement('td');
				td.innerHTML = 'Google informó, en Diciembre de 2009, que dejaba de prestar atención a las <em>Keywords</em> en su algoritmo de posicionamiento. Sin embargo, las <em>Keywords</em> siguen siendo siendo útiles para: <a style="color:blue;cursor:pointer;" onclick="document.getElementById(\'info_keywords\').style.display=\'block\';this.style.display=\'none\';">Ver más info...</a><div id="info_keywords" style="display:none;"><ul><li>Otros buscadores</li><li>Sistemas de gestión documental</li><li>Encontrar información de una página</li><li>Localizar una página por sus keywords</li></ul></div>';
				tr.appendChild(td);
			meta_table.appendChild(tr);

			var tr = document.createElement('tr');
				var td = document.createElement('td');
				td.innerHTML = 'Description';
				tr.appendChild(td);
				var td = document.createElement('td');
				var input3 = document.createElement('textarea');
				input3.addEventListener('blur',
					function(event) {
						var ajax = new Ajax();
						var ajax = new Ajax('[[AJAX name=set_page_description]]');
						ajax.setCallback200(function(text) {
							//var json = eval('('+text+')');
							//input1.value = json['title'];
						});
						ajax.query({'id':panels.left.tree.getSelected().id,'description':input3.value});
					},
					true
				);

				td.appendChild(input3);
				tr.appendChild(td);
				var td = document.createElement('td');
				td.innerHTML = 'La metaetiqueta <em>Description</em> de una página proporciona a Google y a otros motores de búsqueda un resumen sobre la página. Mientras que el título de una página contiene unas pocas palabras, la metaetiqueta <em>Description</em> podría contener <strong>un par de frases o incluso un párrafo corto</strong>. <a style="color:blue;cursor:pointer;" onclick="document.getElementById(\'info_description\').style.display=\'block\';this.style.display=\'none\';">Ver más info...</a><div id="info_description" style="display:none;"><p>Las metaetiquetas <em>Description</em> son importantes ya que Google podría utilizarlas como fragmentos de descripción de tus páginas. Ten en cuenta que decimos “podría” porque Google podría optar por utilizar una parte relevante del texto visible de tu página si ésta concuerda con la consulta del usuario. Google también podría usar la descripción del Open Directory Project, si éste sitio web está incluido.</p><p>Agregar metaetiquetas <em>Description</em> para cada una de tus páginas es siempre una buena práctica en caso de que Google no pueda encontrar un buen texto a utilizar como fragmento. Encontrarás una entrada sobre cómo mejorar los fragmentos con metaetiquetas description en el Blog para webmasters de Google. Escribe una descripción que informe y a su vez cree interés en los usuarios en caso de que encuentren esa metaetiqueta description como fragmento de un resultado de búsqueda.</p><p>Evita:<ul><li>Una metaetiqueta description con contenido no relacionado con la página</li><li>Descripciones genéricas como “Esto es una página web” o “Página sobre cromos de béisbol”</li><li>Una descripción con sólo palabras clave</li><li>Copiar y pegar todo el contenido del documento en una metaetiqueta description</li></ul></p><p>Tener una metaetiqueta diferente para cada página ayuda tanto a los usuarios como a Google, especialmente en búsquedas en las que los usuarios pueden obtener varias páginas de tu dominio (por ejemplo, búsquedas con el operador site:). Si tu sitio tiene miles o incluso millones de páginas, la elaboración de metaetiquetas description a mano no será factible. En este caso, se pueden generar automáticamente basándose en el contenido de cada página.</p><p>Evita:<ul><li>Utilizar una única metaetiqueta description en todas las páginas de tu sitio o en un gran grupo de páginas de tu sitio</li></ul></p></div>';
				tr.appendChild(td);
			meta_table.appendChild(tr);



			var tr = document.createElement('tr');
				var td = document.createElement('td');
				td.innerHTML = 'Template';
				tr.appendChild(td);
				var td = document.createElement('td');
				var input4 = document.createElement('select');
				input4.addEventListener('click',
					function(event) {
						var ajax = new Ajax();
						var ajax = new Ajax('[[AJAX name=set_page_template]]');
						ajax.query({'page_id':panels.left.tree.getSelected().id,'template':input4.value});
					},
					true
				);

				td.appendChild(input4);
				tr.appendChild(td);
				var td = document.createElement('td');
				td.innerHTML = 'Selecciona una plantilla...';
				tr.appendChild(td);
			meta_table.appendChild(tr);



		panels.right.center.meta.appendChild(meta_table);		

		


		// Meto la barra de menús

		
		// Meto las pestañas
		var tabber_callback_click = function(event) {
			panels.right.center.preview.style.display = 'none';
			panels.right.center.edit_html.style.display = 'none';
			panels.right.center.edit_css.style.display = 'none';
			panels.right.center.edit_js.style.display = 'none';
			panels.right.center.edit_ajax.style.display = 'none';
			panels.right.center.meta.style.display = 'none';
			if (this.id==0) {
				panels.right.center.preview.style.display = 'block';
				// TODO: recordar la posición del scroll
				panels.right.center.preview.src = '/u/'+id;
			}
			if (this.id==1) {
				panels.right.center.edit_html.style.display = 'block';
			}
			if (this.id==2) {
				panels.right.center.edit_css.style.display = 'block';
			}
			if (this.id==3) {
				panels.right.center.edit_js.style.display = 'block';
			}
			if (this.id==4) {
				panels.right.center.edit_ajax.style.display = 'block';
			}
			if (this.id==5) {
			var ajax = new Ajax();
			var ajax = new Ajax('[[AJAX name=load_page_info]]');
			ajax.setCallback200(function(text) {
				var json = eval('('+text+')');
				input1.value = json['title'];
				input2.value = json['keywords'];
				input3.value = json['description'];

				input4.innerHTML = '';
				var templates = json['templates'];
				for (key in templates) {
					var item = document.createElement('option');
					item.innerHTML = templates[key].name;
					item.value = templates[key].value;
					console.log(json);
					if (item.value == json['template']) item.selected = true;
					input4.appendChild(item);
				}

				panels.right.center.meta.style.display = 'block';
			});
			ajax.query({'id':panels.left.tree.getSelected().id});
			}
		};
		tabber.addButton('Vista previa', tabber_callback_click);
		tabber.addButton('HTML', tabber_callback_click);
		tabber.addButton('CSS', tabber_callback_click);
		tabber.addButton('JS', tabber_callback_click);
		tabber.addButton('AJAX', tabber_callback_click).style.display='none';
		tabber.addButton('Meta', tabber_callback_click);


		// Selecciono la vista previa:
		tabber.select(0);
	}

	panels.left.appendChild(panels.left.tree);

	return dom;
}