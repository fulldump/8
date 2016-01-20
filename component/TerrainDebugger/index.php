<div component="TerrainTests">
	<div id="buttons">
		<button onclick="terrain.clean(); td.inspect()">clean()</button>
		<button onclick="terrain.insertCode(); td.inspect()">insertCode()</button>
		<button onclick="terrain.insertImage('data:image/png;base64,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'); td.inspect()">insertImage()</button>
		<button onclick="terrain.insertUl(); td.inspect()">insertUl()</button>
		<button onclick="terrain.insertOl(); td.inspect()">insertOl()</button>
		<button onclick="terrain.formatBold(); td.inspect()">Bold</button>
		<button onclick="terrain.formatItalic(); td.inspect()">Italic</button>
		<button onclick="terrain.formatUnderline(); td.inspect()">Underline</button>
		<button onclick="terrain.formatStrike(); td.inspect()">Strike</button>
		<br>
		<button onclick="terrain.formatBlock('H2'); td.inspect()">H1</button>
		<button onclick="terrain.formatBlock('H3'); td.inspect()">H2</button>
		<button onclick="terrain.formatBlock('H4'); td.inspect()">H3</button>
		<button onclick="terrain.formatBlock('H5'); td.inspect()">H4</button>
		<button onclick="terrain.formatBlock('H6'); td.inspect()">H5</button>
		<button onclick="terrain.highlightCodes(); td.inspect()">highlightCodes()</button>


	</div>

	<div id="my-document"></div>

	<div id="watcher"></div>

</div>

<script type="text/javascript">

	var td = new TerrainDebugger(document.getElementById('my-document'), document.getElementById('watcher'));

	var terrain = new Terrain(document.getElementById('my-document'));
	terrain.enableEditor();

	td.start();

</script>

<style type="text/css">
	[component="TerrainTests"] {

	}

	[component="TerrainTests"] #buttons {
		position: absolute;
		top: 0;
		left: 0;
		right: 0;
		height: 100px;
	}

	[component="TerrainTests"] [component="Terrain"] {
		position: absolute;
		top: 100px;
		left: 0;
		bottom: 0;
		width: 45%;
		overflow-y: auto;
	}

	[component="TerrainTests"] [component="TerrainDebugger"] {
		position: absolute;
		top: 100px;
		right: 0;
		bottom: 0;
		width: 45%;
		overflow-y: auto;
	}
</style>