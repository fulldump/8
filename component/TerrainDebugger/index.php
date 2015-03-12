<div component="TerrainTests">
	<div id="buttons">
		<button onclick="terrain.clean(); td.inspect()">clean()</button>
		<button onclick="terrain.insertCode(); td.inspect()">insertCode()</button>
		<button onclick="terrain.formatBold(); td.inspect()">Bold</button>
		<button onclick="terrain.formatItalic(); td.inspect()">Italic</button>
		<button onclick="terrain.formatUnderline(); td.inspect()">Underline</button>
		<button onclick="terrain.formatStrike(); td.inspect()">Strike</button>
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
		width: 50%;
		overflow-y: auto;
	}

	[component="TerrainTests"] [component="TerrainDebugger"] {
		position: absolute;
		top: 100px;
		right: 0;
		bottom: 0;
		width: 49%;
		overflow-y: auto;
	}
</style>