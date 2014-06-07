[[INCLUDE component=Ajax]]
[[INCLUDE component=GraphicPopupImage]]


window.addEventListener('load', function(event){
	var elements = document.getElementsByTagName('*');
	var cadena;
	var docs = [];
	for (key in elements) {
		cadena = elements[key].id;
		if (typeof(cadena)=='string') {
			var id = parseInt(cadena.replace('SimpleText', ''));
			if (!isNaN(id))
				docs[id] = elements[key];
		}
	}
	for (key in docs) 
		newSimpleText(docs[key]).loadDocument(key);
	
}, true);


var newSimpleText = function(dom) {

	var toolbar = document.createElement('div');
	toolbar.className = 'simpletext-toolbar';

	var group1 = document.createElement('div'); toolbar.appendChild(group1);
	group1.className = 'simpletext-group';
	// Negrita
	var button1 = document.createElement('button'); group1.appendChild(button1);
	button1.className = 'icon-bold';
	button1.addEventListener('mousedown', function(event){
		document.execCommand('bold', false, null);
	}, true);
	// Cursiva
	var button2 = document.createElement('button'); group1.appendChild(button2);
	button2.className = 'icon-italic';
	button2.addEventListener('mousedown', function(event){
		document.execCommand('italic', false, null);
	}, true);
	// Subrayado
	var button3 = document.createElement('button'); group1.appendChild(button3);
	button3.className = 'icon-underline';
	button3.addEventListener('mousedown', function(event){
		document.execCommand('underline', false, null);
	}, true);
	// Tachado
	var button4 = document.createElement('button'); group1.appendChild(button4);
	button4.className = 'icon-strike';
	button4.addEventListener('click', function(event){
		document.execCommand('strikeThrough', false, null);
	}, true);
	// Borrar formato
	var button9 = document.createElement('button'); group1.appendChild(button9);
	button9.className = 'icon-eraseformat';
	button9.addEventListener('click', function(event){
		document.execCommand('removeFormat', false, null);
	}, true);


	var group4 = document.createElement('div'); toolbar.appendChild(group4);
	group4.className = 'simpletext-group';
	// Imagen
	var button10 = document.createElement('button'); group4.appendChild(button10);
	button10.className = 'icon-image';
	button10.addEventListener('mousedown', function(event){
		var gpi = newGraphicPopupImage();
		gpi.setCallbackImage(function(image) {
			document.execCommand('insertImage', false, '/img/'+image.id);
		});
		gpi.show();
	}, true);	
	// Enlace
	var button11 = document.createElement('button'); group4.appendChild(button11);
	button11.className = 'icon-hiperlink';
	button11.addEventListener('mousedown', function(event){
		var href = '';
		if (href = prompt("Message", "http://")) {
			document.execCommand('createLink', false, href);
		}
	}, true);	


	var group2 = document.createElement('div'); toolbar.appendChild(group2);
	group2.className = 'simpletext-group';
	// Lista normal
	var button5 = document.createElement('button'); group2.appendChild(button5);
	button5.className = 'icon-unorderedlist';
	button5.addEventListener('mousedown', function(event){
		document.execCommand('insertUnorderedList', false, null);
	}, true);	
	// Lista ordenada
	var button6 = document.createElement('button'); group2.appendChild(button6);
	button6.className = 'icon-orderedlist';
	button6.addEventListener('mousedown', function(event){
		document.execCommand('insertOrderedList', false, null);
	}, true);

	var group3 = document.createElement('div'); toolbar.appendChild(group3);
	group3.className = 'simpletext-group';
	// SUP
	var button7 = document.createElement('button'); group3.appendChild(button7);
	button7.className = 'icon-super';
	button7.addEventListener('mousedown', function(event){
		document.execCommand('superscript', false, null);
	}, true);	
	// INF
	var button8 = document.createElement('button'); group3.appendChild(button8);
	button8.className = 'icon-sub';
	button8.addEventListener('mousedown', function(event){
		document.execCommand('subscript', false, null);
	}, true);
	


	var textarea = document.createElement('div'); textarea.innerHTML = 'loading...';
	textarea.className = 'simpletext-textarea';
	textarea.focused = false;

	dom.innerHTML = '';
	dom.appendChild(toolbar);
	dom.appendChild(textarea);

	textarea.addEventListener('focus', function(event){
		textarea.focused = true;
		toolbar.style.display = 'block';
		toolbar.style.width = textarea.clientWidth+'px';
		toolbar.style.marginTop = '-'+toolbar.clientHeight+'px';
	}, true);

	textarea.addEventListener('blur', function(event) {
		textarea.style.border='solid orange 1px';
		textarea.style.margin='-1px';
		var ajax = new Ajax('[[AJAX name=save_text]]');
		ajax.setCallback200(function(text){
			textarea.style.border='';
			textarea.style.margin='';
		});
		ajax.query({'id':id_text,'text':textarea.innerHTML});
	}, true);

	textarea.addEventListener('blur', function(event){
		textarea.focused = false;
		setTimeout(function(){if (!textarea.focused) toolbar.style.display = '';}, 500);
	}, true);

	toolbar.addEventListener('mouseup', function(event){
		textarea.focus();
	}, true);



	// CONSTRUCTOR:
	var id_text=0;

	dom.loadDocument = function(id) {
		id_text = id;
		var ajax = new Ajax('[[AJAX name=load_text]]');

		// TODO: Bloquear edición de partes hasta que termine el proceso de carga
		ajax.setCallback200(function(text){
			textarea.innerHTML = text;
			textarea.setAttribute('contentEditable', true);
		});
		ajax.query({'id':id});
	}

	dom.getComponentVersion = function() {
		return '0.0.1';
	}


	return dom;
}


