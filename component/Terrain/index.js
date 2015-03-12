/**
 * Terrain
 * Text editor for HTML5 browsers.
 *
 * Typical example:
 *
 *	var terrain = new Terrain(document.getElementById('my-document'));
 *	terrain.enableEditor();
 *
*/

(function(context){

	'use strict';

	var debug_enabled = true;

	function debug(item) {
		if (debug_enabled) {
			console.log(item);
		}
	};

	var Terrain = function(dom) {
		this.dom = dom;
	};

	Terrain.prototype.enableEditor = function() {
		this.dom.setAttribute('component', 'Terrain');
		this.dom.setAttribute('contentEditable', true);
		this.dom.focus();
		this.checkEmpty();

		var that = this;

		this.dom.addEventListener('keydown', function(event){
			that.checkEmpty();

			var path = that.getDomPath();

			if (13 == event.keyCode) { // Enter key
				debug('enter');
				var node = path[0];

				if (!event.shiftKey && 
					node.nodeName == 'CODE' &&
					node.getAttribute('component') == 'Code' &&
					that.isCursorAtBegin(node)
				) {
					event.stopPropagation();
					event.preventDefault();
					var p = document.createElement('p');
					p.innerHTML = '<br>';
					that.dom.insertBefore(p, node);
					that.putCursorAt(p);
				}
			} else if (9 == event.keyCode) { // Tab key
				event.preventDefault();
				event.stopPropagation();

				var path_0 = path[0];
				if ('CODE' == path_0.nodeName && 'Code' == path_0.getAttribute('component')) {

				}
			}
		}, true);

		this.dom.addEventListener('paste', function(event){
			event.preventDefault();

			if (true || event.clipboardData.types.indexOf('text/plain') > -1) {
				document.execCommand('delete', false, null);

				var text = event.clipboardData.getData('text/plain');
				var selection = window.getSelection();
				var anchor = selection.anchorNode;
				var offset = selection.anchorOffset;
				var length = anchor.nodeValue.length;
				
				var pre = document.createTextNode(anchor.nodeValue.substring(0, offset));
				anchor.parentNode.insertBefore(pre, anchor);

				anchor.nodeValue = anchor.nodeValue.substring(offset);

				var lines = text.split('\n');
				for (var i in lines) {
					var t = document.createTextNode(lines[i].replace(/ /mg, "\u00a0"));
					anchor.parentNode.insertBefore(t, anchor);

					if (i < lines.length-1) {
						var br = document.createElement('br');
						anchor.parentNode.insertBefore(br, anchor);
					}
				}
			}

		}, true);


	};

	Terrain.prototype.isCursorAtBegin = function(node) {
		var selection = window.getSelection();
		if (selection.anchorOffset == 0) {
			return true;
		}
		return false;
	};

	Terrain.prototype.formatBold = function() {
		document.execCommand('bold', false, null);
	};

	Terrain.prototype.formatItalic = function() {
		document.execCommand('italic', false, null);
	};

	Terrain.prototype.formatUnderline = function() {
		document.execCommand('underline', false, null);
	};

	Terrain.prototype.formatStrike = function() {
		document.execCommand('strikeThrough', false, null);
	};

	Terrain.prototype.putCursorAt = function(node, offset) {
		debug('putCursorAt');
		var range = document.createRange();
		range.setStart(node, offset);

		var selection = window.getSelection();
		selection.removeAllRanges();
		selection.addRange(range);		
	};
	
	Terrain.prototype.removeRootBr = function() {
		debug('removeRootBr');
		var n = this.dom.childNodes.length;
		for (var i=n-1; i>=0; i--) {
			var node = this.dom.childNodes[i];
			if (node.nodeType == 1 && node.tagName == 'BR') {
				this.dom.removeChild(this.dom.childNodes[i]);
			}
		}
	};

	Terrain.prototype.getDomPath = function(element) {
		if (!element) {
			element = document.getSelection().anchorNode;
		}
		
		var parts = [];
		
		while (element != this.dom) {
			parts.unshift(element);
			element = element.parentNode;
		}
		return parts;
	};

	Terrain.prototype.insertCode = function() {
		var code = document.createElement('code');
		code.setAttribute('component', 'Code');
		code.innerHTML = '<br>';
		
		var reference = this.getDomPath()[0];
		this.dom.insertBefore(code, reference.nextSibling);
		this.putCursorAt(code);
	};

	Terrain.prototype.checkEmpty = function() {
		debug('checkEmpty');
		this.removeRootBr();
		if (this.dom.childNodes.length == 0) {
			var p = document.createElement('p');
			p.innerHTML = '<br>';
			this.dom.appendChild(p);

			this.putCursorAt(p);
		}
	};
	
	Terrain.prototype.cleanAttributes = function(node, excluded) {
		debug('cleanAttributes');

		var attributes = node.attributes;
		for (var i = attributes.length-1; i>=0; i--) {
			var attributeName = attributes[i].name;
			if (!excluded || -1 == excluded.indexOf(attributeName) ) {
				node.removeAttribute(attributeName);
			}
		}
	};

	Terrain.prototype.trimText = function(node) {
		debug('cleanText');
		if (node.parentNode.childNodes[0] == node) {
			node.nodeValue = node.nodeValue.replace(/^[ \t\n\r]+/gm, '');
		}
	};

	Terrain.prototype.cleanCode = function(node) {
		if (node.getAttribute('component') == 'Code') {
			var path = this.getDomPath(node);
			this.dom.insertBefore(node, path[0]);
		} else {
			this.cleanAttributes(node);
			this.cleanText(node);
		}
	};

	Terrain.prototype.cleanText = function(parent) {
		debug('cleanText');
		var n = parent.childNodes.length;
		for (var i=n-1; i>=0; i--) {
			var node = parent.childNodes[i];
			if (node.nodeType == 1) {
				var nodeName = node.nodeName;
				switch (nodeName) {
					case 'B':
					case 'STRONG':
						this.cleanAttributes(node);
						debug('clean B or STRONG');
						break;
					case 'I':
					case 'EM':
						this.cleanAttributes(node);
						debug('clean I or EM');
						break;
					case 'U':
						this.cleanAttributes(node);
						debug('clean U');
						break;
					case 'S':
						this.cleanAttributes(node);
						debug('clean S');
						break;
					case 'SUP':
						this.cleanAttributes(node);
						debug('clean SUP');
						break;
					case 'SUB':
						this.cleanAttributes(node);
						debug('clean SUB');
						break;
					case 'BR':
						this.cleanAttributes(node);
						debug('clean BR');
						break;
					case 'CODE':
						this.cleanCode(node);
						break;
					default:
						debug('remove '+nodeName)
						parent.removeChild(node);
				}
			} else if (node.nodeType == 3) {
				this.trimText(node);
			}
		}
	};

	Terrain.prototype.clean = function() {
		debug('clean');
		this.cleanRoot(this.dom);
	};

	Terrain.prototype.replaceTag = function(new_node, old_node) {
		var parentNode = old_node.parentNode;
		parentNode.insertBefore(new_node, old_node);

		var n = old_node.childNodes.length;
		for (var i=0; i<n; i++) {
			new_node.appendChild(old_node.childNodes[i]);
		}

		parentNode.removeChild(old_node);
	};


	Terrain.prototype.cleanRoot = function(root) {
		debug('cleanRoot');
		var n = root.childNodes.length;
		for (var i=n-1; i>=0; i--) {
			var node = root.childNodes[i];
			if (node.nodeType == 1) { // TAG
				var nodeName = node.nodeName;
				switch (nodeName) {
					case 'P':
						debug('clean P');
						this.cleanAttributes(node);
						this.cleanText(node);
						break;
					case 'CODE':
						debug('clean CODE');
						this.cleanAttributes(node, ['component']);
						this.cleanText(node);
						break;
					case 'BLOCKQUOTE':
						debug('clean BLOCKQUOTE');
						this.cleanAttributes(node);
						break;
					case 'FIGURE':
						debug('clean FIGURE');
						this.cleanAttributes(node);
						break;
					case 'UL':
						debug('clean UL');
						this.cleanAttributes(node);
						break;
					case 'OL':
						debug('clean OL');
						this.cleanAttributes(node);
						break;
					case 'H1':
						debug('clean H1');
						this.cleanAttributes(node);
						this.cleanText(node);
						var h2 = document.createElement('h2');
						this.replaceTag(h2, node);
						break;
					case 'H2':
					case 'H3':
					case 'H4':
					case 'H5':
					case 'H6':
						debug('clean H2');
						this.cleanAttributes(node);
						this.cleanText(node);
						break;
					case 'TABLE':
						debug('clean TABLE');
						this.cleanAttributes(node);
						break;
					case 'DIV':
						debug('clean DIV');
						var p = document.createElement('p');
						this.replaceTag(p, node);
						// for (var j=0; j<node.length; j++) {
						// 	alert(j);
						// 	root.insertBefore(node.childNodes[j], node)
						// }
						// root.removeChild(node);
						break;
					default:
						debug('remove '+nodeName)
						root.removeChild(node);
				}
			// } else if (node.nodeType == 3) { // TEXT
			// 	//alert('soy  un text');
			// 	node.nodeValue = node.nodeValue.trim().replace(/^[\r\n\t]*/gm, '');
			}
		}
	};

	context.Terrain = Terrain;

})(window);

