[[INCLUDE component=Ajax]]
[[INCLUDE component=GraphicPopupImage]]

newImage = function (dom) {

	var edit_id = dom.getAttribute('edit_id');
	if (edit_id != null) {
		//dom.setAttribute('contentEditable', 'true');
		dom.addEventListener('dblclick', function(event) {
			var gpi = newGraphicPopupImage();
			gpi.setCallbackImage(function(image) {
				dom.src='/img/'+image.id;
				var ajax = new Ajax('[[AJAX name=set_image]]');
				ajax.query({'id_image_instance':edit_id,'id_image':image.id});
			});
			gpi.show();
		}, true);
	}

	dom.parentNode.setAttribute('href', '#');

}

window.addEventListener('load', function(event) {
	var elements = document.getElementsByTagName('*');
	var cadena;
	for (key in elements) {
		cadena = elements[key].id;
		if (typeof(cadena)=='string') {
			var id = parseInt(cadena.replace('Image', ''));
			if (!isNaN(id)) {
				newImage(elements[key]);
			}
		}
	}
}, true);