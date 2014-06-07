var NinjaEditorToolbarGroup = function(parent) {

	this.parent = parent;
	this.dom = null;
	this.name = '';
	this.buttons = {};

	this._buildDom();
};

NinjaEditorToolbarGroup.prototype._buildDom = function() {
	// Add to tree
	this.dom = document.createElement('div');
	this.dom.className = 'NinjaEditor-toolbar-group';

	// Extra configuration
	this.parent.dom.appendChild(this.dom);
};

NinjaEditorToolbarGroup.prototype.add = function(name) {
	var button = this.buttons[name] = document.createElement('button');
	button.className = 'NinjaEditor-toolbar-button';
	button.setAttribute('group', this.name);
	button.setAttribute('button', name);
	this.dom.appendChild(button);
	return button;
};
