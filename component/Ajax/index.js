// Component Ajax
var Ajax = function (url) {
	this.query = function (params) {
        var data = '';
		for (key in params)
			data += key+'='+encodeURIComponent(params[key])+'&';
			ajax.send(data);
	}
	
	this.setCallback200 = function (cb) {
		_callback200 = cb;
	}

	this.abort = function() {
		ajax.abort();
	};
	
	/* CONSTRUCTOR */
	
	var _callback200 = null;
	
	var ajax = null;
	try { // Firefox, Opera 8.0+, Safari
		ajax = new XMLHttpRequest();
	} catch (e) { // Puto y pestilente Internet Explorer
		try {
			ajax = new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			ajax = new ActiveXObject("Microsoft.XMLHTTP");
		}
	}

	ajax.open('POST', url, true);
	ajax.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
	ajax.onreadystatechange = function () {
		if (ajax.readyState == 4) {
			if (ajax.status == 200) {
				if (_callback200!=null)
					_callback200(ajax.responseText);
			}
		}
	}
};
