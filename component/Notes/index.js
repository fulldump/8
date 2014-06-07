[[INCLUDE component=Ajax]]



function NotesMakeEntry(id_notes) {
	var ajax = new Ajax('[[AJAX name=notes-make-entry]]');
	ajax.setCallback200(function(text) {
		location.reload();
	});
	ajax.query({'id_notes':id_notes});
}

function NotesRemoveEntry(id_entry) {
	if (confirm('Your entry will be removed permanently, ¿Do you wish to continue?')) {
		var ajax = new Ajax('[[AJAX name=notes-remove-entry]]');
		ajax.setCallback200(function(text) {
			location.reload();
		});
		ajax.query({'id_entry':id_entry});
	}
}

function NotesPublishEntry(id_entry) {
	var ajax = new Ajax('[[AJAX name=notes-publish-entry]]');
	ajax.setCallback200(function(text) {
		location.reload();
	});
	ajax.query({'id_entry':id_entry});
}
