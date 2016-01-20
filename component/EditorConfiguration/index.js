// Component EditorConfiguration

[[INCLUDE component=Ajax]]
[[INCLUDE component=GraphicList]]
[[INCLUDE component=GraphicTab]]
[[INCLUDE component=CodeEditor]]
[[INCLUDE component=TrunkDouble]]

newEditorConfiguration = function() {
	
	var god = false;
	
	var dom = document.createElement('div');
	dom.className = 'EditorConfiguration';

	var panels = trunk.create('Double');
	dom.appendChild(panels.dom);

	var current = document.createElement('div');
	current.classList.add('EditorConfiguration-current');
	panels.current_info.appendChild(current);


/*	
	var left_panel = document.createElement('div');
	left_panel.className = 'left-panel';
	dom.appendChild(left_panel);

	var right_panel = document.createElement('div');
	right_panel.className = 'right-panel';
	dom.appendChild(right_panel);
*/

	var config_list = newGraphicList();
	panels.left.appendChild(config_list);
	config_list.setCallbackSearch(function(event, text){
		dom.reload();
	});

	if (god) {
		config_list.setCallbackNew(function(event,name){
			var ajax = new Ajax('[[AJAX name=new]]');
			ajax.setCallback200(function(text) {
				if (text != '') {
					config_list.add(text, text);
					dom.select(text);
				}
			});
			ajax.query({'name':name});
		});

		config_list.setCallbackDelete(function(event) {
			var key = this.id;
			var ajax = new Ajax('[[AJAX name=delete]]');
			ajax.setCallback200(function(text) {
				if (text == '')
					config_list.remove(key);
			});
			ajax.query({'key':key});
		});
	}

	config_list.setCallbackClick(function(event) {
		current.innerHTML = this.id;
		panels.detailed(true);
		dom.select(this.id);
	});

	dom.reload = function() {
		var ajax = new Ajax('[[AJAX name=reload]]');
		ajax.setCallback200(function(text) {
			config_list.clear();
			var json = eval('('+text+')');
			for (key in json) {
				config_list.add(json[key], json[key]);
			}
		});
		ajax.query({'query':config_list.search_box.input.value});
	};

	dom.save = function(key, value) {
		var ajax = new Ajax('[[AJAX name=save]]');
		ajax.setCallback200(function(text) {
			dom.select(key);
		});
		ajax.query({'key':key,'value':value});
	};

	dom.restore = function(key) {
		var ajax = new Ajax('[[AJAX name=restore]]');
		ajax.setCallback200(function(text) {
			dom.select(key);
		});
		ajax.query({'key':key});
	};

	dom.select = function(id) {
		panels.right.innerHTML = '';

		var ajax = new Ajax('[[AJAX name=select]]');
		ajax.setCallback200(function(text) {
			var json = eval('('+text+')');
			var config_value = document.createElement('div'); panels.right.appendChild(config_value);
			config_value.className = 'EditorConfiguration-ConfigValue';

			var config_right = document.createElement('div'); config_value.appendChild(config_right);		
			config_right.className = 'EditorConfiguration-ConfigValue-Right';
			config_right.innerHTML = json['description'];

			var config_left = document.createElement('div'); config_value.appendChild(config_left);		
			config_left.className = 'EditorConfiguration-ConfigValue-Left';

			var config_input;

			switch (json['type']) {
				case 'NUMBER':
				case 'STRING':
				case 'MD5':
				case 'EMAIL':
					config_input = document.createElement('input');
					break;
				case 'BOOLEAN':
					config_input = document.createElement('select');
					var op = document.createElement('option'); config_input.appendChild(op);
					op.value = 'true';
					op.innerHTML = 'True';
					
					
					var op = document.createElement('option'); config_input.appendChild(op);
					op.value = 'false';
					op.innerHTML = 'False';
					
					json['value'] = (json['value'] == true) ? 'true' : 'false';

					break;
				default:
					// Es una lista
					if (typeof(json['type'])=='object'&&(json['type'] instanceof Array)) {
						config_input = document.createElement('select');

						var lista = json['type'];
						for (key in lista) {
							var op = document.createElement('option'); config_input.appendChild(op);
							op.value = lista[key];
							op.innerHTML = lista[key];
						}
						
					} else {
						//alert('es un valor no definido');
					}
			}

			config_left.appendChild(config_input);		
			config_input.className = 'EditorConfiguration-ConfigValue-Input';
			config_input.value = json['value'];

			var config_buttons = document.createElement('div'); config_left.appendChild(config_buttons);
			config_buttons.className = 'EditorConfiguration-ConfigValue-Buttons';

			var button_save = document.createElement('button'); config_buttons.appendChild(button_save);
			button_save.className = 'shadow-button shadow-button-blue';
			button_save.innerHTML = 'Save';
			button_save.addEventListener('click', function(event){
				dom.save(json['key'], config_input.value);
			}, true);

			var button_restore = document.createElement('button'); config_buttons.appendChild(button_restore);
			button_restore.className = 'shadow-button shadow-button-blue';
			button_restore.innerHTML = 'Restore default';
			button_restore.addEventListener('click', function(event){
				dom.restore(json['key']);
			}, true);

			

		});
		ajax.query({'key':id});

		config_list.select(id);
	};

	dom.reload();

	return dom;
}