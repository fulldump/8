[[INCLUDE component=Ajax]]

var newLabel = function (dom) {
	dom.setAttribute('contentEditable', 'true');
	dom.addEventListener('blur', newLabel.callback_blur, true);
	dom.parentNode.setAttribute('href', '#');
}

newLabel.callback_blur = function(event) {
	var that = this;
	this.style.border = 'solid orange 1px';
	var ajax = new Ajax('[[AJAX name=set_label]]');
	ajax.setCallback200(function(text){
		that.style.border = '';
	});
	ajax.query({
		'id':this.getAttribute('edit_id'),
		'text':this.innerHTML,
	});
};

window.addEventListener('load', function(event) {
	var elements = document.querySelectorAll('[component="Label"]');
	for (var i = 0; i < elements.length; i++) {
		element = elements[i];
		if ('Label' == element.getAttribute('component')) {
			newLabel(element);
		}
	}
}, true);