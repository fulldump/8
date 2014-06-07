[[INCLUDE component=Ajax]]
[[INCLUDE component=GraphicList]]
[[INCLUDE component=GraphicTab]]
[[INCLUDE component=CodeEditor]]
[[INCLUDE component=TrunkDouble]]


var newEditorTemplate = function() {
	
	var dom = document.createElement('div');
	dom.className = 'comTemplateEditor';
	
	var panels = trunk.create('Double');
	dom.appendChild(panels.dom);

	var current = document.createElement('div');
	current.classList.add('EditorTemplate-current');
	panels.current_info.appendChild(current);

	var templates_list = newGraphicList();
	panels.left.appendChild(templates_list);
	templates_list.setCallbackSearch(function(event, text){
		dom.reloadTemplatesList();
	});
	templates_list.setCallbackNew(function(event, name) {
		ajax = new Ajax('[[AJAX name=new_template]]');
		ajax.setCallback200(
			function (text) {
				templates_list.add(text, name);
				templates_list.select(text);
				dom.loadTemplate(text);
			}
		);
		ajax.query({'name':name});
	});

	dom.deleteTemplate = function(id) {
		var ajax = new Ajax('[[AJAX name=delete_template]]');
		ajax.setCallback200(function(text){
			// Nothing to do
		});
		ajax.query({'id_template':id});
	};

	dom.loadTemplate = function(id) {
		document.title = templates_list.getSelected().button.innerHTML+' (Template)';

		panels.right.innerHTML = 'Loading...';
		
		var ajax = new Ajax('[[AJAX name=load_template]]');
		ajax.setCallback200(function(text){
			panels.right.innerHTML = '';

			var json = eval('('+text+')');

			var top_bar = document.createElement('div');
			top_bar.className = 'top-bar';
			panels.right.appendChild(top_bar);

			var top_bar_g = newGraphicTab();
			top_bar.appendChild(top_bar_g);
			var callback_top_bar = function(event) {
				center_html.style.display = '';
				center_css.style.display = '';
				center_js.style.display = '';
				
				if (this.id==0) {
					center_html.style.display = 'block';
				}
				if (this.id==1) {
					center_css.style.display = 'block';
				}
				if (this.id==2) {
					center_js.style.display = 'block';
				}
			};
			top_bar_g.addButton('HTML', callback_top_bar);
			top_bar_g.addButton('CSS', callback_top_bar);
			top_bar_g.addButton('JS', callback_top_bar);
			top_bar_g.addButton('Styles', callback_top_bar).style.display='none';

			var center_bar = document.createElement('div');
			center_bar.className = 'center-bar';
			panels.right.appendChild(center_bar);


			// HTML
			var center_html = document.createElement('div');
			center_bar.appendChild(center_html);
			var editor_html = newCodeEditor();
			center_html.appendChild(editor_html);
			editor_html.setValue(json.html);
			center_html.className = 'center-html';
			editor_html.setBlurCallback( function(event) {
				var ajax = new Ajax('[[AJAX name=store_html]]');
				ajax.query({'id_template':id,'html':this.value});
			});

			// CSS
			var center_css = document.createElement('div');
			center_bar.appendChild(center_css);
			var editor_css = newCodeEditor();
			center_css.appendChild(editor_css);
			editor_css.setValue(json.css);
			center_css.className = 'center-css';
			editor_css.setBlurCallback( function(event) {
				var ajax = new Ajax('[[AJAX name=store_css]]');
				ajax.query({'id_template':id,'css':this.value});
			});

			// JS
			var center_js = document.createElement('div');
			center_bar.appendChild(center_js);
			var editor_js = newCodeEditor();
			center_js.appendChild(editor_js);
			editor_js.setValue(json.js);
			center_js.className = 'center-js';
			editor_js.setBlurCallback( function(event) {
				var ajax = new Ajax('[[AJAX name=store_js]]');
				ajax.query({'id_template':id,'js':this.value});
			});

			top_bar_g.select(0);
			center_html.style.display = 'block';
		});
		ajax.query({'id_template':id});
	};

	templates_list.setCallbackClick(function(event){
		current.innerHTML = this.id;
		panels.detailed(true);
		dom.loadTemplate(this.id);
	});

	templates_list.setCallbackDelete(function(event) {
		if (confirm('Are you sure?')) {
			dom.deleteTemplate(this.id);
			templates_list.remove(this.id);
		}
	});

	dom.reloadTemplatesList = function() {
		var ajax = new Ajax('[[AJAX name=load_templates_list]]');
		ajax.setCallback200(function(text){
			var json = eval('('+text+')');
			templates_list.clear();
			for (key in json)
				templates_list.add(json[key].id, json[key].name);
		});
		ajax.query({'search':templates_list.search_box.input.value});
	}

	dom.reloadTemplatesList();

	return dom;
};
