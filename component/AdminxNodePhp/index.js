// Component AdminxNodePhp

[[INCLUDE component=AdminxWorkspace]]
[[INCLUDE component=AdminxPreview]]
[[INCLUDE component=CodeMirror]]
[[INCLUDE component=CodeMirrorMatchbrackets]]
[[INCLUDE component=CodeMirrorHTMLMixed]]
[[INCLUDE component=CodeMirrorXML]]
[[INCLUDE component=CodeMirrorJavascript]]
[[INCLUDE component=CodeMirrorCSS]]
[[INCLUDE component=CodeMirrorClike]]
[[INCLUDE component=CodeMirrorPHP]]
[[INCLUDE component=CodeMirrorFold]]
[[INCLUDE component=CodeMirrorFullscreen]]

(function(){
	'use strict';
	
	function AdminxNodePhp(node) {
		
		this.node = node;
		this.dom = document.createElement('div');
		this.dom.setAttribute('component', 'AdminxNodePhp');

		this.workspace = new AdminxWorkspace();
		this.dom.appendChild(this.workspace.dom);

		this.build();
	}

	AdminxNodePhp.prototype.build = function() {
		this.buildPreview();
		this.buildPhp();
		this.buildProperties();

		this.workspace.setStatus('');
		this.workspace.select(0);
	};

	AdminxNodePhp.prototype.buildPreview = function() {
		var that = this;

		this.preview = new AdminxPreview();
		this.workspace_preview = this.workspace.add('Preview', this.preview.dom);
		this.workspace_preview.tab.dom.addEventListener('click', function() {
			that.preview.iframe.src = '/u/' + that.node.id + '?edit';
		}, true);

		this.preview.iframe.src = '/u/' + this.node.id + '?edit';
	};

	AdminxNodePhp.prototype.buildPhp = function() {
		var that = this;

		// Php editor
		this.php = document.createElement('div');
		this.php.classList.add('editor-container');
		this.workspace_php = this.workspace.add('Php', this.php);
		var editor_loaded = false;
		var editor = CodeMirror(
			function(elt) {
				that.php.appendChild(elt);
			},
			{
				height: "100%",
				lineNumbers: true,
				matchBrackets: true,
				mode: 'application/x-httpd-php',
				indentUnit: 4,
				indentWithTabs: true,
				foldGutter: true,
				gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
			}
		);
		editor.on('blur', function(cm) {
			if (editor_loaded) {
				var ajax = new Ajax('[[AJAX name=save_php]]');
				ajax.query({
					id: that.node.id,
					value: cm.getValue(),
				});
			}
		});
		this.workspace_php.tab.dom.addEventListener('click', function() {
			if (!editor_loaded) {
				var ajax = new Ajax('[[AJAX name=load_php]]');
				ajax.setCallback200(function(text){
					editor.setValue(text);
					editor_loaded = true;
					editor.refresh();
					editor.focus();
				});
				ajax.query({id: that.node.id});
			}
			editor.focus();
		}, true);

	};
	
	AdminxNodePhp.prototype.buildProperties = function() {
		var that = this;

		this.properties = document.createElement('div');
		this.properties.innerHTML = 'TODO THIS :) i am the properties UI';
		this.workspace_properties = this.workspace.add('Properties', this.properties);

		this.workspace_properties.tab.dom.style.float = 'right';
	};

	window.AdminxNodePhp = AdminxNodePhp;
	
})();