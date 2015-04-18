[[INCLUDE component=Ajax]]
[[INCLUDE component=TrunkButton]]



(function(){
	'use strict';
	
	var Feedback = function(dom) {
		this.dom = dom;

		var ajax = new Ajax('[[AJAX name=partial]]');
		ajax.setCallback200(function(text){
			that.initialize(text);
		});
		ajax.query({});

		var that = this;
	};

	Feedback.prototype.initialize = function(partial) {
		
		this.dom.innerHTML = partial;
		
		this.button = this.dom.querySelector('.button');
		this.button.addEventListener('click', function(e) {
			that.dom.classList.add('opened');
			that.dom.classList.remove('closed');
			that.message.focus();
		}, true);

		this.shadow = this.dom.querySelector('.shadow');
		this.shadow.addEventListener('click', function(e) {
			that.dom.classList.remove('opened');
			that.dom.classList.add('closed');
		}, true);
		
		this.message = this.dom.querySelector('.message');
		
		this.send = this.dom.querySelector('.send');
		this.send.addEventListener('click', function(e) {
			that.sending.classList.add('visible');
			
			var ajax = new Ajax('[[AJAX name=send]]');
			ajax.setCallback200(function(text){
				that.sending.classList.remove('visible');
				that.thanks.classList.add('visible');
				setTimeout(function(){
					that.dom.style.display = 'none';
				}, 3000);
			});
			ajax.query({
				message: that.message.innerHTML,
			});
			
		}, true);
		
		this.sending = this.dom.querySelector('.sending');
		
		this.thanks = this.dom.querySelector('.thanks');

		var that = this;
	};


	window.addEventListener('load', function(event) {
		var elements = document.querySelectorAll('[data-component="Feedback"]');
		for (var i = 0; i < elements.length; i++) {
			new Feedback(elements[i]);
		}
	}, true);


})();