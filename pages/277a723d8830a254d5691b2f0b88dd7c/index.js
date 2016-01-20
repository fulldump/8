[[INCLUDE component=AdminxTest]]

window.addEventListener('load', function(e){
	var container = document.getElementById('container');
	var adminx_test = new AdminxTest();
	container.appendChild(adminx_test.dom);
}, true);