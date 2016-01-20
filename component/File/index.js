[[INCLUDE component=ShadowButton]]
[[INCLUDE component=GraphicPopupFile]]




function file_link_click(id) {
	var gpf = newGraphicPopupFile();
	gpf.setCallbackFile(function(a){
		var ajax = new Ajax('[[AJAX name=set_file]]');
		ajax.query({'id':id,'file':a.id});
	});
	gpf.show();
}