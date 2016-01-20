[[INCLUDE component=SimpleList]]
[[INCLUDE component=Ajax]]


function load_profile(id) {
	var ajax = new Ajax('[[AJAX name=load_profile id=49]]');
	ajax.setCallback200( function(text) {
		document.getElementById("derecha").innerHTML = text;
	});
	ajax.query({'id':id});
}

function info(color, name, time, db, tag) {
	var a = document.getElementById('profile_info');
	a.style.display = 'block';
	
	a.innerHTML = '<div style="display:inline-block; background-color:'+color+'; width:8px; height:8px; "></div> '+name+'<br>'+
			'Time: '+time+' ms<br>Queries: '+db+'<br>'+tag;
	

}

function noInfo() {
	var a = document.getElementById('profile_info');
	a.style.display = 'none';
}