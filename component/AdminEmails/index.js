[[INCLUDE component=GraphicList]]
[[INCLUDE component=Ajax]]
[[INCLUDE component=GraphicTab]]
[[INCLUDE component=NinjaEditor]]

var AdminEmails = function(parent) {

	this.parent = parent;
	this.editing_email_id = 0;

	this.ajax_reload_emails = null;
	this.ajax_new_email = null;
	this.ajax_delete_email = null;
	this.ajax_load_email = null;
	this.ajax_save_email = null;

	this._build_panels();
};

AdminEmails.prototype._build_panels = function() {

	this.dom = document.createElement('div');
	this.dom.className = 'AdminEmails';	
	this.parent.appendChild(this.dom);

	this._build_left();
	this._build_right();
};

AdminEmails.prototype._build_left = function() {
	var that = this;

	this.left = document.createElement('div');
	this.left.className = 'AdminEmails-left';

	this.list_emails = newGraphicList();
	this.left.appendChild(this.list_emails);

	// Callbacks							// Callbacks:

	this.list_emails.setCallbackClick(function(event){		// CLICK
		that.loadEmail(this.id);
	});

	this.list_emails.setCallbackNew(function(event, name){		// NEW
		that.newEmail(name);
	});

	this.list_emails.setCallbackSearch(function(event, name){	// SEARCH
		that.reloadEmails(name);
	});

	this.list_emails.setCallbackDelete(function(event){		// DELETE
		if (confirm('¿Estás seguro de que lo quieres borrar?')) {
			if (this.id == that.editing_email_id) {
				that.right.style.display = '';
				that.editing_email_id = 0;
			}
			that.deleteEmail(this.id);
			that.list_emails.remove(this.id);
		}
	});

	this.dom.appendChild(this.left);

	this.reloadEmails('');
};

AdminEmails.prototype._build_right = function() {
	this.right = document.createElement('div');
	this.right.className = 'AdminEmails-right';

	this._build_tabs();
	this._build_main();
	this._build_menu();

	this.dom.appendChild(this.right);	
};

AdminEmails.prototype._build_tabs = function() {
	this.tabs = newGraphicTab();
	this.right.appendChild(this.tabs);
};

AdminEmails.prototype._build_main = function() {
	this.main = document.createElement('div');
	this.main.className = 'AdminEmails-main';

	this._build_preview();
	this._build_html();
	this._build_test();
	this._build_parameters();
	this._build_history();


	this.right.appendChild(this.main);
};

AdminEmails.prototype._build_preview = function() {
	var that = this;

	this.preview = document.createElement('div');
	this.preview.className = 'AdminEmails-preview';

	var preview_editor = this.preview_editor = new NinjaEditor(this.preview);

	this.tabs.addButton('Preview', function(e){
		that.preview.style.display = 'block';
		that.html.style.display = '';
		that.test.style.display = '';
		that.parameters.style.display = '';
	});

	this.main.appendChild(this.preview);
};

AdminEmails.prototype._build_html = function() {
	var that = this;

	this.html = document.createElement('div');
	this.html.className = 'AdminEmails-html';	

	this.tabs.addButton('HTML', function(e){
		that.preview.style.display = '';
		that.html.style.display = 'block';
		that.test.style.display = '';
		that.parameters.style.display = '';
	});
	this.html.innerHTML = '{para la fase 2}';

	this.main.appendChild(this.html);
};

AdminEmails.prototype._build_test = function() {
	var that = this;

	this.test = document.createElement('div');
	this.test.className = 'AdminEmails-test';

	this.tabs.addButton('Test', function(e){
		that.preview.style.display = '';
		that.html.style.display = '';
		that.test.style.display = 'block';
		that.parameters.style.display = '';
	});
	this.test.innerHTML = '{Esto para la fase 2}';

	this.main.appendChild(this.test);
};

AdminEmails.prototype._build_parameters = function() {
	var that = this;

	this.parameters = document.createElement('div');
	this.parameters.className = 'AdminEmails-test';

	this.tabs.addButton('Parameters', function(e){
		that.preview.style.display = '';
		that.html.style.display = '';
		that.test.style.display = '';
		that.parameters.style.display = 'block';
	});
	this.parameters.innerHTML = '{Esto para la fase 2}';

	this.main.appendChild(this.parameters);
};

AdminEmails.prototype._build_history = function() {
	this.history = newGraphicList();
	this.history.className += ' AdminEmails-history';

	for (var i = 0; i<23; i++) {
		this.history.add(i, 'Demo ' + i + ' {para la fase 2}');
	}

	this.main.appendChild(this.history);
};

AdminEmails.prototype._build_menu = function() {
	var that = this;

	this.menu = document.createElement('div');

	this.menu.className = 'AdminEmails-menu';

	var button = document.createElement('button');
	button.className = 'shadow-button shadow-button-blue';
	button.innerHTML = 'Guardar borrador';
	button.addEventListener('click', function(e){
		that.saveEmail(that.editing_email_id);
	}, true);
	this.menu.appendChild(button);

	this.right.appendChild(this.menu);	
};



AdminEmails.prototype.reloadEmails = function(search) {
	var that = this;

	if (null != this.ajax_reload_emails) {
		this.ajax_reload_emails.abort();
	}

	this.ajax_reload_emails = new Ajax('[[AJAX name=reload_emails]]');		

	this.ajax_reload_emails.setCallback200(function(text){
		that.list_emails.clear();
		var json = eval( '(' + text + ')' );
		for(var k in json) {
			that.list_emails.add(json[k].id, json[k].name);
		}
		that.list_emails.select(that.editing_email_id);
	});
	this.ajax_reload_emails.query({search:search});
};

AdminEmails.prototype.newEmail = function(name) {
	if (name == '') return;

	var that = this;

	if (null != this.ajax_new_email) {
		this.ajax_new_email.abort();
	}

	this.ajax_new_email = new Ajax('[[AJAX name=new_email]]');
	this.ajax_new_email.setCallback200(function(text){
		var json = eval( '(' + text + ')' );
		that.list_emails.add(json.id, json.name);
	});
	this.ajax_new_email.query({name:name});
};

AdminEmails.prototype.deleteEmail = function(id) {
	var that = this;

	if (null != this.ajax_delete_email) {
		this.ajax_delete_email.abort();
	}

	this.ajax_delete_email = new Ajax('[[AJAX name=delete_email]]');
	this.ajax_delete_email.query({id:id});
};

AdminEmails.prototype.loadEmail = function(id) {
	var that = this;

	if (null != this.ajax_load_email) {
		this.ajax_load_email.abort();
	}

	this.ajax_load_email = new Ajax('[[AJAX name=load_email]]');
	this.ajax_load_email.setCallback200(function(text) {
		if (null == that.tabs.selected_id) {
			that.tabs.select(0);
			that.preview.style.display = 'block';
		}

		var json = eval('('+text+')');

		// LOAD CONTENT:
		that.preview_editor.content.innerHTML = json.html;
		that.preview_editor.setEditable(true);

		that.right.style.display = 'block';
		that.preview_editor.focus();
		that.editing_email_id = id;
	});
	this.ajax_load_email.query({id:id});

	this.right.style.display = '';

};

AdminEmails.prototype.saveEmail = function(id) {
	var that = this;

	if (null != this.ajax_save_email) {
		this.ajax_save_email.abort();
	}

	this.ajax_save_email = new Ajax('[[AJAX name=save_email]]');
	this.ajax_save_email.setCallback200(function(text) {
		that.preview_editor.setEditable(true);
	});
	this.ajax_save_email.query({id:id,html:that.preview_editor.content.innerHTML});

	that.preview_editor.setEditable(false);
};
