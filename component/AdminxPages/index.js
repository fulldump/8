// Component AdminxPages

[[INCLUDE component=TrunkDouble]]
[[INCLUDE component=GraphicSimpleTree]]
[[INCLUDE component=Ajax]]


(function(){
	'use strict';

	
	function AdminxPages() {
		
		this.dom = document.createElement('div');
		this.dom.setAttribute('component', 'AdminxPages');
		
		
		this.buildSkeleton();
			this.buildTree();
		
		
		this.loadTree();
	}
	
	AdminxPages.prototype.buildSkeleton = function() {
		this.panels = trunk.create('Double');
		this.dom.appendChild(this.panels.dom);

		this.current = document.createElement('div');
		this.current.innerHTML = 'soy el current';
		this.current.classList.add('AdminxPages-current');
		this.panels.current_info.appendChild(this.current);

	};
	
	AdminxPages.prototype.buildTree = function() {
		var that = this;
		
		var tree = this.tree = newGraphicSimpleTree();
		this.panels.left.appendChild(this.tree);
		
		tree.setCallbackClick(function(event){
			event.stopPropagation();
			
			var current_node = this;
						
			that.current.innerHTML = this.getText();
			that.panels.detailed(true);
			
			var key = prompt('');
			
			if (null === key) {
				return;
			}
			
			var ajax = new Ajax('[[AJAX name=append_node]]');
			ajax.setCallback200(function(text){
				if ('' == text) {
					alert('Node could not be created');
				} else {
					var json = JSON.parse(text);
					that._loadTreeRec(key, current_node, json);
				}
			});
			ajax.query({
				'id': this.id,
				'key': key,
				'type': 'page',
			});
			
		});

		tree.setCallbackDelete(function(event){
			event.stopPropagation();
			if (confirm('All subtree will be removed. Are you sure?')) {
				var node = this.parentNode.parentNode;
				var id = node.id;
				var ajax = new Ajax('[[AJAX name=delete_node]]');
				ajax.setCallback200(function(text){
					node.style.display = 'none';
				});
				ajax.query({'id':id});
			}
		});

	};
	
	AdminxPages.prototype.loadTree = function() {
		var that = this;
				
		var ajax = new Ajax('[[AJAX name=load_tree]]');
		ajax.setCallback200(function(text) {
			var json = JSON.parse(text);
			that.tree.clear();
			that._loadTreeRec('ROOT', that.tree, json);
		});
		ajax.query({});
	};
	
	
	AdminxPages.prototype._loadTreeRec = function(key, node, json) {
		var new_node = this.tree.createNode();
		new_node.id = json.id;
		new_node.setText(key);
		new_node.setAttribute('type', json.properties['type']);
		node.append(new_node);

		var children = json.children;

		for (var k in children) {
			this._loadTreeRec(k, new_node, children[k]);
		}
	};
	
	window.AdminxPages = AdminxPages;
	
})();