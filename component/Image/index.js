[[INCLUDE component=Ajax]]
[[INCLUDE component=GraphicPopupImage]]

window.addEventListener('load', function(event) {

	var newImage = function (dom) {
		var edit_id = dom.getAttribute('edit_id');
		var edit_options = dom.getAttribute('edit_options');
		dom.addEventListener('click', function(event) {
			var gpi = newGraphicPopupImage();
			gpi.setCallbackImage(function(image) {
				dom.src='/img/'+image.id+edit_options;
				var ajax = new Ajax('[[AJAX name=set_image]]');
				ajax.query({'id_image_instance':edit_id,'id_image':image.id});
			});
			gpi.show();
		}, true);
		dom.parentNode.setAttribute('href', '#');
	}

	var elements = document.querySelectorAll('[component="Image"]')
	for (var i=0; i<elements.length; i++) {
		element = elements[i];
		if ('Image' == element.getAttribute('component') && null != element.getAttribute('edit_id') ) {
			newImage(element);
		}
	}

}, true);
