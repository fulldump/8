// Component AdminxWorkspace

[[INCLUDE component=TrunkTab]]

(function(){
	'use strict';
	
	function AdminxWorkspace() {
		this.panels = [];

		this.dom = document.createElement('div');
		this.dom.setAttribute('component', 'AdminxWorkspace');
		this.dom.setAttribute('status', 'hidden');

		this.buildMain();
			this.buildTabs();
			this.buildContent();
			this.buildButtons();
		
		this.buildLoading();
		
	}
	
	AdminxWorkspace.prototype.buildMain = function() {
		this.main = document.createElement('div');
		this.main.className = 'main';
		this.dom.appendChild(this.main);
	};

	AdminxWorkspace.prototype.buildTabs = function() {
		this.tabs = trunk.create('Tab');

		this.main.appendChild(this.tabs.dom);
	};
	
	AdminxWorkspace.prototype.buildContent = function() {
		this.content = document.createElement('div');
		this.content.className = 'content';
		
		this.main.appendChild(this.content);
	};
	
	AdminxWorkspace.prototype.buildButtons = function() {
		this.buttons = document.createElement('div');
		this.buttons.className = 'buttons';
		
		this.main.appendChild(this.buttons);
	};
	
	AdminxWorkspace.prototype.buildLoading = function() {
		this.loading = document.createElement('div');
		this.loading.className = 'loading';
		this.loading.innerHTML = '<span>loading...</span>';
		this.dom.appendChild(this.loading);
	};
	
	AdminxWorkspace.prototype.load = function(node_id, callback) {
		null === callback || callback(null, {
			id: node_id,
		});
	};

	AdminxWorkspace.prototype.hide_panels = function() {
		for (var i in this.panels) {
			this.panels[i].classList.add('none');
		}
	};
	
	AdminxWorkspace.prototype.add = function(label, content) {
		var that = this;
		var panel = document.createElement('div');
		this.panels.push(panel);
		panel.classList.add('panel');
		panel.classList.add('none');
		panel.appendChild(content);
		this.content.appendChild(panel);

		var tab = this.tabs.add(label);
		tab.dom.addEventListener('click', function(){
			that.hide_panels();
			panel.classList.remove('none');
		}, true);

		return {
			panel: panel,
			content: content,
			tab: tab,
		};
	}

	AdminxWorkspace.prototype.select = function(i) {
		this.tabs.select(i);
		this.hide_panels();
		this.panels[i].classList.remove('none');
	};

	AdminxWorkspace.prototype.setStatus = function(status) {
		this.dom.setAttribute('status', status);
	};

	window.AdminxWorkspace = AdminxWorkspace;
	
})();