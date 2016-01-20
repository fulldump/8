[[INCLUDE component=GraphicPopup]]
[[INCLUDE component=GraphicList]]



var newGraphicPopupFile = function() {

	var dom = newGraphicPopup();
	dom.callback_image = null;

	var panel = document.createElement('div');
	panel.className = 'panel-insert-image';
	dom.appendContent(panel);

	var panel_left = document.createElement('div');
	panel_left.className = 'panel-left';
	panel.appendChild(panel_left);

	var panel_right = document.createElement('div');
	panel_right.className = 'panel-right';
	panel.appendChild(panel_right);

	var file_sources = newGraphicList();
	file_sources.setDocked(false);
	panel_left.appendChild(file_sources);

	file_sources.setCallbackClick(function(event) {
		sources[this.id]();
	});

	file_sources.add(1, 'Buscar en mi disco duro');
	//file_sources.add(2, 'Pegar URL http://...');
	//file_sources.add(3, 'Mis imágenes');
	//file_sources.add(5, 'Búsqueda en Google');

	dom.setCallback (function(event) {
		sources_callback[file_sources.getSelectedId()]();
	});

	dom.setCallbackFile = function(cb) {
		dom.callback_image = cb;
	};

	var sources = new Array();
	var sources_callback = new Array();


	// Source 1: A file from HDD
	var input_file;
	var iframe_id = 'upload_file_from_hdd'+(new Date()).getTime();
	var iframe_target = 'target'+(new Date()).getTime();
	var form;
	sources[1] = function() {
		iframe_id = 'upload_file_from_hdd'+(new Date()).getTime();
		var iframe_target = 'target'+(new Date()).getTime();
		panel_right.innerHTML = 'Seleccione un archivo desde su disco duro:<br><br>';
		var g = document.createElement('div'); panel_right.appendChild(g);
		g.innerHTML = '<iframe id="'+iframe_id+'" style="display:none;" name="'+iframe_target+'"></iframe>';
		var iframe;
		form = document.createElement('form'); panel_right.appendChild(form);
		form.target = iframe_target;
		form.action = '[[AJAX name=upload_file_from_hdd]]';
		form.method = 'post';
		form.enctype = 'multipart/form-data';
		input_file = document.createElement('input'); form.appendChild(input_file);
		input_file.type = 'file';
		input_file.name = 'image[]';
		input_file.multiple = true;
	};

	sources_callback[1] = function() {
			form.submit();
			// ANIMACIÓN DE ENVIANDO...
			iframe = document.getElementById(iframe_id);
			iframe.addEventListener('load', function(event) {
				dom.hide();
				// Actualizo la imagen si lo que me devuelve es mayor de cero
				var text = this.contentDocument.body.innerHTML;
				// Devuelvo el resultado json evaluado
				var image = eval('('+text+')');
				if (dom.callback_image != null) {
					dom.callback_image(image);
				}
			}, true);
	}



	file_sources.select(1);
	sources[1]();

	return dom;
}
