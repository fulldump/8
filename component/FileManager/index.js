[[INCLUDE component=Ajax]]
[[INCLUDE component=GraphicInputButton]]
[[INCLUDE component=ShadowButton]]
[[INCLUDE component=GraphicPopupFile]]

var FileManagerItem = function(data) {
	
	var that = this;

	this.data = data;

	this.dom = document.createElement('a');
	this.dom.href = '/file/'+data.id;
	this.dom.setAttribute('data-mime', data.mime);
	this.dom.className = 'fm-list-item';

	this.delete = document.createElement('div');
	this.delete.data = data;
	this.delete.className = 'fm-list-item-delete';
	this.delete.addEventListener('click', function(event) {
		event.stopPropagation();
		event.preventDefault();
		that.dom.className += ' predeleting';
		var ajax = new Ajax('[[AJAX name=delete_file]]');
		ajax.setCallback200(function(text) {
			that.dom.className += ' deleting';
		});
		ajax.query({'id':this.data.id});
	}, false);
	this.dom.appendChild(this.delete);

	this.icon = document.createElement('div');
	this.icon.className = 'fm-list-item-icon';
	this.dom.appendChild(this.icon);

	this.name = document.createElement('div');
	this.name.className = 'fm-list-item-name';
	this.name.innerHTML = data.name;
	this.dom.appendChild(this.name);

	var date = new Date(data.timestamp*1000);

	this.details = document.createElement('div');
	this.details.className = 'fm-list-item-details';
	this.details.innerHTML =
		'<span class="attr-size">' + sizeToHuman(data.size) + ', </span>' +
		'<span class="attr-timestamp">'+'on ' + date.getDate() + '/' + date.getMonth() + '/' + date.getFullYear() + ', </span>' +
		'<span class="attr-user">'+'by ' + data.user + '</span>';
	this.dom.appendChild(this.details);


	if ( (['image/png','image/gif','image/jpg','image/jpeg','image/svg+xml','image/svg']).indexOf(data.mime) >= 0) {
		this.icon.style.backgroundImage = "url('/file/"+data.id+"')";
	}
}

function sizeToHuman(s) {
	var pf = (['bytes','KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB']);

	var i = 0;
	while (s>999) {
		s /= 1024;
		i++;
	}
	return (Math.round(s*100)/100)+' '+pf[i];
}



var FileManager = function() {

	var that = this;

	this.dom = document.createElement('div');
	this.dom.className = 'file-manager';

	this.bar = document.createElement('div');
	this.bar.className = 'fm-bar';
	this._build_bar();
	this.dom.appendChild(this.bar);

	this.list = document.createElement('div');
	this.list.className = 'fm-list';
	this.list.setAttribute('view', 'list');
	this.dom.appendChild(this.list);

	this.adder = document.createElement('div');
	this.adder.className = 'fm-adder';
	this.dom.appendChild(this.adder);

	var ab = this.adder.button = document.createElement('button');
	ab.className = 'shadow-button shadow-button-blue';
	ab.innerHTML = 'Upload file';
	ab.addEventListener('click', function(event){
		var gpf = newGraphicPopupFile();
		gpf.setCallbackFile(function(a){
			that.search();
		});
		gpf.show();
	}, true);
	this.adder.appendChild(ab);




	/*
	// TODO: delete:
	var i;
	for (i=0; i<100; i++)
		this.add(new FileManagerItem({'id':1,'name':('Fichero '+i+'.jpg'), 'details':'detalles', 'mime':'mime/type'}));
	*/

	this.search();
}

FileManager.prototype.appendTo = function(o) {
	o.appendChild(this.dom);
}

FileManager.prototype.add = function(o) {
	this.list.appendChild(o.dom);
}

FileManager.prototype.search = function() {

	var that = this;

	var ajax = new Ajax('[[AJAX name=load_files]]');

	ajax.setCallback200(function(text) {
		that.list.innerHTML = '';
		var json = eval('('+text+')');
		for(k in json) {
			that.add(new FileManagerItem(json[k]));
		}
	});

	ajax.query({'q':this.searcher.input.value});

}

FileManager.prototype._build_bar = function() {
	var that = this;



	this.searcher = newGraphicInputButton('Search');
	this.searcher.input.addEventListener('keyup', function(event){
		that.search();
	}, true);
	this.bar.appendChild(this.searcher);


	var list_view = document.createElement('a');
	list_view.href = '#';
	list_view.innerHTML = ' list ';
	list_view.addEventListener('click', function(event){
		that.list.setAttribute('view', 'list');
	}, true);
	this.bar.appendChild(list_view);


	var grid_view = document.createElement('a');
	grid_view.href = '#';
	grid_view.innerHTML = ' grid ';
	grid_view.addEventListener('click', function(event){
		that.list.setAttribute('view', 'grid');
	}, true);
	this.bar.appendChild(grid_view);
}






window.addEventListener('load', function(event){
	var fms = document.getElementById('file-manager');
	(new FileManager()).appendTo(fms);
}, true);