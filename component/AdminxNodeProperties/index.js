// Component AdminxNodeProperties

[[INCLUDE component=Ajax]]
[[INCLUDE component=TrunkButton]]

(function(){
	'use strict'; 

	function AdminxNodeProperties(node) {
		this.node = node;

		this.dom = document.createElement('div');
		this.dom.setAttribute('component', 'AdminxNodeProperties');

		this.build();
	}

	AdminxNodeProperties.prototype.build = function() {
		var that = this;

		this.dom.innerHTML = '';

		that.buildTable();
		that.buildAdd();
	};

	AdminxNodeProperties.prototype.buildTable = function() {
		var that = this;

		function save_key_value(event) {
			var key = this.key;
			var value = this.textContent;

			var ajax = new Ajax('[[AJAX name=save_key_value]]');
			ajax.setCallback200(function(text){
				var json = JSON.parse(text);
				that.node.properties = json.properties;
				that.node.properties_inherited = json.properties_inherited;

				that.build();
			});
			ajax.query({
				id: that.node.id,
				key: key,
				value: value,
			});
		}

		function remove_key(event) {
			var key = this.key;

			var ajax = new Ajax('[[AJAX name=remove_key]]');
			ajax.setCallback200(function(text) {
				var json = JSON.parse(text);
				that.node.properties = json.properties;
				that.node.properties_inherited = json.properties_inherited;

				that.build();
			});
			ajax.query({
				id: that.node.id,
				key: key,
			});
		}

		this.table = document.createElement('table');
		this.dom.appendChild(this.table);

		var tr = document.createElement('tr');
			var td_key = document.createElement('td');
			var td_value = document.createElement('td')
			var td_button = document.createElement('td');

			td_key.textContent = 'id';
			td_value.textContent = this.node.id;

			var button = trunk.create('Button');
			button.dom.setAttribute('disabled', true);
			button.dom.textContent = 'Core';
			td_button.appendChild(button.dom);

			tr.appendChild(td_key);
			tr.appendChild(td_value);
			tr.appendChild(td_button);
		this.table.appendChild(tr);


		var properties = this.getAll();
		for (var k in properties) {
			var tr = document.createElement('tr');
				var td_key = document.createElement('td');
				var td_value = document.createElement('td')
				var td_button = document.createElement('td');

				td_key.textContent = k;

				td_value.textContent = properties[k].value;
				td_value.setAttribute('contentEditable', true);
				td_value.key = k;
				td_value.addEventListener('blur', save_key_value, true);

				var button = trunk.create('Button');
				td_button.appendChild(button.dom);


				if (properties[k].inherited) {
					button.dom.textContent = 'inherited';
					button.dom.setAttribute('disabled', true);
				} else {
					button.dom.classList.add('red');
					button.dom.textContent = 'Remove';
					button.dom.key = k;
					button.dom.addEventListener('click', remove_key, true);
				}


				tr.appendChild(td_key);
				tr.appendChild(td_value);
				tr.appendChild(td_button);
			this.table.appendChild(tr);
		}
	};

	AdminxNodeProperties.prototype.buildAdd = function() {
		var that = this;

		function save_key_value(event) {
			var key = this.key.textContent;
			var value = this.val.textContent;

			var ajax = new Ajax('[[AJAX name=save_key_value]]');
			ajax.setCallback200(function(text){
				var json = JSON.parse(text);
				that.node.properties = json.properties;
				that.node.properties_inherited = json.properties_inherited;

				that.build();
			});
			ajax.query({
				id: that.node.id,
				key: key,
				value: value,
			});
		}

		var tr = document.createElement('tr');
			var td_key = document.createElement('td');
			var td_value = document.createElement('td')
			var td_button = document.createElement('td');

			td_key.textContent = 'new_key';
			td_value.textContent = 'new value';
			
			td_key.setAttribute('contentEditable', true);
			td_value.setAttribute('contentEditable', true);

			var button = trunk.create('Button');
			td_button.appendChild(button.dom);
			button.dom.classList.add('blue');
			button.dom.textContent = 'Add';
			button.dom.key = td_key;
			button.dom.val = td_value;
			button.dom.addEventListener('click', save_key_value, true);

			tr.appendChild(td_key);
			tr.appendChild(td_value);
			tr.appendChild(td_button);
		this.table.appendChild(tr);
	};

	AdminxNodeProperties.prototype.getAll = function() {
		var properties = {};

		for (var k in this.node.properties_inherited) {
			properties[k] = {
				value: this.node.properties_inherited[k],
				inherited: true,
			};
		}

		for (var k in this.node.properties) {
			properties[k] = {
				value: this.node.properties[k],
				inherited: false,
			};
		}

		return properties;
	};

	window.AdminxNodeProperties = AdminxNodeProperties;
})();