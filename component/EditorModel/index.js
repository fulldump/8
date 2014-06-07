[[INCLUDE component=Ajax]]

[[INCLUDE component=Favicon]]

setFavicon('/images/4/model.ico');

Dialog = function () {
	
	this.show = function () {
		dom.style.display = 'block';
		document.body.appendChild(dom);
	}
	
	this.hide = function() {
		dom.style.display = 'none';
	}
	
	this.appendBar = function (elem) {
		if (box_bar.hasChildNodes()) {
			//box_bar.firstChild.insertBefore(elem);
			box_bar.appendChild(elem);
		} else {
			box_bar.appendChild(elem);
		}
	}
	
	this.setCloseButtonVisible = function (visible) {
		if (visible)
			button_close.style.visibility = 'visible';
		else
			button_close.style.visibility = 'hidden';
	}
	
	this.clear = function () {
		box_body.innerHTML = '';
	}
	
	this.add = function (elem) {
		box_body.appendChild(elem);
	}
	
	this.setTitle = function (title) {
		box_title.innerHTML = title;
	}
	
	this.setCloseCallback = function (cb) {
		_close_callback = cb;
	}
	
	var _close_callback = null;
	
	
	
	/* CONSTRUCTOR */
	
	var dom = document.createElement('div');
	dom.style.position = 'absolute';
	dom.style.top = '0px';
	dom.style.left = '0px';
	dom.style.height = '100%';
	dom.style.width = '100%';
	dom.style.backgroundColor = 'rgba(0,0,0,0.5)';
	
	var box = document.createElement('div');
	box.style.width = '600px';
	box.style.margin = '64px auto 32px auto';
	box.style.background = 'white';
	box.style.padding = '0px';
	box.style.border = 'solid black 1px';
	dom.appendChild(box);
	
	var box_title = document.createElement('div');
	box_title.innerHTML = 'BOX TITLE';
	box_title.style.background = 'black';
	box_title.style.padding = '4px';
	box_title.style.color = 'white';
	box_title.style.fontWeight = 'bold';
	box.appendChild(box_title);
	
	var box_body = document.createElement('div');
	box_body.style.padding = '16px';
	box.appendChild(box_body);
	
	var box_bar = document.createElement('div');
	box_bar.style.padding = '8px';
	box_bar.style.background = 'silver';
	box_bar.style.textAlign = 'right';
	box.appendChild(box_bar);
	
	var button_close = document.createElement('button');
	button_close.innerHTML = 'Close';
	button_close.onclick = function() {
		dom.style.display = 'none';
		if (_close_callback != null) _close_callback();
	};
	
	this.appendBar(button_close);
	
}

[[INCLUDE component=SimpleList]]

function loadEntities(selected) {
	var ajax = new Ajax('[[AJAX name=load_entities]]');
	ajax.setCallback200(function(text) {
		list_entities.clear();
		var ajax = eval('('+text+')');
		var value = '';
		for (key in ajax) {
			value = ajax[key];
			list_entities.add(value, value);
			
		}
		
		if (selected != undefined) 
			list_entities.select(selected);
	});
	ajax.query({});
}


function loadEntity(entity) {
	var ajax = new Ajax('[[AJAX name=load_entity]]');
	ajax.setCallback200(function(text) {
		document.getElementById('model-editor-properties').style.display = 'none';
		
		var model_editor = document.getElementById('model-editor');
		model_editor.innerHTML = '';
		var ajax = eval('('+text+')');
		var fields = ajax['model']['fields'];
		var entities = ajax['entities'];
		
		var elem_click = function(event) {
			if (model_editor.last_selected != null)
				model_editor.last_selected.style.backgroundColor = '';
			
			model_editor.last_selected = this;
			model_editor.last_selected.style.backgroundColor = '#EEEEEE';
			
			var properties = document.getElementById('model-editor-properties');
			properties.style.display = 'block';
			properties.style.top = this.offsetTop+'px';
			
			// Cargo los tipos y selecciono el actual
			var combo = document.getElementById('model-editor-properties-type');
			combo.innerHTML = '';
			
			for (key in entities) {
				var opcion = document.createElement('option');
				opcion.innerHTML = entities[key];
				combo.appendChild(opcion);
				if (this.field_type == entities[key])
					opcion.setAttribute('selected', 'selected');
			}
		}
		
		var simple_list = document.createElement('div');
		simple_list.setAttribute('class', 'simple-list');
		for (key in fields) {
			var elem = document.createElement('button');
			elem.setAttribute('class', 'elem');
			elem.innerHTML = key + '<span style="font-size:10px;"> ('+fields[key]+')</span>';
			elem.field_type = fields[key];
			elem.field_name = key;
			elem.addEventListener('click', elem_click, true);
			simple_list.appendChild(elem);
		}
		model_editor.appendChild(simple_list);
		var margin = document.createElement('div');
		margin.last_selected = null;
		margin.setAttribute('class', 'margen');
		margin.setAttribute('style', 'background-color:silver; border-radius:4px; margin:16px;');
		model_editor.appendChild(margin);
		var tabla = document.createElement('table');
		tabla.setAttribute('style', 'width:100%');
		margin.appendChild(tabla);
		var fila;
		fila = document.createElement('tr');
		tabla.appendChild(fila);
		var celda;
		celda = document.createElement('td');
		celda.innerHTML = 'Atributo';
		fila.appendChild(celda);
		celda = document.createElement('td');
		fila.appendChild(celda);
		var entrada = document.createElement('input');
		celda.appendChild(entrada);
		fila = document.createElement('tr');
		tabla.appendChild(fila);
		celda = document.createElement('td');
		celda.innerHTML = 'Tipo';
		fila.appendChild(celda);
		celda = document.createElement('td');
		fila.appendChild(celda);
		var combo = document.createElement('select');
		celda.appendChild(combo);
		
		for (key in entities) {
			var opcion = document.createElement('option');
			opcion.innerHTML = entities[key];
			combo.appendChild(opcion);
		}
		
		var botonera = document.createElement('div');
		botonera.setAttribute('style', 'padding-top:16px; text-align:right;');
		margin.appendChild(botonera);
		var add = document.createElement('button');
		add.setAttribute('class', 'shadow-button shadow-button-blue')
		add.innerHTML = 'Añadir atributo';
		add.addEventListener('click', function(event) {addAttribute(entity, entrada.value, combo.value);}, true);
		botonera.appendChild(add);
	});
	ajax.query({'entity':entity});
}

function createEntity(name) {
	var ajax = new Ajax('[[AJAX name=create_entity]]');
	ajax.setCallback200(function(text) {
		loadEntities(name);
	});
	ajax.query({'name':name});
}

function deleteEntity(entity) {
	var ajax = new Ajax('[[AJAX name=delete_entity]]');
	ajax.setCallback200(function(text) {
		if (text=='') {
			document.getElementById('model-editor').innerHTML = text;
			document.getElementById('model-editor-properties').style.display = 'none';
			loadEntities();
		} else {
			alert(text);
		}
	});
	ajax.query({'entity':entity});
}

function regenerateEntity(entity) {
	var ajax = new Ajax('[[AJAX name=regenerate_entity]]');
	ajax.setCallback200(function(text) {
		alert('Entidad '+entity+' regenerada.');
	});
	ajax.query({'entity':entity});
}

function regenerateAllEntities() {
	var ajax = new Ajax('[[AJAX name=regenerate_all]]');
	ajax.setCallback200(function(text) {
		alert('Se han regenerado todas las entidades.');
	});
	ajax.query({});
}

function addAttribute(entity, attribute, type) {
	if (attribute == '') {
		alert('El atributo debe tener un nombre');
	} else {
		var ajax = new Ajax('[[AJAX name=add_attribute]]');
		ajax.setCallback200(function(text) {
			loadEntity(entity);
		});
		ajax.query({'entity':entity,'attribute':attribute,'type':type});
	}
}

function deleteAttribute(entity, attribute) {
	var ajax = new Ajax('[[AJAX name=delete_attribute]]');
	ajax.setCallback200(function(text) {
		loadEntity(entity);
	});
	ajax.query({'entity':entity,'attribute':attribute});
}
