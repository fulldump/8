// Component ComponentEditor

[[INCLUDE component=CodeMirror]]
[[INCLUDE component=CodeMirrorMatchbrackets]]
[[INCLUDE component=CodeMirrorHTMLMixed]]
[[INCLUDE component=CodeMirrorXML]]
[[INCLUDE component=CodeMirrorJavascript]]
[[INCLUDE component=CodeMirrorCSS]]
[[INCLUDE component=CodeMirrorClike]]
[[INCLUDE component=CodeMirrorPHP]]
[[INCLUDE component=CodeMirrorFold]]
[[INCLUDE component=CodeMirrorFullscreen]]
[[INCLUDE component=GraphicList]]
[[INCLUDE component=SimpleList]]
[[INCLUDE component=Ajax]]
[[INCLUDE component=GraphicTab]]
[[INCLUDE component=CodeEditor]]
[[INCLUDE component=TrunkDouble]]

newComponentEditor = function() {

	var dom = document.createElement('div');

	var panels = trunk.create('Double');
	dom.appendChild(panels.dom);

	var current = document.createElement('div');
	current.classList.add('ComponentEditor-current');
	panels.current_info.appendChild(current);

	var list_components = newGraphicList();
	panels.left.appendChild(list_components);

	list_components.setCallbackClick(function(event) {
		panels.detailed(true);
		current.innerHTML = this.id;
		loadComponent(this.id);
	});
	list_components.setCallbackNew(function(event,name){
		ajax = new Ajax('[[AJAX name=new_component]]');
		ajax.setCallback200(
			function (text) {
				list_components.add(text, name);
				list_components.select(text);
				loadComponent(text);
			}
		);
		ajax.query({'name':name});
	});
	list_components.setCallbackSearch(function(event, name) {
		dom.reload();
	});
	list_components.setCallbackDelete(function(event){
		if (confirm('Are you sure?')) {
			dom.deleteComponent(this.id);
			list_components.remove(this.id);
		}
	});

	dom.deleteComponent = function(id) {
		var ajax = new Ajax('[[AJAX name=delete_component]]');
		ajax.setCallback200(function(text){
			// Nothing to do
		});
		ajax.query({'id_component':id});
	};

	dom.reload = function() {
		var ajax = new Ajax('[[AJAX name=load_list_components]]');
		ajax.setCallback200(function(text) {
			var json = eval('('+text+')');
			list_components.clear();
			for (key in json) {
				var name = json[key];
				list_components.add(name, name);
			}
		});
		ajax.query({'query':list_components.search_box.input.value});
	}

	dom.reload();

	var loadComponent = function(id) {
		document.title = list_components.getSelected().button.innerHTML+' (Component)';

		var editor_js = null;
		var editor_css = null;
		var editor_html = null;
		var editor_ajax = null;

		panels.right.innerHTML = '';

		var ajax = new Ajax('[[AJAX name=load_component]]');
		ajax.setCallback200(function(text){
			var json = eval('('+text+')');

			var topbar = newGraphicTab();
			topbar.className = topbar.className + ' topbar';
			panels.right.appendChild(topbar);

			var topbar_callback_click = function(event) {
				editor_js.style.display = '';
				editor_css.style.display = '';
				editor_html.style.display = '';
				editor_ajax.style.display = '';

				if (this.id==0) {
					editor_js.style.display = 'block';
					editor_js.editor.refresh();
				}
				if (this.id==1) {
					editor_css.style.display = 'block';
					editor_css.editor.refresh();
				}
				if (this.id==2) {
					editor_html.style.display = 'block';
					editor_html.editor.refresh();
				}
				if (this.id==3) {
					editor_ajax.style.display = 'block';
					editor_ajax.innerHTML = '';

					var right_panel = document.createElement('div');
					editor_ajax.appendChild(right_panel);
					right_panel.className = 'editor-ajax-right-panel';
					var list_ajax = new SimpleList();
					list_ajax.setParentNode(right_panel);
					list_ajax.setCallbackClick(function(id) {
					var ajax = new Ajax('[[AJAX name=load_ajax]]');
						ajax.setCallback200(
							function(text){
								center_panel.innerHTML = '';
								var center_panel_margin = document.createElement('div');
								center_panel_margin.className = 'editor-ajax-center-panel-margin';
								center_panel.appendChild(center_panel_margin);
								// var editor = document.createElement('div');
								// center_panel_margin.appendChild(editor);
								editor = CodeMirror(
									function(elt) {
										center_panel_margin.appendChild(elt);
									},
									{
										height: "100%",
										lineNumbers: true,
										matchBrackets: true,
										mode: 'application/x-httpd-php',
										indentUnit: 4,
										indentWithTabs: true,
										foldGutter: true,
										gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
									}
								);
								editor.on('blur', function(cm) {
									var ajax = new Ajax('[[AJAX name=store_ajax]]');
									ajax.query({
										'component_name':list_components.getSelectedId(),
										'ajax_name': id,
										'ajax_code': cm.getValue()
									});
								});
								editor.setValue(text);
							}
						);
						ajax.query({
							'component_name': list_components.getSelectedId(),
							'ajax_name': id
						});
					});
					
					var center_panel = document.createElement('div');
					center_panel.className='editor-ajax-center-panel';
					editor_ajax.appendChild(center_panel);

					var ajax = new Ajax('[[AJAX name=load_ajax_list]]');
					ajax.setCallback200(
						function(text){
							var json = eval('('+text+')');
							list_ajax.clear();
							for (key in json) {
								list_ajax.add(json[key].id, json[key].name);
							}
						}
					);
					ajax.query({'id_component':id});
				}

			}

			topbar.addButton('JS', topbar_callback_click);
			topbar.addButton('CSS', topbar_callback_click);
			topbar.addButton('HTML', topbar_callback_click);
			topbar.addButton('AJAX', topbar_callback_click);

			var center = document.createElement('div');
			center.className = 'center';
			panels.right.appendChild(center);


			editor_js = document.createElement('div');
			center.appendChild(editor_js);
			editor_js.editor = CodeMirror(
				function(elt) {
					editor_js.appendChild(elt);
				},
				{
					height: "100%",
					lineNumbers: true,
					matchBrackets: true,
					mode: {name: 'javascript', json: true},
					indentUnit: 4,
					indentWithTabs: true,
					foldGutter: true,
					gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
				}
			);
			editor_js.editor.on('blur', function(cm) {
				var ajax = new Ajax('[[AJAX name=store_js]]');
				ajax.query({'id_component':id,'js':cm.getValue()});
			});
			editor_js.editor.setValue(json.js);
			editor_js.className = 'editor-js';
			

			editor_css = document.createElement('div');
			center.appendChild(editor_css);
			editor_css.editor = CodeMirror(
				function(elt) {
					editor_css.appendChild(elt);
				},
				{
					height: "100%",
					lineNumbers: true,
					matchBrackets: true,
					mode: 'css',
					indentUnit: 2,
					indentWithTabs: false,
					foldGutter: true,
					gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
					extraKeys: {
						"F11": function(cm) {
							cm.setOption("fullScreen", !cm.getOption("fullScreen"));
						},
						"Esc": function(cm) {
							if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
						}
					}
				}
			);
			editor_css.editor.on('blur', function(cm) {
				var ajax = new Ajax('[[AJAX name=store_css]]');
				ajax.query({'id_component':id,'css':cm.getValue()});
			});
			editor_css.editor.setValue(json.css);
			editor_css.className = 'editor-css';




			editor_html = document.createElement('div');
			center.appendChild(editor_html);
			editor_html.editor = CodeMirror(
				function(elt) {
					editor_html.appendChild(elt);
				},
				{
					height: "100%",
					lineNumbers: true,
					matchBrackets: true,
					mode: 'application/x-httpd-php',
					indentUnit: 4,
					indentWithTabs: true,
					foldGutter: true,
					gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
				}
			);
			editor_html.editor.on('blur', function(cm) {
				var ajax = new Ajax('[[AJAX name=store_html]]');
				ajax.query({'id_component':id,'html':cm.getValue()});
			});
			editor_html.editor.setValue(json.html);
			editor_html.className = 'editor-html';


			editor_ajax = document.createElement('div');
			editor_ajax.innerHTML = 'editor ajax';
			editor_ajax.className = 'editor-ajax';
			center.appendChild(editor_ajax);




			topbar.select(0);
			editor_js.style.display='block';
		});
		ajax.query({'id_component':id});
	}



	return dom;
}