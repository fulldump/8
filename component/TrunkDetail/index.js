// Component TrunkDetail

[[INCLUDE component:Trunk]]

(function(){
	'use strict';

	function TrunkDetail() {
		this.dom = document.createElement('div');
		this.dom.classList.add('TrunkMargin');

		var top = this.top = document.createElement('div');
		top.classList.add('top');
		this.dom.appendChild(top);

		var center = this.center = document.createElement('div');
		center.classList.add('center');
		this.dom.appendChild(center);

		var bottom = this.bottom = document.createElement('div');
		bottom.classList.add('bottom');
		this.dom.appendChild(bottom);
	}

	trunk.register(TrunkDetail);

})();