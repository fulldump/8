[[INCLUDE component=Ajax]]

[[INCLUDE component=GraphicPopup]]

function admin_recargar(url) {
	var ajax = new Ajax('[[AJAX name=admin_recargar]]');
	ajax.setCallback200(function(text) {
		json = eval('('+text+')');
		if (json.redirect != undefined) {
			document.getElementById('iframe').src = json.redirect;
		} else {
			document.getElementById('idioma').innerHTML = json.language;
		}
	});
	ajax.query({'url':url});
	
}

function admin_go_home() {
	var home = '/?edit';
	document.getElementById('iframe').src = home;
}

function admin_meta() {
	var gp = newGraphicPopup();

	var div = document.createElement('div'); gp.appendContent(div);
	div.className = 'admin-meta';
	div.style.width = '500px';
	div.innerHTML = 'Cargando...';

	gp.setCallback(function(event){
		gp.hide();
	});
	gp.show();

	var ajax = new Ajax('[[AJAX name=admin_load_meta]]');
	ajax.setCallback200(function(text) {
		div.innerHTML = '';

		var json = eval('('+text+')');

		var label_title = document.createElement('div'); div.appendChild(label_title);
		label_title.innerHTML = 'Título';

		var title = document.createElement('input'); div.appendChild(title);
		title.value = json.title;
		title.addEventListener('blur', function(event){
			admin_set_title(json.id, this.value, json.lang);
		}, true);
	
		var label_description = document.createElement('div'); div.appendChild(label_description);
		label_description.innerHTML = 'Descripción';
	
		var description = document.createElement('textarea'); div.appendChild(description);
		description.style.height = '80px';	
		description.value = json.description;
		description.addEventListener('blur', function(event){
			admin_set_description(json.id, this.value, json.lang);
		}, true);


		gp.show();
	});
	ajax.query({'url':document.getElementById('iframe').contentWindow.location.href});
}

function admin_set_title(id, value, lang) {
	var ajax = new Ajax('[[AJAX name=admin_set_title]]');
	ajax.query({'id':id,'value':value,'lang':lang});
}

function admin_set_description(id, value, lang) {
	var ajax = new Ajax('[[AJAX name=admin_set_description]]');
	ajax.query({'id':id,'value':value,'lang':lang});
}

function admin_estadisticas() {
	window.open('https://www.google.com/analytics/web/?hl=es&pli=1#report/visitors-overview/a42154948w71816274p74105129/');
}