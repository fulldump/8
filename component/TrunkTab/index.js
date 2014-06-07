// Component TrunkTab

/**
 * Typical use:
 * 	tt = trunk.create('Tab');
 *  tt.add('One').dom.addEventListener('click', function(e){ alert('I am the one'); }, true);
 *  tt.add('Two').dom.addEventListener('click', function(e){ alert('I am the two'); }, true);
 *  tt.add('Three').dom.addEventListener('click', function(e){ alert('I am the three'); }, true);
 *  // do a click
 *
 *  Getting the selected:
 *  tt.selected;
*/

[[INCLUDE component:Trunk]]
[[INCLUDE component:TrunkButton]]

(function(){
	'use strict';

	function TrunkTab() {
		this.items = [];
		this.selected = null;

		var dom = this.dom = document.createElement('div');

		var that = this;
		this._button_click = function(e) {
			// Find selected from 'this'
			for (var i in that.items) {
				if (that.items[i].dom == this) {
					that.select(i);
				}
			}

			// Remove the focus
			this.blur();
		}
	}

	TrunkTab.prototype.add = function(text) {
		var button = trunk.create('Button');
		button.dom.classList.add('none');
		button.dom.addEventListener('click', this._button_click, true);
		if (undefined !== text) {
			button.dom.innerHTML = text;
		}
		this.dom.appendChild(button.dom);
		this.items.push(button);
		return button;
	};

	TrunkTab.prototype.get = function(i) {
		return this.items[i];
	};

	TrunkTab.prototype.select = function(i) {
		// Restore style (if exists previous selected)
		if (null != this.selected) {
			this.selected.dom.classList.add('none');
		}

		// Add style to selected button
		this.items[i].dom.classList.remove('none');

		// Select the item
		this.selected = this.items[i];
	}

	trunk.register(TrunkTab);

})();