<div id="panel-tables">
	<div class="margen">
		<div id="list-entities"></div>
	</div>
</div>
<div id="panel-editor" style="display:none;">
	<div style="position:absolute; top:0; left:0; right:0; text-align:center;" class="margen">
		<input id="query-input" onkeyup="loadTable(list_entities.getSelectedId(), document.getElementById('panel-editor-content'));"
		style="display:inline-block; margin:0; padding:7px; border:solid silver 1px; height:17px; width:300px;"
		><button class="shadow-button" onclick="loadTable(list_entities.getSelectedId(), document.getElementById('panel-editor-content'));"
		style="display:inline-block; height:33px; border-top-left-radius:0; border-bottom-left-radius:0;">Buscar</button>
	</div>
	<div class="margen panel-editor-content" id="panel-editor-content" style="position:absolute; top:64px; left:0; right:0; overflow:auto; bottom:80px;">
	</div>
	<div style="position:absolute; bottom:0; left:0; right:0;" class="margen">
		<div id="editor-footer">
			<button class="shadow-button" onclick="insertRow(list_entities.getSelectedId());">Nueva fila</button>
		</div>
	</div>
</div>

		<script type="text/javascript">
			var list_entities = new SimpleList();
			list_entities.setParentNode(document.getElementById('list-entities'));
			list_entities.setCallbackClick(list_entities_click);
			loadEntities();
		</script>