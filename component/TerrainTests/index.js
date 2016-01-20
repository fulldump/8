[[INCLUDE component = Terrain]]
[[INCLUDE component = Tests]]


(function() {

	'use strict';


	// Prepare functions

	var instantiate_terrain = function(t) {
		/**
		Create a terrain instance and attach it to t.terrain
		*/

		var div = document.createElement('div');
		document.body.appendChild(div);
		var terrain = new Terrain(div);
		terrain.enableEditor();

		t.terrain = terrain;
	};

	var prepare_send_keys = function(t) {
		/**
		Simulates a key press
		*/
		t.sendKey = function(options) {
			if (!options) {
				t.error('\'options\' parameter is mandatory for t.sendKey()')
			}

			var e = {
				_preventDefault: 0,
				preventDefault: function() {
					this._preventDefault++;
				},
				_stopPropagation: 0,
				stopPropagation: function() {
					this._stopPropagation++;
				},
			};

			for (var k in options) {
				e[k] = options[k];
			}

			e.type = 'keydown';
			t.terrain.processEvent(e);

			e.type = 'keypress';
			t.terrain.processEvent(e);

			e.type = 'keyup';
			t.terrain.processEvent(e);

			return e;
		};
	};

	var prepare_append_tag = function(t) {
		t.appendTag = function(tag_name, inner_html) {
			var tag = document.createElement(tag_name);
			if (inner_html) {
				tag.innerHTML = inner_html;
			}
			t.terrain.dom.appendChild(tag);
			return tag;
		};
	};

	var prepare_empty_terrain = function(t) {
		t.emptyTerrain = function() {
			t.terrain.dom.innerHTML = '';
		};
	};

	var prepare_send_tab = function(t) {
		t.sendTab = function(shift) {
			t.sendKey({
				keyCode: 9,
				shiftKey: shift
			});
		};
	};

	var uninstantiate_terrain = function(t) {
		/**
		Remove terrain instance from DOM
		*/
		document.body.removeChild(t.terrain.dom);
	};

	var test_clean = function(t) {
		// Add function test_clean to test and attach it to t.test_clean

		t.test_clean = function(original, expected) {
			t.terrain.dom.innerHTML = original;
			t.terrain.clean();
			if (t.terrain.dom.innerHTML != expected) {
				t.error('terrain.clean() fails\n\t\'' + original + '\'\nshould be:\n\t\'' + expected + '\',\nbut is:\n\t\'' + t.terrain.dom.innerHTML + '\'\n');
			}
		};
	};


	// Test packs

	var test_pack = {};

	test_pack['clean'] = function(tests) {

		tests.prepare(instantiate_terrain);
		tests.prepare(test_clean);
		tests.restore(uninstantiate_terrain);

		var allowed_tags = ['p', 'code', 'blockquote', 'figure', 'h2', 'h3', 'h4', 'h5', 'h6', 'table'];

		tests.add(function(t) {
			// Check allowed tags except 'table' are not filtered
			for (var i in allowed_tags) {
				var tag = allowed_tags[i];
				if ('table' == tag) {
					continue;
				}
				t.test_clean(
					'<' + tag + ' attribute="val">hello</' + tag + '>',
					'<' + tag + '>hello</' + tag + '>'
				);
			}
		});

		tests.add(function(t) {
			// Check tag 'table' is not filtered
			t.test_clean(
				'<table borderspacing="0"><tbody><tr><td>a</td><td>b</td></tr></tbody></table>',
				'<table><tbody><tr><td>a</td><td>b</td></tr></tbody></table>'
			);
		});

		tests.add(function(t) {
			t.test_clean('<div color="blue">hello</div>', '<p>hello</p>');
		});

		tests.add(function(t) {
			t.test_clean('<p attribute="my attribute">hello</p>', '<p>hello</p>');
		});

		tests.add(function(t) {
			t.test_clean('<code attribute="my attribute">h<span>e</span>llo</code>', '<code>hello</code>');
		});

		tests.add(function(t) {
			t.test_clean('aaa<p>bbb</p>', '<p>aaa</p><p>bbb</p>');
		});

		tests.add(function(t) {
			t.test_clean('<p>bbb</p>ccc', '<p>bbb</p><p>ccc</p>');
		});

	};

	test_pack['cleanText'] = function(tests) {

		// test suit cleanText //

		var prepare_text = function(t) {

			t.test_cleanText = function(original, expected) {
				t.terrain.dom.innerHTML = original;
				t.terrain.cleanText(t.terrain.dom);
				if (t.terrain.dom.innerHTML != expected) {
					t.error('terrain.cleanText() fails\n\t\'' + original + '\'\nshould be:\n\t\'' + expected + '\',\nbut is:\n\t\'' + t.terrain.dom.innerHTML + '\'\n');
				}
			};
		};

		tests.prepare(instantiate_terrain);
		tests.prepare(prepare_text);
		tests.restore(uninstantiate_terrain);

		var allowed_tags = ['strong', 'em', 'u', 's', 'sup', 'sub', 'code'];

		tests.add(function(t) {
			t.test_cleanText('<b color="red">hello</b>', '<strong>hello</strong>');
		});

		tests.add(function(t) {
			t.test_cleanText('<i color="red">hello</i>', '<em>hello</em>');
		});

		tests.add(function(t) {
			t.test_cleanText('aaa<br color="red">bbb', 'aaa<br>bbb');
		});

		tests.add(function(t) {
			// Check allowed tags are not filtered

			for (var i in allowed_tags) {
				var tag = allowed_tags[i];
				t.test_cleanText('<' + tag + ' color="red">hello</' + tag + '>', '<' + tag + '>hello</' + tag + '>');
			}
		});

		tests.add(function(t) {
			// Check nested tags are not filtered

			for (var i in allowed_tags) {
				var etag = allowed_tags[i]; // external tag
				for (var j in allowed_tags) {
					var itag = allowed_tags[i]; // internal tag
					t.test_cleanText(
						'<' + etag + '>te<' + itag + '>te<span>-</span>xt</' + itag + '>xt</' + etag + '>',
						'<' + etag + '>te<' + itag + '>te-xt</' + itag + '>xt</' + etag + '>'
					);
				}
			}
		});

		tests.add(function(t) {
			// Check inside a text tag:
			for (var i in allowed_tags) {
				var tag = allowed_tags[i];
				t.test_cleanText('aaa<' + tag + ' color="red">bbb<span color="red">ccc</span>dddd</' + tag + '>eee', 'aaa<' + tag + '>bbbcccdddd</' + tag + '>eee');
			}
		});

	};



	test_pack['keySimulations'] = function(tests) {

		tests.prepare(instantiate_terrain);
		tests.prepare(prepare_send_keys);
		tests.restore(uninstantiate_terrain);

		// ALL
		tests.add(function(t) {
			t.terrain.putCursorAt(t.terrain.dom);
			var e = t.sendKey({
				keyCode: 9
			});

			if (0 == e._stopPropagation) {
				t.error('Tab key should stop propagation');
			}
			if (0 == e._preventDefault) {
				t.error('Tab key should prevent default');
			}
		});

		// CODE
		tests.add(function(t) {
			/**
			Check if enter key inside a root code candel and stop event
			*/
			t.terrain.dom.innerHTML = '<code>print("hello world!")</code>';
			t.terrain.putCursorAt(t.terrain.dom.childNodes[0].childNodes[0], 0);

			var e = t.sendKey({
				keyCode: 13
			});

		});

		// CODE
		tests.add(function(t) {
			/**
			Check if enter key inside a root code candel and stop event
			*/
			t.terrain.dom.innerHTML = '<code>print("hello world!")</code>';
			t.terrain.putCursorAt(t.terrain.dom.childNodes[0].childNodes[0], 0);

			var e = t.sendKey({
				keyCode: 13
			});

			if (0 == e._stopPropagation) {
				t.error('Enter key inside root code should stop propagation');
			}
			if (0 == e._preventDefault) {
				t.error('Enter key inside root code should prevent default');
			}

		});

		// CODE
		tests.add(function(t) {
			t.terrain.dom.innerHTML = '<code>print("hello world!")</code>';
			t.terrain.putCursorAt(t.terrain.dom.childNodes[0].childNodes[0], 13);
			var e = t.sendKey({
				keyCode: 13
			});
			if ('<code>print("hello <br>world!")<br></code>' != t.terrain.dom.innerHTML) {
				t.error('Enter  key inside root node should insert a BR tag')
			}

		});

		// CODE
		tests.add(function(t) {
			/**
			Check if enter key inside a root code candel and stop event
			*/
			t.terrain.dom.innerHTML = '<code></code>';
			t.terrain.putCursorAt(t.terrain.dom.childNodes[0]);

			var e = t.sendKey({
				keyCode: 13
			});
			if ('<p><br></p><code><br></code>' != t.terrain.dom.innerHTML) {
				t.error('Enter key inside root code should insert a line break char')
			}
		});

	};



	test_pack['keyTab h1 h2 h3 h4 h5 h6'] = function(tests) {

		tests.prepare(instantiate_terrain);
		tests.prepare(prepare_send_keys);
		tests.prepare(prepare_send_tab);
		tests.prepare(prepare_append_tag);
		tests.prepare(prepare_empty_terrain);
		tests.restore(uninstantiate_terrain);

		tests.add(function(t) {
			t.emptyTerrain();
			var h = t.appendTag('h2', 'Title level 2');
			t.terrain.putCursorAt(h);

			t.sendTab();

			if ('<h3>Title level 2</h3>' != t.terrain.dom.innerHTML) {
				t.error('h2 should be h3');
			}
		});

		tests.add(function(t) {
			t.emptyTerrain();
			var h = t.appendTag('h3', 'Title level 3');
			t.terrain.putCursorAt(h);

			t.sendTab();

			if ('<h4>Title level 3</h4>' != t.terrain.dom.innerHTML) {
				t.error('h3 should be h4');
			}
		});

		tests.add(function(t) {
			t.emptyTerrain();
			var h = t.appendTag('h4', 'Title level 4');
			t.terrain.putCursorAt(h);

			t.sendTab();

			if ('<h5>Title level 4</h5>' != t.terrain.dom.innerHTML) {
				t.error('h4 should be h5');
			}
		});

		tests.add(function(t) {
			t.emptyTerrain();
			var h = t.appendTag('h5', 'Title level 5');
			t.terrain.putCursorAt(h);

			t.sendTab();

			if ('<h6>Title level 5</h6>' != t.terrain.dom.innerHTML) {
				t.error('h5 should be h6');
			}
		});

		tests.add(function(t) {
			t.emptyTerrain();
			var h = t.appendTag('h6', 'Title level 6');
			t.terrain.putCursorAt(h);

			t.sendTab();

			if ('<h6>Title level 6</h6>' != t.terrain.dom.innerHTML) {
				t.error('h6 should be h6');
			}
		});

		tests.add(function(t) {
			t.emptyTerrain();
			var h = t.appendTag('h2', 'Title level 2');
			t.terrain.putCursorAt(h);

			t.sendTab(true);

			if ('<h2>Title level 2</h2>' != t.terrain.dom.innerHTML) {
				t.error('h2 should be h2');
			}
		});

		tests.add(function(t) {
			t.emptyTerrain();
			var h = t.appendTag('h3', 'Title level 3');
			t.terrain.putCursorAt(h);

			t.sendTab(true);

			if ('<h2>Title level 3</h2>' != t.terrain.dom.innerHTML) {
				t.error('h3 should be h2');
			}
		});

		tests.add(function(t) {
			t.emptyTerrain();
			var h = t.appendTag('h4', 'Title level 4');
			t.terrain.putCursorAt(h);

			t.sendTab(true);

			if ('<h3>Title level 4</h3>' != t.terrain.dom.innerHTML) {
				t.error('h4 should be h3');
			}
		});

		tests.add(function(t) {
			t.emptyTerrain();
			var h = t.appendTag('h5', 'Title level 5');
			t.terrain.putCursorAt(h);

			t.sendTab(true);

			if ('<h4>Title level 5</h4>' != t.terrain.dom.innerHTML) {
				t.error('h5 should be h4');
			}
		});

		tests.add(function(t) {
			t.emptyTerrain();
			var h = t.appendTag('h6', 'Title level 6');
			t.terrain.putCursorAt(h);

			t.sendTab(true);

			if ('<h5>Title level 6</h5>' != t.terrain.dom.innerHTML) {
				t.error('h6 should be h5');
			}
		});


	};

	test_pack['enter h1 h2 h3 h4 h5 h6'] = function(tests) {

		tests.prepare(instantiate_terrain);
		tests.prepare(prepare_send_keys);
		tests.prepare(prepare_append_tag);
		tests.prepare(prepare_empty_terrain);
		tests.restore(uninstantiate_terrain);

		tests.add(function(t) {
			for (var i = 2; i <= 6; i++) {

				t.emptyTerrain();
				var h = t.appendTag('h' + i, 'Title level ' + i);
				t.terrain.putCursorAt(h.firstChild, 5);

				var e = t.sendKey({
					keyCode: 13
				});

				if ('<h' + i + '>Title level ' + i + '</h' + i + '><p><br></p>' != t.terrain.dom.innerHTML) {
					t.error('should be <p><br></p> after the H' + i + ' tag');
				}

				if (0 == e._stopPropagation) {
					t.error('stopPropagation() has not been called.')
				}

				if (0 == e._preventDefault) {
					t.error('preventDefault() has not been called.')
				}

			}
		});

		tests.add(function(t) {
			for (var i = 2; i <= 6; i++) {

				t.emptyTerrain();
				var h = t.appendTag('h' + i, 'Title level ' + i);
				t.terrain.putCursorAt(h.firstChild, 0);

				var e = t.sendKey({
					keyCode: 13
				});

				if ('<p><br></p><h' + i + '>Title level ' + i + '</h' + i + '>' != t.terrain.dom.innerHTML) {
					t.error('should be <p><br></p> after the H' + i + ' tag');
				}

				if (0 == e._stopPropagation) {
					t.error('stopPropagation() has not been called.')
				}

				if (0 == e._preventDefault) {
					t.error('preventDefault() has not been called.')
				}

			}
		});

	};


	test_pack['isCursorAtBegin'] = function(tests) {

		tests.prepare(instantiate_terrain);
		tests.restore(uninstantiate_terrain);

		tests.add(function(t) {
			/**
			Check if enter key inside a root code candel and stop event
			*/
			t.terrain.dom.innerHTML = '<p>This <u>is a big test</u> text</p>';
			var node = t.terrain.dom.childNodes[0];

			t.terrain.putCursorAt(t.terrain.dom.childNodes[0].childNodes[0], 0);
			if (!t.terrain.isCursorAtBegin(node)) {
				t.error('cursor: <p>|This <u>is a big test</u> text</p> should be true')
			}

			t.terrain.putCursorAt(t.terrain.dom.childNodes[0].childNodes[0], 1);
			if (t.terrain.isCursorAtBegin(node)) {
				t.error('cursor: <p>T|his <u>is a big test</u> text</p> should be false')
			}

			t.terrain.putCursorAt(t.terrain.dom.childNodes[0].childNodes[1].childNodes[0], 1);
			if (t.terrain.isCursorAtBegin(node)) {
				t.error('cursor: <p>This <u>i|s a big test</u> text</p> should be false')
			}

		});

		tests.add(function(t) {
			/**
			Check if enter key inside a root code candel and stop event
			*/
			t.terrain.dom.innerHTML = '<p><u>This is a big test text</u></p>';
			var node = t.terrain.dom.childNodes[0];

			t.terrain.putCursorAt(t.terrain.dom.childNodes[0].childNodes[0].childNodes[0], 0);
			if (!t.terrain.isCursorAtBegin(node)) {
				t.error('cursor: <p><u>|This is a big test text</u></p> should be true')
			}

			t.terrain.putCursorAt(t.terrain.dom.childNodes[0].childNodes[0].childNodes[0], 1);
			if (t.terrain.isCursorAtBegin(node)) {
				t.error('cursor: <p><u>T|his is a big test text</u></p> should be false')
			}

		});

	};


	test_pack['isCursorAtEnd'] = function(tests) {

		tests.prepare(instantiate_terrain);
		tests.restore(uninstantiate_terrain);

		tests.add(function(t) {
			/**
			Check if enter key inside a root code candel and stop event
			*/
			t.terrain.dom.innerHTML = '<p>This <u>is a big test</u> text</p>';
			var node = t.terrain.dom.childNodes[0];

			t.terrain.putCursorAt(t.terrain.dom.childNodes[0].childNodes[2], 5);
			if (!t.terrain.isCursorAtEnd(node)) {
				t.error('cursor: <p>This <u>is a big test</u> text|</p> should be true')
			}

			t.terrain.putCursorAt(t.terrain.dom.childNodes[0].childNodes[2], 4);
			if (t.terrain.isCursorAtEnd(node)) {
				t.error('cursor: <p>This <u>is a big test</u> tex|t</p> should be false')
			}

			t.terrain.putCursorAt(t.terrain.dom.childNodes[0].childNodes[1].childNodes[0], 13);
			if (t.terrain.isCursorAtEnd(node)) {
				t.error('cursor: <p>This <u>is a big test|</u> text</p> should be false')
			}

		});

		tests.add(function(t) {
			/**
			Check if enter key inside a root code candel and stop event
			*/
			t.terrain.dom.innerHTML = '<p><u><s>This is a big test text</s></u></p>';
			var node = t.terrain.dom.childNodes[0];

			t.terrain.putCursorAt(t.terrain.dom.childNodes[0].childNodes[0].childNodes[0].childNodes[0], 23);
			if (!t.terrain.isCursorAtEnd(node)) {
				t.error('cursor: <p><u><s>This is a big test text|</s></u></p> should be true')
			}

			t.terrain.putCursorAt(t.terrain.dom.childNodes[0].childNodes[0].childNodes[0].childNodes[0], 22);
			if (t.terrain.isCursorAtEnd(node)) {
				t.error('cursor: <p><u><s>This is a big test tex|t</s></u></p> should be false')
			}

		});

	};

	test_pack['Lists'] = function(tests) {

		tests.prepare(instantiate_terrain);
		tests.prepare(test_clean);
		tests.prepare(prepare_send_keys);
		tests.prepare(prepare_send_tab);
		tests.restore(uninstantiate_terrain);

		tests.add(function(t) {
			var initial = '<ul><li>one</li><li>two</li></ul>';
			var expected = '<ul><li>one</li><li>two</li></ul>';

			t.test_clean(initial, expected)
		});

		tests.add(function(t) {
			var initial = '<ol><li>one</li><li>two</li></ol>';
			var expected = '<ol><li>one</li><li>two</li></ol>';

			t.test_clean(initial, expected)
		});

		tests.add(function(t) {
			var initial = '<ol>  <li>a</li>  </ol>';
			var expected = '<ol><li>a</li></ol>';

			t.test_clean(initial, expected)
		});

		tests.add(function(t) {
			var initial = '<ol><li>a</li><span>b</span></ol>';
			var expected = '<ol><li>a</li></ol>';

			t.test_clean(initial, expected)
		});

		tests.add(function(t) {
			var initial = '<ol><li><span><u>one</u></span></li><li>two</li></ol>';
			var expected = '<ol><li><u>one</u></li><li>two</li></ol>';

			t.test_clean(initial, expected)
		});

		tests.add(function(t) {
			var initial = '<ul><li>one</li><ol><li>two</li></ol></ul>';
			var expected = '<ul><li>one</li><ol><li>two</li></ol></ul>';

			t.test_clean(initial, expected);
		});

		tests.add(function(t) {
			var initial = '<ul> <li>one</li>   <ol>   <li>two</li>  </ol>  </ul>';
			var expected = '<ul><li>one</li><ol><li>two</li></ol></ul>';

			t.test_clean(initial, expected);
		});

		tests.add(function(t) {
			var initial = '<ul> <li>one</li> <ol>  </ol> </ul>';
			var expected = '<ul><li>one</li></ul>';

			t.test_clean(initial, expected);
		});

		tests.add(function(t) {
			t.terrain.dom.innerHTML = '';
			var ul = document.createElement('ul');
			t.terrain.dom.appendChild(ul);

			var li_1 = document.createElement('li');
			li_1.innerHTML = 'one';
			ul.appendChild(li_1);

			var li_2 = document.createElement('li');
			li_2.innerHTML = 'two';
			ul.appendChild(li_2);

			t.terrain.putCursorAt(li_2);

			document.execCommand('indent', false, null);

			if ('<ul><li>one</li><ul><li>two</li></ul></ul>' != t.terrain.dom.innerHTML) {
				t.error('Ul structure should be: UL(LI,UL(LI))')
			}

		});

	};

	test_pack['insertImage()'] = function(tests) {

		tests.prepare(instantiate_terrain);
		tests.prepare(test_clean);
		tests.prepare(prepare_send_keys);
		tests.prepare(prepare_send_tab);
		tests.restore(uninstantiate_terrain);

		tests.add(function(t) {
			t.terrain.insertImage('http://example.com/image.png');

			var expected = '<p><br></p><figure><img src="http://example.com/image.png"><figcaption></figcaption></figure>';
			if (t.terrain.dom.innerHTML != expected) {
				t.error('Terrain innerHTML should be \'' + expected + '\'');
			}
		});

		tests.add(function(t) {
			t.terrain.insertImage('http://example.com/image.png');

			t.sendKey({
				keyCode: 13
			});

			var expected = '<p><br></p><figure><img src="http://example.com/image.png"><figcaption></figcaption></figure><p><br></p>';
			if (t.terrain.dom.innerHTML != expected) {
				t.error('Terrain innerHTML should be \'' + expected + '\'');
			}
		});

	};

	var do_tests = function() {

		var tests = new Tests();

		var time_start = new Date();

		for (var i in test_pack) {
			tests.log('Test pack: ' + i).classList.add('test-pack');

			tests.reset();
			tests.all = true;

			test_pack[i](tests);
		}

		var time_end = new Date();

		var time_delta = time_end.getTime() - time_start.getTime();

		tests.log('All tests time: ' + time_delta + 'ms');
	};


	window.addEventListener('load', do_tests, true);

})();
