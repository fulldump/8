[[INCLUDE component=Ajax]]
[[INCLUDE component=ShadowButton]]

function makeQuestion(question) {
	if (question=='') {
		alert('Debes hacer una pregunta');
	} else {
		var ajax = new Ajax('[[AJAX name=make_question]]');
		ajax.setCallback200(function(text) {
			var div = document.createElement('div');
			div.innerHTML = text;
			document.getElementById('preguntas').appendChild(div);
		});
		ajax.query({'text':question});
	}
}

function searchQuestions(question) {
	var ajax = new Ajax('[[AJAX name=search_question]]');
	ajax.setCallback200(function(text) {
		document.getElementById('preguntas').innerHTML = text;
		var msg = document.getElementById('no-hay-resultados');
		if (text == '') {
			msg.style.display = 'block';
		} else {
			msg.style.display = 'none';
		}
	});
	ajax.query({'text':question});
}

function botonResponderClick(id) {
	var answer = document.getElementById('answer'+id);
	answer.style.display = 'block';
	
	var s = '';
	s += '<div style="padding:8px; background-color:white;">';
		s += '<textarea id="answer-textarea'+id+'" style="border:none; padding:0; margin:0; width:100%;"></textarea>';
	s += '</div>';
	s += '<div style="margin-top:16px; text-align:right;">';
		s += '<button class="shadow-button" onclick="answer(\''+id+'\', document.getElementById(\'answer-textarea'+id+'\').value)">Responder</button>';
	s += '</div>';
	
	answer.innerHTML = s;
	
	document.getElementById('answer-textarea'+id).focus();
	document.getElementById('answer-textarea'+id).setAttribute('onkeyup', 'ajustarTextarea(this);');
}

function answer(id_post, response) {
	if (response == '') {
		alert('Debes escribir una respuesta');
	} else {
		document.getElementById('answer'+id_post).style.display = 'none';
		var ajax = new Ajax('[[AJAX name=answer]]');
		ajax.setCallback200(function(text) {
			var div = document.createElement('div');
			div.innerHTML = text;
			document.getElementById('hijos'+id_post).appendChild(div);
			document.getElementById('answer-button1-'+id_post).style.display = '';
		});
		ajax.query({'id':id_post,'text':response});
	}
}

function ajustarTextarea(ta) {
    if(ta.clientHeight<500) {
        ta.style.height = ta.scrollHeight+'px';
    }
    if (ta.clientHeight>500) ta.style.height = '500px';
}