// Component AdminxDatabase

[[INCLUDE component=TrunkDouble]]
[[INCLUDE component=TrunkTab]]
[[INCLUDE component=TrunkDetail]]
[[INCLUDE component=TrunkButton]]
[[INCLUDE component=TrunkTable]]
[[INCLUDE component=GraphicList]]
[[INCLUDE component=Ajax]]

(function(){
	'use strict';

	function AdminxDatabase() {
		this.dom = document.createElement('div');
		this.dom.setAttribute('component', 'AdminxDatabase');

		this.buildSkeleton();
			this.buildListTables();
			this.buildDetail();
				this.buildButtons();
				this.buildTable();
				this.buildModel();
				this.buildCustom();
				this.buildTabs();

		this.loadList('');
	};
	
	AdminxDatabase.prototype.loadList = function(search) {
		var that = this;
		var ajax = new Ajax('[[AJAX name=load_list]]');
		ajax.setCallback200(function(text){
			var json = JSON.parse(text);
			for (var k in json) {
				var table_name = json[k];
				that.list_tables.add(table_name, table_name);
			}
		});
		ajax.query({search: search});
		this.list_tables.clear();
	};

	AdminxDatabase.prototype.buildSkeleton = function(search) {

		this.panels = trunk.create('Double');
		this.dom.appendChild(this.panels.dom);

		this.current = document.createElement('div');
		this.current.classList.add('AdminxDatabase-current');
		this.panels.current_info.appendChild(this.current);

	}

	AdminxDatabase.prototype.buildListTables = function(search) {
		var that = this;

		this.list_tables = newGraphicList();
		this.panels.left.appendChild(this.list_tables);

		this.list_tables.setCallbackClick(function(event) {				// CLICK
			var table_name = that.list_tables.getSelected().id;
			that.current.innerHTML = table_name;
			that.detail.dom.style.display = '';
			that.table.load(table_name)
			that.panels.detailed(true);
		});

		this.list_tables.setCallbackNew(function(event, text) {			// NEW
			alert(text);
		});

		this.list_tables.setCallbackSearch(function(event, text) {		// SEARCH
			that.loadList(text);
		});

		this.list_tables.setCallbackDelete(function(event) {			// DELETE
			var msg = 'Are you sure of delete "'+this.id+'"?';
			if (event.shiftKey || confirm(msg)) {
				var row = this.parentNode;
				var list = that.list_tables.list_box;
				list.removeChild(row);
			}
		});
	}

	AdminxDatabase.prototype.buildDetail = function(search) {
		this.detail = trunk.create('Detail');
		this.detail.dom.style.display = 'none';
		this.panels.right.appendChild(this.detail.dom);
	}

	AdminxDatabase.prototype.buildButtons = function(search) {
		// Button Add Row
		this.button_add_row = trunk.create('Button');
		this.button_add_row.dom.innerHTML = 'Add row';
		this.button_add_row.dom.addEventListener('click', function(e) {

		}, true);
		this.detail.bottom.appendChild(this.button_add_row.dom);

		// Button Add Field
		this.button_add_field = trunk.create('Button');
		this.button_add_field.dom.innerHTML = 'Add field';
		this.button_add_field.dom.addEventListener('click', function(e) {

		}, true);
		this.detail.bottom.appendChild(this.button_add_field.dom);

		// Button Add Row
		this.button_add_index = trunk.create('Button');
		this.button_add_index.dom.innerHTML = 'Add index';
		this.button_add_index.dom.addEventListener('click', function(e) {

		}, true);
		this.detail.bottom.appendChild(this.button_add_index.dom);
	}

	AdminxDatabase.prototype.buildTable = function(search) {
		this.table = trunk.create('Table');
		
		this.detail.center.appendChild(this.table.dom);
	}

	AdminxDatabase.prototype.buildModel = function(search) {
		this.model = document.createElement('div');
		this.model.innerHTML = 'SOY LE MODEL';
		this.detail.center.appendChild(this.model);
	}

	AdminxDatabase.prototype.buildCustom = function(search) {
		this.custom = document.createElement('div');
		this.custom.innerHTML = 'SOY CUSTOM';
		this.detail.center.appendChild(this.custom);
	}

	AdminxDatabase.prototype.buildTabs = function(search) {
		var that = this;
		function hide() {
			that.table.dom.style.display = 'none';
			that.model.style.display = 'none';
			that.custom.style.display = 'none';

			that.button_add_row.dom.style.display = 'none';
			that.button_add_field.dom.style.display = 'none';
			that.button_add_index.dom.style.display = 'none';
		}

		hide();

		this.tabs = trunk.create('Tab');
		this.tabs.add('Data').dom.addEventListener('click', function(e) {
			hide();
			that.table.dom.style.display = '';
			that.button_add_row.dom.style.display = '';
		}, true);
		this.tabs.add('Model').dom.addEventListener('click', function(e) {
			hide();
			that.model.style.display = '';
			that.button_add_field.dom.style.display = '';
			that.button_add_index.dom.style.display = '';
		}, true);
		this.tabs.add('Custom class').dom.addEventListener('click', function(e) {
			hide();
			that.custom.style.display = '';
		}, true);
		this.detail.top.appendChild(this.tabs.dom);

		this.tabs.get(0).dom.click();
	}

	window.AdminxDatabase = AdminxDatabase;

})();
