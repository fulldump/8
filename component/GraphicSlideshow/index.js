[[INCLUDE component=Ajax]]
[[INCLUDE component=GraphicPopupImage]]


function fake(id) {
	document.getElementById('fake').style.marginLeft = '-'+(id*100)+'%';
}

function selectImage(item_id) {
	var gpi = newGraphicPopupImage();
	gpi.setCallbackImage(function(image) {
		var ajax = new Ajax('[[AJAX name=set_image]]');
		ajax.query({'item_id':item_id,'image_id':image.id});

		var item = document.getElementById('GraphicSlideshow-item-'+item_id);
		item.style.backgroundImage = 'url(/img/'+image.id+'/h:380)';
	});
	gpi.show();
}