[[INCLUDE component=NinjaEditorToolbar]]

var NinjaEditor = function(parent) {

	this.editable = false;
	this.parent = parent;
	this.dom = null;
	this.toolbar = null; 
	this.pre = null;
	this.content = null;
	this.post = null;

	this._buildDom();
};

NinjaEditor.prototype._buildDom = function() {
	this.dom = document.createElement('div');
	this.dom.className = 'NinjaEditor';
	this.parent.appendChild(this.dom);

	this._buildToolbar();
	this._buildPre();
	this._buildContent();
	this._buildPost();
};

NinjaEditor.prototype._buildToolbar = function() {
	var that = this;

	// Add to tree
	var toolbar = this.toolbar = new NinjaEditorToolbar(this.dom);

	window.addEventListener('scroll', function(e) {
		toolbar.dom.setAttribute('floated',
			window.pageYOffset > that.dom.offsetTop ? 'true' : 'false'
		);
	}, true);

	// Add Groups
	var group = null
	, button;

	group = toolbar.add('format');
		button = group.add('bold');
			button.title = 'Negrita';
			button.addEventListener('click', function(e) {
				document.execCommand('bold', false, null);
				that.focus();
			}, true);
		button = group.add('italic');
			button.title = 'Cursiva';
			button.addEventListener('click', function(e) {
				document.execCommand('italic', false, null);
				that.focus();
			}, true);
		button = group.add('underline');
			button.title = 'Subrayado';
			button.addEventListener('click', function(e) {
				document.execCommand('underline', false, null);
				that.focus();
			}, true);
		button = group.add('strike');
			button.title = 'Tachado';
			button.addEventListener('click', function(e) {
				document.execCommand('strikeThrough', false, null);
				that.focus();
			}, true);
		button = group.add('clear-format');
			button.title = 'Limpiar formato';
			button.addEventListener('click', function(e) {
				document.execCommand('removeFormat', false, null);
				that.focus();
			}, true);
		button = group.add('sup');
			button.title = 'Superíndice';
			button.addEventListener('click', function(e) {
				document.execCommand('superscript', false, null);
				that.focus();
			}, true);
		button = group.add('sub');
			button.title = 'Subíndice';
			button.addEventListener('click', function(e) {
				document.execCommand('subscript', false, null);
				that.focus();
			}, true);

	/*group = toolbar.add('links');
		button = group.add('hyperlink');
			button.title = 'Enlace externo';
		button = group.add('reference');
			button.title = 'Referencia al pie';
	*/

	group = toolbar.add('lists');
		button = group.add('unordered');
			button.title = 'Lista';
			button.addEventListener('click', function(e) {
				document.execCommand('insertUnorderedList', false, null);
				that.focus();
			}, true);
		button = group.add('ordered');
			button.title = 'Lista ordenada';
			button.addEventListener('click', function(e) {
				document.execCommand('insertOrderedList', false, null);
				that.focus();
			}, true);

	/*
	group = toolbar.add('insert');
		button = group.add('image');
			button.title = 'Insertar imagen';
		button = group.add('file');
			button.title = 'Insertar archivo';
	*/

	
	/*group = toolbar.add('image-size');
		button = group.add('big');
			button.title = 'Grande';
		button = group.add('medium');
			button.title = 'Mediano';
		button = group.add('small');
			button.title = 'Pequeño';
	*/

	/*group = toolbar.add('image-align');
		button = group.add('left-float');
			button.title = 'Alineación flotante izquierda';
		button = group.add('left');
			button.title = 'Alineación izquierda';
		button = group.add('center');
			button.title = 'Alineación centrada';
		button = group.add('right');
			button.title = 'Alineación derecha';
		button = group.add('right-float');
			button.title = 'Alineación flotante derecha';
	*/





};

NinjaEditor.prototype._buildPre = function() {
	// Add to tree
	this.pre = document.createElement('div');
	this.pre.className = 'NinjaEditor-pre';
	this.dom.appendChild(this.pre);

	// Extra configuration
};

NinjaEditor.prototype._buildContent = function() {
	var that = this;	

	// Add to tree
	this.content = document.createElement('div');
	this.content.className = 'NinjaEditor-content';
	this.dom.appendChild(this.content);

	// Extra configuration
	this.content.addEventListener('keypress', function(e){
		if (window.pageYOffset == that.content.offsetTop) {
			window.scrollTo(0,0);
		}
	}, true);
};

NinjaEditor.prototype._buildPost = function() {
	// Add to tree
	this.post = document.createElement('div');
	this.post.className = 'NinjaEditor-post';
	this.dom.appendChild(this.post);

	// Extra configuration
};

NinjaEditor.prototype.setEditable = function(b) {
	this.editable = b;
	this.content.setAttribute('contentEditable', b);
};

NinjaEditor.prototype.getEditable = function() {
	return this.editable;
};

NinjaEditor.prototype.focus = function() {
	this.content.focus();
	/*
	var that = this;
	setTimeout(function() {
		that..focus();
	}, 500);*/

};




[[INCLUDE component=NinjaEditorIcons]]