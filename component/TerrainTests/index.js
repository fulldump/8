[[INCLUDE component=Terrain]]
[[INCLUDE component=Tests]]



(function(){

	'use strict';

	var do_tests = function() {
		var tests = new Tests();
		tests.all = true;
		
		tests.prepare(function(t){

			var div = document.createElement('div');
			document.body.appendChild(div);
			var terrain = new Terrain(div);
			terrain.enableEditor();

			t.terrain = terrain;
		});

		tests.prepare(function(t){
			
			t.test_clean = function(original, expected) {
				t.terrain.dom.innerHTML = original;
				t.terrain.clean();
				if (t.terrain.dom.innerHTML != expected) {
					t.error('terrain.clean() fails, \''+original+'\' should be \''+expected+'\'');
				}
			};
		});

		tests.restore(function(t){

			document.body.removeChild(t.terrain.dom);
		});

		tests.add(function(t){

			t.test_clean('<div>hello</div>', '<p>hello</p>');
		});	

		tests.add(function(t){

			t.test_clean('<p attribute="my attribute">hello</p>', '<p>hello</p>');
		});	

		tests.add(function(t){

			t.test_clean('aaa<p>bbb</p>ccc', '<p>aaa</p><p>bbb</p><p>ccc</p>');
		});	
		
		
		
		

		// cleanText
		tests.prepare(function(t){

			t.test_cleanText = function(original, expected) {
				t.terrain.dom.innerHTML = original;
				t.terrain.cleanText(t.terrain.dom);
				if (t.terrain.dom.innerHTML != expected) {
					t.error('terrain.cleanText() fails, \''+original+'\' should be \''+expected+'\', but is \''+t.terrain.dom.innerHTML+'\'');
				}
			};
		});

		tests.add(function(t){
			t.test_cleanText('<b color="red">hello</b>', '<strong>hello</strong>');
		});	

		tests.add(function(t){
			t.test_cleanText('<strong color="red">hello</strong>', '<strong>hello</strong>');
		});	

		tests.add(function(t){
			t.test_cleanText('<i color="red">hello</i>', '<em>hello</em>');
		});	

		tests.add(function(t){
			t.test_cleanText('<em color="red">hello</em>', '<em>hello</em>');
		});	

		tests.add(function(t){
			t.test_cleanText('<u color="red">hello</u>', '<u>hello</u>');
		});	

		tests.add(function(t){
			t.test_cleanText('<s color="red">hello</s>', '<s>hello</s>');
		});	

	};


	window.addEventListener('load', do_tests, true);

})();