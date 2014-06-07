[[INCLUDE component=TrunkDouble]]
[[INCLUDE component=Ajax]]

(function(){
	'use strict';

	var last_selected = null;
	var timer = null;

	function expand_click(event) {
		var node = this.tree.dom;
		if ('true' == node.getAttribute('expanded')) {
			node.setAttribute('expanded', 'false');
		} else {
			node.setAttribute('expanded', 'true');
		}
	}

	function check_click(event) {
		this.tree.setCheck(this.checked);
		last_selected = this.tree;
	}

	function test_click(event) {
		if (null != timer) {
			clearInterval(timer);
			timer = null;
		}
		if (event.ctrlKey || event.ctrlKey) {
			var selected = this.tree;
			timer = window.setInterval(function(){
				selected.run();
			}, 1000);
		}
		last_selected = this.tree;
		// this.tree.setCheck(true);
		Tree.callback_result(this.tree._output);
	};

	function Tree() {
		this._name = '';
		this._type = '';
		this._children = {};
		this._parent = null;
		this._pass = null;
		this._output = '';
		this._stats = {pass:0, fail:0};
		
		this.buildDom();
			this.buildInfo();
			this.buildSubtree();
	}

	Tree.callback_result = function() {};

	Tree.prototype.updateStats = function() {
		if ('test' == this.getType()) {
			this._stats.pass = 0;
			this._stats.fail = 0;
			if (true == this._pass) {
				this._stats.pass = 1;
			} else if (false == this._pass) {
				this._stats.fail = 1;
			}
		} else {
			this._stats.pass = 0;
			this._stats.fail = 0;
			for (var i in this._children) {
				var result = this._children[i]._stats;
				this._stats.pass += result.pass;
				this._stats.fail += result.fail;
			}
			this.pass.innerHTML = this._stats.pass;
			this.fail.innerHTML = this._stats.fail;
			this.total.innerHTML = this._stats.pass+this._stats.fail;
			
		}

		this.info.setAttribute('pass', this._stats.fail == 0);

		if (null !== this._parent) {
			this._parent.updateStats();
		}
	};

	Tree.prototype.getRoot = function() {
		var result;
		var node = this;
		while (null != node) {
			result = node;
			node = node._parent;
		}
		return result;
	};

	Tree.prototype.buildDom = function() {
		this.dom = document.createElement('div');
		this.dom.classList.add('tree');
		this.dom.setAttribute('expanded', 'true');
		this.dom.setAttribute('count', 0);
	};

	Tree.prototype.buildInfo = function() {
		this.info = document.createElement('div');
		this.info.classList.add('info');
		this.info.tree = this;
		this.dom.appendChild(this.info);

		this.buildStats();

		this.expand = document.createElement('div');
		this.expand.classList.add('expand');
		this.expand.tree = this;
		this.expand.addEventListener('click', expand_click, true);
		this.info.appendChild(this.expand);

		this.check = document.createElement('input');
		this.check.classList.add('check');
		this.check.tree = this;
		this.check.setAttribute('type', 'checkbox');
		this.check.addEventListener('click', check_click, true);
		this.info.appendChild(this.check);

		this.name = document.createElement('div');
		this.name.classList.add('name');
		this.info.appendChild(this.name);
	};

	Tree.prototype.buildStats = function() {
		this.stats = document.createElement('div');
		this.stats.classList.add('stats');
		this.info.appendChild(this.stats);

		this.pass = document.createElement('span');
		this.pass.classList.add('stat');
		this.pass.classList.add('pass');
		this.pass.innerHTML = '0';
		this.stats.appendChild(this.pass);

		this.fail = document.createElement('span');
		this.fail.classList.add('stat');
		this.fail.classList.add('fail');
		this.fail.innerHTML = '0';
		this.stats.appendChild(this.fail);

		this.total = document.createElement('span');
		this.total.classList.add('stat');
		this.total.classList.add('total');
		this.total.innerHTML = '0';
		this.stats.appendChild(this.total);
	};

	Tree.prototype.buildSubtree = function() {
		this.subtree = document.createElement('div');
		this.subtree.classList.add('subtree');
		this.dom.appendChild(this.subtree);
	};

	Tree.prototype.clear = function() {
		// TODO:

		this.dom.setAttribute('count', 0);
	};

	Tree.prototype.addChild = function(tree) {
		tree._parent = this;
		this._children[tree.getName()] = tree;
		this.subtree.appendChild(tree.dom);

		this.dom.setAttribute('count', Object.keys(this._children).length);

		if ('test' == tree._type) {
			tree.info.addEventListener('click', test_click, true);
		}
	};

	Tree.prototype.setJson = function(json){
		this.setName(json.name);
		this.setType(json.type);

		for (var i in json.children) {
			var tree = new Tree();
			tree.setJson(json.children[i]);
			this.addChild(tree);
		}

	};

	Tree.prototype.setCheck = function(value) {
		this.check.checked = value;
		for (var k in this._children) {
			this._children[k].setCheck(value);
		}

		if (value && 'test' == this._type) {
			this.run();
		}
	};

	Tree.prototype.getPath = function(){
		var path = '';
		var node = this;
		while (null != node) {
			path = '/' + node.getName() + path;
			node = node._parent;
		}
		return path.substring(1);
	};

	Tree.prototype.run = function() {
		if ('test' == this._type) {
			this._pass = false;
			var that = this;
			var ajax = new Ajax('[[AJAX name=run]]');
			ajax.setCallback200(function(text) {
				try {
					var json = JSON.parse(text);
					that._pass = json.pass;
					that._output = json.output;
				} catch (e) {
					that._pass = false;
					that._output = text;
				}

				that.updateStats();
				if (that == last_selected) {
					Tree.callback_result(that._output);
				}
			});
			ajax.query({
				file: this._parent.getPath(),
				test: this.getName(),
			});
		}
	};

	Tree.prototype.setName = function(value) {
		this._name = value;
		this.name.innerHTML = value;
	};

	Tree.prototype.getName = function() {
		return this._name;
	};

	Tree.prototype.setType = function(value) {
		this._type = value;
		this.info.setAttribute('type', value);
	};

	Tree.prototype.getType = function() {
		return this._type;
	};

	////////////////////////////////////////////////////////////

	function AdminxTest() {
		this.dom = document.createElement('div');
		this.dom.setAttribute('component', 'AdminxTest');

		this.tree = null;

		this.buildSkeleton();

		var that = this;
		Tree.callback_result = function(result) {
			that.result.innerHTML = result;
		};

		this.load();
	};

	AdminxTest.prototype.buildSkeleton = function() {
		this.panels = trunk.create('Double');
		this.dom.appendChild(this.panels.dom);

		this.current = document.createElement('div');
		this.current.innerHTML = 'COMPLETAR ESTO';
		this.current.classList.add('AdminxTest-current');
		this.panels.current_info.appendChild(this.current);

		this.result = document.createElement('pre');
		this.panels.right.appendChild(this.result);
	};

	AdminxTest.prototype.load = function() {
		if (null != this.tree) {
			// TODO: remove tree
		}
		var that = this;
		var ajax = new Ajax('[[AJAX name=load_list]]');
		ajax.setCallback200(function(text) {
			var json = JSON.parse(text);
			var tree = new Tree();
			tree.setJson(json);
			that.panels.left.appendChild(tree.dom);
		});
		ajax.query();
	};

	window.AdminxTest = AdminxTest;

})();
