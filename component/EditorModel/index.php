		<div id="panel-tables">
			<div class="margen">
				<div id="list-entities"></div>
				<div class="bottom-bar">
					<div class="margen">
						<table style="width:100%">
							<tr>
								<td>Entidad</td>

								<td><input id="new-entity-name"></td>
							</tr>
						</table>
						<div style="text-align:right; padding-top:16px;">
							<button class="shadow-button button-1" onclick="createEntity(document.getElementById('new-entity-name').value); document.getElementById('new-entity-name').value = '';">Nuevo</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="panel-editor">
			<div class="margen" style="position:absolute; top:0; bottom:65px; left:0; right:0; overflow-y:auto;">
				<div style="width:340px;" id="model-editor">
					
				</div>
				<div class="margen" id="model-editor-properties" style="display:none; position:absolute; top:0; width:300px; left:356px; background-color:#EEEEEE; border-radius:0 8px 8px 8px;">
					<table>
						<tr>

							<td>Tipo</td>
							<td><select id="model-editor-properties-type"></select></td>
						</tr>
						<tr>
							<td colspan="2"><input type="checkbox" style="display:inline; width: auto;"> Sólo lectura</td>
						</tr>
						<tr>

							<td colspan="2"><input type="checkbox" style="display:inline; width: auto;"> Dependencia de datos</td>
						</tr>
					</table>
					<div style="margin-top:16px; text-align:right;">
						<button onclick="deleteAttribute(list_entities.getSelectedId(), document.getElementById('model-editor').last_selected.field_name);" class="shadow-button shadow-button-red">Eliminar atributo</button>
					</div>
				</div>

			</div>
			<div class="margen" style="position:absolute; bottom:0; left:0; right:0;">
				<div id="editor-footer">
					<button onclick="deleteEntity(list_entities.getSelectedId());" class="shadow-button" title="Vuelve a generar el código de la clase">Eliminar entidad</button>
					<button onclick="regenerateEntity(list_entities.getSelectedId());" class="shadow-button" title="Vuelve a generar el código de la clase">Regenerar entidad</button>
					<button onclick="regenerateAllEntities();" class="shadow-button" title="Vuelve a generar el código de todas las entidades">Regenerar todo</button>
				</div>

			</div>
		</div>
		<script type="text/javascript">
			var list_entities = new SimpleList();
			list_entities.setParentNode(document.getElementById('list-entities'));
			list_entities.setCallbackClick(loadEntity);
			loadEntities();
		</script>