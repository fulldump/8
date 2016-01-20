[[INCLUDE component=TrunkInputButton]]

var newGraphicList = function() {
	var dom = document.createElement('div');

	dom.classList.add('TrunkMargin');

	var callback_delete = null;
	var callback_click = null;

	var selected_item = null;
	var item_click_select = function(event) {
		dom.select(this.id);
	};

	dom.setDocked = function(val) {
		if (val) {
			dom.classList.add('graphicList-docked');
		} else {
			dom.classList.remove('graphicList-docked');
		}
	};

	dom.setDocked(true);

	var search_box = trunk.create('InputButton');
	search_box.dom.style.display = 'none';
	search_box.dom.classList.add('search');
	search_box.dom.classList.add('graphicList-searchBox');
	dom.appendChild(search_box.dom);

	var list_box = document.createElement('div');
	list_box.className = 'graphicList-listBox';
	list_box.style.top = '0px';
	list_box.style.bottom = '0px';
	dom.appendChild(list_box);

	var new_box = trunk.create('InputButton');
	new_box.dom.style.display = 'none';
	new_box.dom.classList.add('new');
	new_box.dom.classList.add('graphicList-newBox');
	dom.appendChild(new_box.dom);


	dom.remove = function(id) {
		var childs = list_box.childNodes;
		for(key in childs) {
			if (childs[key].id == id) {
				list_box.removeChild(childs[key]);
			}
		}
	}

	dom.add = function (id, label) {
		var list_item = document.createElement('div'); list_box.appendChild(list_item);
		list_item.className = 'graphicList-listItem';
		list_item.id = id;

		var list_item_button = document.createElement('button'); list_item.appendChild(list_item_button);
		list_item_button.id = id;
		list_item_button.className = 'graphicList-listItem-Button';
		list_item_button.innerHTML = label;
		list_item_button.addEventListener('click', item_click_select, true);
		list_item.button = list_item_button;

		// Añado el evento click
		if (callback_click != null)
			list_item_button.addEventListener('click', callback_click, false);

		// Añado el evento delete
		if (callback_delete != null) {
			var list_item_delete = document.createElement('button'); list_item.appendChild(list_item_delete);
			list_item_delete.id = id;
			list_item_delete.className = 'graphicList-listItem-Delete';
			list_item_delete.addEventListener('click', callback_delete, true);
		}

	};

	dom.setCallbackSearch = function(callback) {
		search_box.input.addEventListener('keyup', function(event) {callback(event, search_box.input.value);}, true);
		search_box.button.addEventListener('click', function(event) {callback(event, search_box.input.value);}, true);
		search_box.dom.style.display = '';
		list_box.style.top = ''; // search_box.clientHeight+'px';
	};

	dom.setCallbackNew = function(callback) {
		new_box.button.addEventListener('click', function(event) {callback(event, new_box.input.value); new_box.input.value = '';}, true);
		new_box.dom.style.display = '';
		list_box.style.bottom = ''; // search_box.clientHeight+'px';
	};

	dom.setCallbackClick = function(callback) {
		callback_click = callback;
	};

	dom.setCallbackDelete = function(callback) {
		callback_delete = callback;
	};

	dom.clear = function() {
		// TODO: poner seleccionado = null
		list_box.innerHTML = '';
	};

	dom.select = function (id) {
		if (selected_item != null)
			selected_item.className = 'graphicList-listItem';
		
		var childs = list_box.childNodes;
		for(key in childs) {
			if (childs[key].id == id) {
				selected_item = childs[key];
				selected_item.className = 'graphicList-listItem graphicList-listItem-selected';
			}
		}
	};

	dom.getSelectedId = function() {
		return selected_item.id;
	};

	dom.getSelected = function() {
		return selected_item;
	};

	dom.search_box = search_box;
	dom.list_box = list_box;
	dom.new_box = new_box;

	return dom;
};
