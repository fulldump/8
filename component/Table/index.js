[[INCLUDE component=Ajax]]
[[INCLUDE component=GraphicPopupFile]]



function Table(table_name, dom) {

	lpad = function(s, padString, length) {
		var str = s+'';
		while (str.length < length)
			str = padString + str;
		return str;
	}

	
	dom.setAttribute('component', 'Table');

	var data = null;
	
	function build() {
		// Build table:
		var table = document.createElement('table');
		table.classList.add('table');
		dom.appendChild(table);
		
		// Build head:
		var thead = document.createElement('thead');
		table.appendChild(thead);

		var header = document.createElement('tr');
		thead.appendChild(header);
		
		var th = document.createElement('th');
		header.appendChild(th);

		for (var k in data.fields) {
			var th = document.createElement('th');
			th.innerHTML = k;
			header.appendChild(th);
		}

		var add_row = function(e) {
			dom.innerHTML = 'loading...';
			var that = this;
			var ajax = new Ajax('[[AJAX name=add]]');
			ajax.setCallback200(function(text) {
				dom.innerHTML = '';
				reload();
			});
			ajax.query({
				table: table_name,
			});
		};

		var remove_row = function(e) {
			var that = this;
			if (confirm('¿Estás seguro de borrar esta fila?')) {
				var ajax = new Ajax('[[AJAX name=remove]]');
				ajax.setCallback200(function(text){
					that.tr.parentNode.removeChild(that.tr);
				});
				ajax.query({
					table: table_name,
					row: this.row.id,
				});
			}
		};
		
		var save_native = function(e) {
			var that = this;
			if (this.innerText != this.row[this.field.name]) {
				this.classList.add('saving');
				var ajax = new Ajax('[[AJAX name=save]]');
				ajax.setCallback200(function(text){
					//that.innerText = text;
					that.classList.remove('saving');
				});
				ajax.query({
					table: table_name,
					row: this.row.id,
					field: this.field.name,
					value: this.innerText,
				});
			}
		};
		
		var save_date = function(e) {
			var that = this;
			this.classList.add('saving');
			
			parts = this.value.split("T");
			alert(parts);
			
			
			
			/*
			var ajax = new Ajax('[[AJAX name=save]]');
			ajax.setCallback200(function(text){
				//that.innerText = text;
				that.classList.remove('saving');
			});
			ajax.query({
				table: table_name,
				row: this.row.id,
				field: this.field.name,
				value: this.innerText,
			});
			*/
		};
		
		var save_combo = function(e) {
			var that = this;
			if (this.value != this.row[this.field.name]) {
				this.classList.add('saving');
				var ajax = new Ajax('[[AJAX name=save_combo]]');
				ajax.setCallback200(function(text){
					that.classList.remove('saving');
				});
				ajax.query({
					table: table_name,
					row: this.row.id,
					field: this.field.name,
					value: this.value,
				});
			}
		};

		var save_file = function(e) {
			var that = this;

			var file = document.createElement('input');
			file.setAttribute('type', 'file');
			file.setAttribute('style', 'display: none;');
			file.addEventListener('change', function(e) {
				var files = this.files;
				if (1 != files.length) {
					alert('Debe seleccionar un archivo.');
					return;
				}

				var form_data = new FormData();
				form_data.append('file', files[0], files[0].name);
				form_data.append('table', table_name);
				form_data.append('row', that.row.id);
				form_data.append('field', that.field.name);

				var xhr = new XMLHttpRequest();
				xhr.open('POST', '[[AJAX name=save_file]]', true);

				xhr.onload = function () {
					var json = JSON.parse(xhr.responseText);
					if (xhr.status === 200) {
						that.innerHTML = 'Uploaded';
						that.classList.add('uploaded');
					} else {
						that.innerHTML = 'Error';
						that.classList.add('error');
					}
				};
				xhr.send(form_data);
			}, true);
			this.parentNode.appendChild(file);
			file.click();
		};
		
		
		// Build body:
		var tbody = document.createElement('tbody');
		table.appendChild(tbody);

		for (var row_id in data.rows) {
			var row = data.rows[row_id];

			var tr = document.createElement('tr');

			var td = document.createElement('td');
				var remove = document.createElement('span');
				remove.classList.add('remove');
				remove.row = row;
				remove.tr = tr;
				remove.addEventListener('click', remove_row, true);
				td.appendChild(remove);
			tr.appendChild(td);

			for (var field_id in data.fields) {
				var field = data.fields[field_id];
				
				
				var td = document.createElement('td');
				td.setAttribute('type', field.type);
				if (field.native) {
					if ('Date' == field.type) {
						dtl = document.createElement('input');
						dtl.setAttribute('type', 'datetime-local');
						dtl.row = row;
						dtl.field = field;
						
						d = new Date(1000*row[field_id]);
						s = 							
							d.getUTCFullYear()
							+'-'
							+lpad(1+d.getUTCMonth(), '0', 2)
							+'-'
							+lpad(d.getUTCDate(), '0', 2)
							+'T'
							+lpad(d.getUTCHours(), '0', 2)
							+':'
							+lpad(d.getUTCMinutes(), '0', 2)
							+':'
							+lpad(d.getUTCSeconds(), '0', 2);
						
						dtl.setAttribute('value', s);
						dtl.addEventListener('blur', save_date, true);
						td.appendChild(dtl);
					} else {
						td.row = row;
						td.field = field;
						td.innerText = row[field_id];
						td.setAttribute('contenteditable', true);
						td.addEventListener('blur', save_native, true);
					}
				} else {
					if ('File' == field.type) {
						var upload = document.createElement('button');
						upload.innerHTML = 'Upload';
						upload.row = row;
						upload.field = field;
						upload.addEventListener('click', save_file, true);
						td.appendChild(upload);

						if (0 != row[field_id]) {
							var name = document.createElement('a');
							name.innerHTML = 'Download';
							name.setAttribute('href', '/file/' + row[field_id]);
							td.appendChild(name);
						}
					} else {
						var combo = document.createElement('select');
						combo.row = row;
						combo.field = field;
						combo.addEventListener('change', save_combo, true);

						var option = document.createElement('option');
						option.value = 0;
						option.innerText = '';
						combo.appendChild(option);
						
						for (var id in field.combo) {
							var option = document.createElement('option');
							option.value = id;
							option.innerText = field.combo[id];						
							combo.appendChild(option);
							if (id == row[field_id]) {
								option.setAttribute('selected', true);
							}
						}
						td.appendChild(combo);
					}
				}
				tr.appendChild(td);
			}
			tbody.appendChild(tr);
		}
		
		// Build buttons
		var buttons = document.createElement('div');
		buttons.classList.add('buttons');
		dom.appendChild(buttons);

		var button_add = document.createElement('button');
		button_add.innerHTML = 'Add';
		button_add.addEventListener('click', add_row, true);
		buttons.appendChild(button_add);
	}
	
	function reload() {
		var ajax = new Ajax('[[AJAX name=reload]]');
		ajax.setCallback200(function(text){
			data = JSON.parse(text);
			build();
		});
		ajax.query({table:table_name});
	}
	
	this.reload = reload;
	

	
	
	
	this.reload();
	
}

