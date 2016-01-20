

[[INCLUDE component=ShadowButton]]

var newGraphicTabButton = function() {
	
	var dom = document.createElement('button');
	dom.className = 'shadow-button';

	dom.setText = function(text) {
		dom.innerHTML = text;
	}

	return dom;
};

var newGraphicTab = function() {

	var dom = document.createElement('div');
	dom.className = 'comGraphicTab';

	dom.buttons = new Array();
	dom.selected_id = null;

	var button_callback_click = function(event) {
		for(key in dom.buttons)
			dom.buttons[key].className = 'shadow-button';
		this.className = 'shadow-button shadow-button-blue';
		dom.selected_id = this.id;
	}

	dom.select = function(id) {
		for(key in dom.buttons)
			dom.buttons[key].className = 'shadow-button';
		dom.buttons[id].className = 'shadow-button shadow-button-blue';
		dom.selected_id = id;
	}

	dom.getSelectedId = function() {
		return dom.selected_id;
	}
	
	dom.addButton = function(text, callback) {
		var button = newGraphicTabButton();
		button.id = dom.buttons.length;
		dom.buttons.push(button);
		button.setText(text);
		button.addEventListener('click', button_callback_click, true);
		button.addEventListener('click', callback, true);
		dom.appendChild(button);
		return button;
	}



	return dom;
};
