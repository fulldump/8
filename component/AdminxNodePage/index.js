// Component AdminxNodePage

[[INCLUDE component=Ajax]]
[[INCLUDE component=AdminxWorkspace]]
[[INCLUDE component=AdminxPreview]]
[[INCLUDE component=TrunkButton]]
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
	
	function AdminxNodePage(node) {
		this.node = node;

		this.dom = document.createElement('div');
		this.dom.setAttribute('component', 'AdminxNodePage');

		this.workspace = new AdminxWorkspace();
		this.dom.appendChild(this.workspace.dom);

		this.build();
	}

	AdminxNodePage.prototype.build = function() {
		this.buildPreview();
		this.buildPhp();
		this.buildCss();
		this.buildJavascript();
		this.buildProperties();

		this.buildButtons();

		this.workspace.setStatus('');
		this.workspace.select(0);
	};

	AdminxNodePage.prototype.buildPreview = function() {
		var that = this;

		this.preview = new AdminxPreview();
		this.workspace_preview = this.workspace.add('Preview', this.preview.dom);
		this.workspace_preview.tab.dom.addEventListener('click', function() {
			that.preview.iframe.src = '/u/' + that.node.id + '?edit';
		}, true);

		this.preview.iframe.src = '/u/' + this.node.id + '?edit';
	};

	AdminxNodePage.prototype.buildPhp = function() {
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

	AdminxNodePage.prototype.buildCss = function() {
		var that = this;

		// Php editor
		this.css = document.createElement('div');
		this.css.classList.add('editor-container');
		this.workspace_php = this.workspace.add('Css', this.css);
		var editor_loaded = false;
		var editor = CodeMirror(
			function(elt) {
				that.css.appendChild(elt);
			},
			{
				height: "100%",
				lineNumbers: true,
				matchBrackets: true,
				mode: 'css',
				indentUnit: 2,
				indentWithTabs: false,
				foldGutter: true,
				gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
			}
		);
		editor.on('blur', function(cm) {
			if (editor_loaded) {
				var ajax = new Ajax('[[AJAX name=save_css]]');
				ajax.query({
					id: that.node.id,
					value: cm.getValue(),
				});
			}
		});
		this.workspace_php.tab.dom.addEventListener('click', function() {
			if (!editor_loaded) {
				var ajax = new Ajax('[[AJAX name=load_css]]');
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

	AdminxNodePage.prototype.buildJavascript = function() {
		var that = this;

		// Php editor
		this.javascript = document.createElement('div');
		this.javascript.classList.add('editor-container');
		this.workspace_php = this.workspace.add('JavaScript', this.javascript);
		var editor_loaded = false;
		var editor = CodeMirror(
			function(elt) {
				that.javascript.appendChild(elt);
			},
			{
				height: "100%",
				lineNumbers: true,
				matchBrackets: true,
				mode: {name: 'javascript', json: true},
				indentUnit: 4,
				indentWithTabs: true,
				foldGutter: true,
				gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"],
			}
		);
		editor.on('blur', function(cm) {
			if (editor_loaded) {
				var ajax = new Ajax('[[AJAX name=save_javascript]]');
				ajax.query({
					id: that.node.id,
					value: cm.getValue(),
				});
			}
		});
		this.workspace_php.tab.dom.addEventListener('click', function() {
			if (!editor_loaded) {
				var ajax = new Ajax('[[AJAX name=load_javascript]]');
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
	
	AdminxNodePage.prototype.buildProperties = function() {
		var that = this;

		this.properties = document.createElement('div');
		this.properties.innerHTML = 'TODO THIS :) i am the properties UI';
		this.workspace_properties = this.workspace.add('Properties', this.properties);

		this.workspace_properties.tab.dom.style.float = 'right';
	};

	AdminxNodePage.prototype.buildButtons = function() {
		var that = this;

		var make_home = trunk.create('Button');
		make_home.dom.innerHTML = 'Make home';
		make_home.dom.addEventListener('click', function() {
			if (confirm('Selected page will be home. Continue?')) {
				var ajax = new Ajax('[[AJAX name=make_home]]');
				ajax.query({id:that.node.id});
			}
		}, true);
		this.workspace.buttons.appendChild(make_home.dom);


		var make_404 = trunk.create('Button');
		make_404.dom.innerHTML = 'Make 404';
		make_404.dom.addEventListener('click', function() {
			if (confirm('Selected page will be the 404. Continue?')) {
				var ajax = new Ajax('[[AJAX name=make_404]]');
				ajax.query({id:that.node.id});
			}
		}, true);
		this.workspace.buttons.appendChild(make_404.dom);
	};

	window.AdminxNodePage = AdminxNodePage;
	
})();