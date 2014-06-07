// Component TrunkTable

[[INCLUDE component=Trunk]]
[[INCLUDE component=TrunkInputButton]]
[[INCLUDE component=Ajax]]

(function() {
	
	function TrunkTable() {
		this.name = null;
		this.fields = {};
		this.rows = {};

		this.dom = document.createElement('div');

		this.search = trunk.create('InputButton');
		this.search.dom.classList.add('search');
		this.dom.appendChild(this.search.dom);

		this.table = document.createElement('table');
		this.dom.appendChild(this.table);

		this.table.head = document.createElement('thead');
		this.table.appendChild(this.table.head);

		this.table.body = document.createElement('tbody');
		this.table.appendChild(this.table.body);

		// this.table.foot = document.createElement('tfoot');
		// this.table.appendChild(this.table.foot);

		this.pagination = document.createElement('div');
		this.pagination.classList.add('pagination')
		this.pagination.innerHTML = 'pagination';
		this.dom.appendChild(this.pagination);
	}

	TrunkTable.prototype.load = function(table_name) {
		this.name = table_name;
		this.loadFields();
	};

	TrunkTable.prototype.loadFields = function() {
		var that = this;
		var ajax = new Ajax('[[AJAX name=load_fields]]');
		ajax.setCallback200(function(text) {
			that.fields = JSON.parse(text);
			that.repaintFields();
			that.loadRows();
		});
		ajax.query({name:this.name});
	};

	TrunkTable.prototype.loadRows = function() {
		var that = this;
		var ajax = new Ajax('[[AJAX name=load_rows]]');
		ajax.setCallback200(function(text) {
			that.rows = JSON.parse(text);
			that.repaintRows();
		});
		ajax.query({name:this.name});
	};	

	TrunkTable.prototype.repaintFields = function() {
		// TODO: improve this:
		this.table.head.innerHTML = '';

		var tr = document.createElement('tr');
		this.table.head.appendChild(tr);

		for(var i in this.fields) {
			var field = this.fields[i];

			var td = document.createElement('th');
			td.setAttribute('type', field.type);
			td.setAttribute('native', field.native);
			td.setAttribute('title', field.type);
			td.innerHTML = '<div class="a"><div class="c"><div class="f">'+i+'</div></div><div class="b">'+i+'</div></div>';
			tr.appendChild(td);

			field.dom = tr;
		}

		// this.table.foot.appendChild(tr.cloneNode(true));

	};

	TrunkTable.prototype.repaintRows = function() {
		// TODO: improve this:
		this.table.body.innerHTML = '';

		var fields = this.fields;

		for(var i in this.rows) {
			var row = this.rows[i];

			var tr = document.createElement('tr');

			for (var field in fields) {
				var td = document.createElement('td');
				td.setAttribute('type', field.type);
				td.setAttribute('native', field.native);
				td.innerHTML = row[field];
				tr.appendChild(td);
			}

			this.table.body.appendChild(tr);
		}
	};



	trunk.register(TrunkTable);

})();