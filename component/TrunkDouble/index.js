[[INCLUDE component:TrunkButton]]

// Component TrunkDouble
(function(){
	'use strict';

	function TrunkDouble() {
		this.dom = document.createElement('div');

		this.buildLeft();
		this.buildSplitter();
		this.buildCurrent();
		this.buildRight();
	};

	TrunkDouble.prototype.detailed = function(enabled){
		if (enabled) {
			this.dom.classList.add('detailed');
		} else {
			this.dom.classList.remove('detailed');
		}
	}

	TrunkDouble.prototype.buildCurrent = function(){
		var that = this;

		this.current = document.createElement('div');
		this.current.classList.add('current-panel');
		this.dom.appendChild(this.current);

		this.current_back = trunk.create('Button');
		this.current_back.dom.innerHTML = '';
		this.current_back.dom.classList.add('current-back');
		this.current_back.dom.addEventListener('click', function(e) {
			that.detailed(false);
		}, true);
		this.current.appendChild(this.current_back.dom);

		this.current_info = document.createElement('div');
		this.current_info.classList.add('current-info');
		this.current.appendChild(this.current_info);
	}

	TrunkDouble.prototype.buildLeft = function(){
		this.left = document.createElement('div');
		this.left.classList.add('left-panel');
		this.dom.appendChild(this.left);
	};

	TrunkDouble.prototype.buildSplitter = function(){
		this.splitter = document.createElement('div');
		this.splitter.classList.add('splitter-panel');
		this.dom.appendChild(this.splitter);

		var that = this;
		this.splitter.addEventListener('click', function(e){
			that.dom.classList.toggle('splitted');
		}, true);
	};

	TrunkDouble.prototype.buildRight = function(){
		this.right = document.createElement('div');
		this.right.classList.add('right-panel');
		this.dom.appendChild(this.right);
	};

	trunk.register(TrunkDouble);
	
})();