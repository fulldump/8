[[INCLUDE component=ShadowButton]]

var newGraphicPopup = function() {

	var dom = document.createElement('div');
	dom.className = 'graphicPopup';

	var frame = document.createElement('div');
	dom.appendChild(frame);
	frame.className = 'graphicPopup-frame';

	var content = document.createElement('div');
	frame.appendChild(content);
	content.className = 'graphicPopup-content';

	var buttons = document.createElement('div');
	frame.appendChild(buttons);
	buttons.className = 'graphicPopup-buttons';

	var button_accept = document.createElement('button');
	buttons.appendChild(button_accept);
	button_accept.className = 'shadow-button shadow-button-blue';
	button_accept.innerHTML = 'Aceptar';

	var button_close = document.createElement('button');
	buttons.appendChild(button_close);
	button_close.className = 'shadow-button shadow-button-red';
	button_close.innerHTML = 'Cerrar';
	button_close.addEventListener('click', function(event){
		dom.hide();
	}, false);

	dom.show = function() {
		dom.style.display = 'block';
		frame.style.marginLeft = '-'+frame.clientWidth/2+'px'
		frame.style.marginTop = '-'+frame.clientHeight/2+'px'
	}

	dom.setCallback = function(fun) {
		button_accept.addEventListener('click', fun, true);

	}

	dom.hide = function() {
		dom.style.display = 'none';
	}

	dom.appendContent = function(elem) {
		content.appendChild(elem);
	}

	dom.style.display = 'none';
	document.body.appendChild(dom);

	return dom;
};
