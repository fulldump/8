[[INCLUDE component=Terrain]]

/**
 * TerrainDebugger
 * Visual component to aid Terrain development.
 *
 * Typical example:
 *
 *	var div_terrain = document.getElementById('div_terrain');
 *	var div_debugger = document.getElementById('div_debugger');
 *
 *	var terrain_debugger = new TerrainDebugger(div_terrain, div_debugger);
 *	terrain_debugger.start();
 *
 *	var terrain = new Terrain(div_terrain);
 *	terrain.enableEditor();
 *
 * IMPORTANT: Instantiate TerrainDebugger BEFORE Terrain to avoid handler
 * events from being cancelled by Terrain.
*/

(function(context){

	'use strict';

	var TerrainDebugger = function(terrain, dom) {
		this.terrain = terrain;
		this.dom = dom;
		this.dom.setAttribute('component', 'TerrainDebugger');
		this.timer = null;
		this.timerbg = null;
		this.selection = null;
		this.anchor_watcher = null;
		this.wanky = false;
	};

	TerrainDebugger.prototype.inspect = function() {
		this.dom.innerHTML = '';
		this.selection = window.getSelection();
		this._inspectRecursive(this.terrain, this.dom);
	};

	TerrainDebugger.prototype._inspectRecursive = function(inspected, watcher) {
		var n = inspected.childNodes.length;
		for (var i=0; i<n; i++) {
			var node = inspected.childNodes[i];
			var watch_node = document.createElement('div');
			watch_node.classList.add('watch_node');

			if (this.selection.anchorNode === node) {
				watch_node.classList.add('anchor_node');
				this.anchor_watcher = watch_node;
			}

			if (this.selection.focusNode === node) {
				watch_node.classList.add('focus_node');
			}

			if (node.nodeType == 3) {
				watch_node.classList.add('text');
				var anchorNode = this.selection.anchorNode;
				var anchorOffset = this.selection.anchorOffset;

				if (anchorNode === node) {
					var c = document.createElement('div');
					c.classList.add('counter');
					c.innerHTML = anchorOffset;
					watcher.appendChild(c);
					var aon = anchorNode.nodeValue.substring(0, anchorOffset) + '<span class="caret"><span></span></span>' + anchorNode.nodeValue.substring(anchorOffset);
				} else {
					var aon = node.nodeValue;
				}

				// Text node
				watch_node.style.whiteSpace = 'pre';
				watch_node.innerHTML = aon;
			} else {
				// Calculate attributes:
				var attributes = node.attributes;
				var a = "";
				for (var j = 0; j<attributes.length; j++) {
					a += " " + attributes[j].name + '="' + attributes[j].value + '"';
				}
				a = '<span style="color: silver;">' + a + '</span>';

				// Regular node
				var pre = document.createElement('div');
				pre.innerHTML = '&lt;<b>'+node.nodeName+'</b>'+a+'>';
				watch_node.appendChild(pre);

				if (node.childNodes.length > 0) {
					var cont = document.createElement('div');
					cont.classList.add('cont');
					watch_node.appendChild(cont);

					var post = document.createElement('div');
					post.innerHTML = '&lt;/<b>'+node.nodeName+'</b>>';
					watch_node.appendChild(post);

					this._inspectRecursive(node ,cont);

				} else {
					pre.setAttribute('style', 'display: inline-block;');
					watch_node.setAttribute('style', 'display: inline-block;');
				}

			}

			watcher.appendChild(watch_node);
		}

	};

	TerrainDebugger.prototype.start = function() {
		var that = this;
		if (null === this.timer) {
			this.timer = window.setInterval(function(){
				if (that.wanky) {
					that.wanky=false;
					that.inspect();
					that.dom.scrollTop = that.anchor_watcher.offsetTop - 200;
				}
			}, 30);
		}

		if (null === this.timerbg) {
			this.timerbg = window.setInterval(function(){
				if (that.wanky) {
					that.wanky=false;
					that.inspect();
				}
			}, 2000);
		}


		var the_event = function(e) {
			//that.inspect();
			that.wanky=true;
		};

		this.terrain.addEventListener('keydown', the_event, true);
		this.terrain.addEventListener('keyup', the_event, true);
		this.terrain.addEventListener('mousedown', the_event, true);
		this.inspect();
	};

	TerrainDebugger.prototype.stop = function() {
		window.clearInterval(this.timer);
		this.timer = null;

		window.clearInterval(this.timerbg);
		this.timerbg = null;
	};

	context.TerrainDebugger = TerrainDebugger;

})(window);
