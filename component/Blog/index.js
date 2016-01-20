[[INCLUDE component=Ajax]]

blog_save_post_title = function(id, title) {
	var ajax = new Ajax('[[AJAX name=blog_save_post_title]]');
	ajax.query({'id':id,'title':title});
}