

[[INCLUDE component=Ajax]]

SimpleList = function() {
	this.setParentNode = function (parentNode) {
		parentNode.appendChild(dom);
	}
	
	this.clear = function () {
		list_element_selected = null;
		dom.innerHTML = '';
	}
	
	this.add = function (id, label) {
		var list_element = document.createElement('button');
		list_element.id = id;
		list_element.innerHTML = label;
		list_element.addEventListener('click', list_element_click, true);
		dom.appendChild(list_element);
	}
	
	this.setCallbackClick = function (cb) {
		callback_click = cb;
	}
	
	this.select = function (id) {
		if (list_element_selected != null)
			list_element_selected.setAttribute('class', '');
		
		
		var childs = dom.childNodes;
		for(key in childs) {
			if (childs[key].id == id) {
				list_element_select(childs[key]);
			}
		}
	}
	
	this.getSelectedId = function () {
		return list_element_selected.id;
	}

	this.getSelected = function() {
		return list_element_selected;
	}
	
	// Atributos y métodos privados
	
	var list_element_click = function(event) {
		list_element_select(this);
	}
	
	var list_element_select = function(button) {
		if (list_element_selected != null)
			list_element_selected.setAttribute('class', '');
			
		list_element_selected = button;
		button.setAttribute('class', 'selected');
		
		if (callback_click != null)
			callback_click(button.id);
	}
	
	// Constructor:
	var dom = document.createElement('div');
	dom.setAttribute('class', 'simple-list');
	
	var callback_click = null;
	var list_element_selected = null;
	
}
