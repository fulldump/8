[[INCLUDE component=NinjaEditorToolbarGroup]]

var NinjaEditorToolbar = function(parent) {

	this.parent = parent;
	this.groups = {};

	this._buildDom();
};

NinjaEditorToolbar.prototype._buildDom = function() {

	// Add to tree
	this.dom = document.createElement('div');
	this.dom.className = 'NinjaEditor-toolbar';
	this.parent.appendChild(this.dom);

	// Extra configuration
	
	
};

NinjaEditorToolbar.prototype.add = function(name) {
	// TODO: check if 'name' group is yet in use and return it
	var group = this.groups[name] = new NinjaEditorToolbarGroup(this);
	group.name = name;
	return group;
};
