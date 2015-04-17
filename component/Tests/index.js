(function(context) {

	var Test = function(tests) {

		this.tests = tests;
	};

	Test.prototype.log = function(text) {

		return this.tests.log(text);
	};

	Test.prototype.error = function(text) {

		throw new Error(text);
	};





	var Tests = function() {

		// GUI
		this.dom = document.createElement('div');
		this.dom.setAttribute('component', 'Tests');
		document.body.appendChild(this.dom);

		// Add runs the test while stop==false
		this.stop = false;

		// Run all tests even if this.stop==false
		this.all = false;

		// Queue
		this.queue = [];

		// Callbakcs
		this.callbacks_prepare = [];
		this.callbacks_restore = [];

		// Tests counter
		this.c = 0;

		// Welcome
		this.log('Welcome to Tests');
	};

	Tests.prototype.reset = function() {

		this.all = false;
		this.queue = [];
		this.callbacks_prepare = [];
		this.callbacks_restore = [];
		this.c = 0;
	};

	Tests.prototype.add = function(f) {

		if (this.stop && !this.all) {
			return;
		}

		this.c++;

		var entry = this.log('Test ' + this.c + ' ');
		entry.classList.add('test-entry');

		try {
			var t = new Test(this);
			for (var i in this.callbacks_prepare) {
				this.callbacks_prepare[i](t);
			}
			f(t);
		} catch (err) {
			this.stop = true;
			console.log(err.stack);
			this.log(err.stack).classList.add('test-error');
			entry.classList.add('test-error');
			return false;
		} finally {
			for (var i in this.callbacks_restore) {
				this.callbacks_restore[i](t);
			}
		}

		entry.classList.add('test-pass');
		return true;
	};

	Tests.prototype.prepare = function(f) {
		this.callbacks_prepare.push(f);
	};

	Tests.prototype.restore = function(f) {
		this.callbacks_restore.push(f);
	};

	Tests.prototype.log = function(text) {

		var entry = document.createElement('div');
		entry.innerHTML = text.replace(/</mg, '&lt;');
		this.dom.appendChild(entry);
		this.dom.scrollTop = entry.offsetTop;

		return entry;
	};

	context.Tests = Tests;

})(window);
