

[[INCLUDE component=Ajax]]

newLabel = function (dom) {

	var edit_id = dom.getAttribute('edit_id');
	if (edit_id != null) {
		dom.setAttribute('contentEditable', 'true');
		dom.addEventListener('blur', function(event) {
			dom.style.border = 'solid orange 1px';
			var ajax = new Ajax('[[AJAX name=set_label]]');
			ajax.setCallback200(function(text){
				dom.style.border = '';
			});
			ajax.query({'id':edit_id,'text':this.innerHTML});
			
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
			var id = parseInt(cadena.replace('Label', ''));
			if (!isNaN(id)) {
				newLabel(elements[key]);
			}
		}
	}
}, true);