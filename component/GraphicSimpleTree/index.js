

[[INCLUDE component=Ajax nota='De momento este include no es necesario']]


newGraphicSimpleTreeNode = function(tree) {
	var dom = document.createElement('div');

	var info = document.createElement('div');
	info.setAttribute('class', 'comGraphic-simple-tree-node-info');
	info.setAttribute('draggable', 'true');
	info.addEventListener('dragover', function(event) {
		event.preventDefault();
		event.stopPropagation();
	}, false);
	info.addEventListener('dragenter', function(event) {
	}, false);
	info.addEventListener('dragleave', function(event) {
	}, false);
	info.addEventListener('drop', function(event) {
		event.preventDefault();
		event.stopPropagation();
	}, false);
	info.addEventListener('dragend', function(event) {
	}, false);
	info.addEventListener('dragstart', function(event) {
		event.dataTransfer.setData('origin', this.parentNode.id);
	}, false);


	dom.info = info; dom.appendChild(info);

	// TODO: añadir sólo si hay evento delete:
	if (tree.callback_delete != null) {
		var button_delete = document.createElement('button'); info.appendChild(button_delete);
		button_delete.className = 'comGraphic-simple-tree-node-info-delete';
		button_delete.addEventListener('click', tree.callback_delete, true);
	}





	var label_pre = document.createElement('div');
	label_pre.setAttribute('class', 'comGraphic-simple-tree-node-pre');
	label_pre.addEventListener('dragenter', function(event){this.setAttribute('class', 'comGraphic-simple-tree-node-pre comGraphic-simple-tree-node-pre-drag');}, false);
	label_pre.addEventListener('dragleave', function(event){this.setAttribute('class', 'comGraphic-simple-tree-node-pre')}, false);
	label_pre.addEventListener('drop', function(event){
		this.setAttribute('class', 'comGraphic-simple-tree-node-pre');
		var destination = this.parentNode.parentNode.id;
		var origin = event.dataTransfer.getData('origin');
		if (null !== tree.callback_drop) {
			tree.callback_drop(event, origin, destination, 'insert_before');
		}
	}, false);
	info.appendChild(label_pre);

	var label = document.createElement('div');
	label.setAttribute('class', 'comGraphic-simple-tree-node-label');
	label.addEventListener('dragenter', function(event){this.setAttribute('class', 'comGraphic-simple-tree-node-label comGraphic-simple-tree-node-label-drag');}, false);
	label.addEventListener('dragleave', function(event){this.setAttribute('class', 'comGraphic-simple-tree-node-label')}, false);
	label.addEventListener('drop', function(event){
		this.setAttribute('class', 'comGraphic-simple-tree-node-label');
		var destination = this.parentNode.parentNode.id;
		var origin = event.dataTransfer.getData('origin');
		if (null !== tree.callback_drop) {
			tree.callback_drop(event, origin, destination, 'append');
		}
	}, false);
	info.appendChild(label);

	var label_post = document.createElement('div');
	label_post.setAttribute('class', 'comGraphic-simple-tree-node-post');
	label_post.addEventListener('dragenter', function(event){this.setAttribute('class', 'comGraphic-simple-tree-node-pre comGraphic-simple-tree-node-pre-drag');}, false);
	label_post.addEventListener('dragleave', function(event){this.setAttribute('class', 'comGraphic-simple-tree-node-pre')}, false);
	label_post.addEventListener('drop', function(event){
		this.setAttribute('class', 'comGraphic-simple-tree-node-pre');
		var destination = this.parentNode.parentNode.id;
		var origin = event.dataTransfer.getData('origin');
		if (null !== tree.callback_drop) {
			tree.callback_drop(event, origin, destination, 'insert_after');
		}
	}, false);
	info.appendChild(label_post);

	var children = document.createElement('div');
	children.setAttribute('class', 'comGraphic-simple-tree-node-children');
	dom.appendChild(children);

	dom.setText = function(text) {
		label.innerHTML = text;
	}

	dom.getText = function() {
		return label.innerHTML;
	}

	dom.append = function(node) {
		children.appendChild(node);
	}

	return dom;
};



newGraphicSimpleTree = function() {

	var dom = document.createElement('div');
	dom.setAttribute('class', 'comGraphic-simple-tree');

	dom.callback_delete = null;
	dom.callback_drop = null;
	
	dom.last_selected = null;

	dom.clear = function() {
		dom.innerHTML = '';
	}

	dom.append = function (node) {
		dom.appendChild(node);
	}

	dom.createNode = function() {
		var node = newGraphicSimpleTreeNode(dom);
		node.addEventListener('click', dom.callback_click_default, false);
		node.addEventListener('click', dom.callback_click, false);
		return node;
	};

	dom.setCallbackDelete = function(callback) {
		dom.callback_delete = callback;
	};

	dom.getSelected = function() {
		return dom.last_selected;
	}

	dom.setCallbackClick = function(cb) {
		dom.callback_click = cb;
	}

	dom.setCallbackDblClick = function(cb) {
		dom.callback_dblclick = cb;
	}
	
	dom.setCallbackDrop = function(cb) {
		dom.callback_drop = cb;
	};

	dom.callback_click = null;
	dom.callback_click_default = function(event) {
		if (dom.last_selected != null)
			dom.last_selected.info.className = 'comGraphic-simple-tree-node-info';
		dom.last_selected = this;
		dom.last_selected.info.className = 'comGraphic-simple-tree-node-info selected';
	}

	return dom;

};
