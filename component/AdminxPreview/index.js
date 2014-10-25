// Component AdminxPreview

(function(){
	'use strict';
	
	function AdminxPreview() {
		this.dom = document.createElement('div');
		this.dom.setAttribute('component', 'AdminxPreview');

		this.buildMain();		
	}
	
	AdminxPreview.prototype.buildMain = function() {
		this.iframe = document.createElement('iframe');
		this.dom.appendChild(this.iframe);
	};

	window.AdminxPreview = AdminxPreview;
	
})();