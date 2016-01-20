[[INCLUDE component=AdminxDatabase]]

window.addEventListener('load', function(e){
	var container = document.getElementById('container');
	var adminx_database = new AdminxDatabase();
	container.appendChild(adminx_database.dom);
}, true);