[[INCLUDE component=Ajax]]
[[INCLUDE component=SimpleList]]
[[INCLUDE component=Favicon]]
[[INCLUDE component=GraphicPopupImage]]

setFavicon('/images/4/data.ico');

function loadEntities() {
	var ajax = new Ajax('[[AJAX name=load_entities]]');
	ajax.setCallback200(function(text) {
		list_entities.clear();
		var ajax = eval('('+text+')');
		var value = '';
		for (key in ajax) {
			value = ajax[key];
			list_entities.add(value, value);
		}
	});
	ajax.query({});
}

function loadTableOLD(table) {
	var ajax = new Ajax('[[AJAX name=load_table]]');
	ajax.setCallback200(function(text) {
		document.getElementById('panel-editor-content').innerHTML = text;
		document.getElementById('panel-editor').style.display = 'block';
	});
	ajax.query({'entity':table});
}

function loadTable(entity, parent) {
	var ajax = new Ajax('[[AJAX name=load_table]]');
	var query = document.getElementById('query-input');
	ajax.setCallback200(function(text) {
		parent.innerHTML = '';
		
		var ajax = eval('('+text+')');
		
		var table = document.createElement('table'); parent.appendChild(table);
		
		var header = document.createElement('tr'); table.appendChild(header);
		
		var fields = ajax['fields'];
		var th;
		th = document.createElement('th'); header.appendChild(th);
		th = document.createElement('th'); header.appendChild(th);
		th.innerHTML = 'Id';
		for (key in fields) {
			th = document.createElement('th'); header.appendChild(th);
			th.innerHTML = key;
		}
		
		var data = ajax['data'];
		
		var textarea_keydown = function(event) {
			if (event.keyCode==9) {
				event.stopPropagation();
				event.preventDefault();
				
				var startPos = this.selectionStart;
				var endPos = this.selectionEnd; 
				this.value = this.value.substring(0, startPos)+ '\t' + this.value.substring(endPos, this.value.length);
				this.selectionStart = startPos+1;
				this.selectionEnd = startPos+1;
			}
		}
		
		var row;
		var tr;
		var td;
		var editable;
		var editable_border;
		for (key in data) {
			row = data[key];
			tr = document.createElement('tr'); table.appendChild(tr);
			td = document.createElement('td'); tr.appendChild(td);
			td.innerHTML = '<a href="javascript:" tabindex="-1" onclick="deleteRow(\''+entity+'\',\''+row['Id']+'\', this);" style="font-weight:bold; color:red; text-decoration:none;">X</a>';
			for (col in row) {
				td = document.createElement('td'); tr.appendChild(td);
				
				
				if (col == 'Id') {
					td.className = 'field-Id';
					td.innerHTML = row[col];
				} else {
					if (fields[col]['type']=='Number') {
						editable = document.createElement('input');
						editable.value = row[col];
						editable.setAttribute('onchange', 'setValue(\''+entity+'\',\''+row['Id']+'\',\''+col+'\', this);');
						td.appendChild(editable);
					} else if (fields[col]['type']=='Text') {
						editable = document.createElement('textarea');
						editable.value = row[col];
						editable.setAttribute('onchange', 'setValue(\''+entity+'\',\''+row['Id']+'\',\''+col+'\', this);');
						td.appendChild(editable);
					} else if (fields[col]['type']=='Image') {
						td.classList.add('field-Image');
						editable = document.createElement('button');
						editable.style.backgroundImage = "url('"+row[col]+"/w:96;h:96;q:50')";
						editable.innerHTML = 'Edit';
						editable.entity = entity;
						editable.row = row['Id'];
						editable.col = col;
						editable.cell_value = row[col];
						editable.addEventListener('click', function(e){
							var that = this;
							var gpi = newGraphicPopupImage();
							gpi.setCallbackImage(function(image) {
								that.style.backgroundImage = "";
								var ajax = new Ajax('[[AJAX name=set_image]]');
								ajax.setCallback200(function(text) {
									that.style.backgroundImage = "url('/img/"+image.id+"/w:96;h:96;q:50')";
								});
								ajax.query({'entity':that.entity,'id':that.row,'field':that.col,'value':image.id});
							})
							gpi.show();
						}, true);
						td.appendChild(editable);
					} else {
						td.innerHTML = row[col]; //+' <a href="javascript:" onclick="panelDerivedType(\''+entity+'\', \''+row[0]+'\', \''+col+'\')" style="float:right;">Editar</a>';
					}
				}
				
			}
		}
		
		document.getElementById('panel-editor').style.display = 'block';
	});
	ajax.query({'entity':entity,'query':query.value});
}

function insertRow(table) {
	var ajax = new Ajax('[[AJAX name=insert_row]]');
	ajax.setCallback200(function(text) {
		//loadTable(table);
		loadTable(table, document.getElementById('panel-editor-content'));
	});
	ajax.query({'entity':table});
}

function deleteRow(table, id, link) {
	var table_row = link.parentNode.parentNode;
	table_row.setAttribute('operation', 'DELETE');
	var ajax = new Ajax('[[AJAX name=delete_row]]');
	ajax.setCallback200(function(text) {
		table_row.parentNode.removeChild(table_row);
	});
	ajax.query({'entity':table,'id':id});
}

function setValue(table, id, field, input) {
	input.style.color = 'blue';
	var ajax = new Ajax('[[AJAX name=set_value]]');
	ajax.setCallback200(function(text) {
		input.style.color = '';
		input.value = text;
	});
	ajax.query({'entity':table,'id':id,'field':field,'value':input.value});
}

function panelDerivedType(table, id, field) {
	var ajax = new Ajax('[[AJAX name=panel_derived_type]]');
	ajax.setCallback200(function(text) {
		var dialog = new Dialog();
		dialog.setTitle(field);
		dialog.show();
		
		var cuerpo = document.createElement('div');
		cuerpo.setAttribute('class','panel-editor-content');
		cuerpo.innerHTML = text;
		dialog.add(cuerpo);
	});
	ajax.query({'entity':table,'id':id,'field':field});
}

function list_entities_click() {
	document.getElementById('query-input').value = '';
	loadTable(list_entities.getSelectedId(), document.getElementById('panel-editor-content'));
}

