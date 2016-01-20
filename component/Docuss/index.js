(function(config){
	
	console.log(config);
	
	var index = [];
	var dom = null;

	window.addEventListener('load', function(e) {
		dom = document.querySelector(config.query_selector);

		loadCSS('//softwaremaniacs.org/media/soft/highlight/styles/googlecode.css');

		loadJS('//softwaremaniacs.org/media/soft/highlight/highlight.pack.js', function(e) {
			processALL();
		});

	}, true);

	function processALL() {
		addMetaMobile();

		index = [];

		traverseDOM(dom, function(item) {
			var tagName = item.tagName;
			switch (tagName) {
				case 'CODE': processCODE(item); break;
				case 'H1': processH(item, 1); break;
				case 'H2': processH(item, 2); break;
				case 'H3': processH(item, 3); break;
				case 'H4': processH(item, 4); break;
				case 'H5': processH(item, 5); break;
				case 'H6': processH(item, 6); break;
			}
		});

		if (config.add_index) createIndex();
		if (config.add_title) addDocumentTitle();

		searchForHash();
	};

	function addMetaMobile() {
		var meta = document.createElement('meta');
		meta.setAttribute('name', 'viewport');
		meta.setAttribute('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no');
		document.head.appendChild(meta);
	}

	/**
	 * If URL has a hash, navigate to it
	*/
	function searchForHash() {
		var hash = location.hash;
		if ('' != hash) {
			location.href = hash;
		}
	}

	function addDocumentTitle() {
		var documentTitle = document.title;
		if ('' != documentTitle) {
			var div = document.createElement('div');
			div.className = 'first-page';
			dom.insertBefore(div, dom.firstChild);

			var title = document.createElement('div');
			title.className = 'document-title';
			title.innerHTML = documentTitle;
			div.appendChild(title);

			var listMeta = document.getElementsByTagName('meta');
			console.log(listMeta);
			var metas = [];
			for (var i=0; i<listMeta.length; i++) {
				var meta = listMeta[i];
				var meta_name = meta.getAttribute('name');
				if (null != meta_name) {
					meta_name = meta_name.toLowerCase();
					if (null == metas[meta_name]) {
						metas[meta_name] = [];
					}
					var content = meta.getAttribute('content');
					metas[meta_name][content] = 0;
				}
			}

			// Build metas:
			var divMetas = document.createElement('table');
			divMetas.className = 'document-meta';
			for (var meta in metas) {
				var row = document.createElement('tr');
				row.className = 'meta meta-'+meta;
				divMetas.appendChild(row);

				var rowTitle = document.createElement('td');
				rowTitle.className = 'title';
				rowTitle.innerHTML = meta;
				row.appendChild(rowTitle);

				var rowContents = document.createElement('td');
				rowContents.className = 'contents';
				row.appendChild(rowContents);

				for (var c in metas[meta]) {
					var content = document.createElement('div');
					content.className = 'content';
					content.innerHTML = c;
					rowContents.appendChild(content);
				}

			}
			if (config.add_meta) div.appendChild(divMetas);
		}
	}

	function createIndex() {    
		var current_level = 0;
		var result = '';

		var identation = [];

		var i = 0;
		while (i<index.length) {
			var level = index[i].level;
			if (current_level < level) {
				identation.push(0);
				result+=('<ol>');
				current_level++;
			} else if (current_level > level) {
				identation.pop();
				result+=('</ol>');
				current_level--;
			} else {
				identation.push(1+identation.pop());

				var item = index[i].item;
				item.id = 'title-'+identation.join('.');
				item.innerHTML = '<a href="#'+item.id+'"><span class="identation">'+identation.join('.')+'</span>'+item.innerHTML+'</a>';
				result+=('<li>'+item.innerHTML+'');

				i++;
			}
		}

		var div = document.createElement('div');
		div.className = 'index';
		div.innerHTML = result;

		dom.insertBefore(div, dom.firstChild);
	}

	function processCODE(item) {

		var frame = document.createElement('div');
		frame.className = 'code-frame';
		item.parentNode.insertBefore(frame, item);

		var title = document.createElement('div');
		title.className = 'code-title';
		frame.appendChild(title);

		var pre = document.createElement('pre');
		pre.appendChild(item);
		frame.appendChild(pre);

		var lang = '';
		if (null !== item.getAttribute('lang')) {
			lang = item.getAttribute('lang');
		} else if (null != item.getAttribute('language')) {
			lang = item.getAttribute('language');
		}
		if ('' != lang) {
			item.className = lang;
		}

		hljs.highlightBlock(item, null, false);

		title.innerHTML = item.className;
	};

	function processH(item, level) {
		index.push({
			item:item,
			level:level
		});
	}

	function traverseDOM(item, callback) {
		var children = item.children;
		for (var i=0; i<children.length; i++) {
			traverseDOM(children[i], callback);
		}
		callback(item);
	}

	function loadJS(url, callback) {
		var script = document.createElement('script');
		script.setAttribute('type', 'text/javascript');
		script.setAttribute('src', url);
		if (callback) {
			script.addEventListener('load', callback, true);
		}
		document.head.appendChild(script);
	}

	function loadCSS(url, callback) {
		var link = document.createElement('link');
		link.setAttribute('rel', 'stylesheet');
		link.setAttribute('type', 'text/css');
		link.setAttribute('title', 'default');
		link.setAttribute('href', url);
		if (callback) {
			link.addEventListener('load', callback, true);
		}
		document.head.appendChild(link);
	}

	
	
	
})({
	query_selector: '.docuss',
	add_index: true,
	add_title: true,
	add_meta: false,
});