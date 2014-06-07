<?php
	if (isset($_GET['pregunta'])) {
		
		
		$pregunta = Forum::ROW($_GET['pregunta']);
			$pregunta->drawAnswers();
		
	} else {
?>
<div class="margen" style="text-align:center;">
	<input style="display:inline-block; margin:0; padding:7px; border:solid silver 1px; height:17px;"
		onkeyup="searchQuestions(this.value);"
	><button class="shadow-button" style="display:inline-block; height:33px; border-top-left-radius:0; border-bottom-left-radius:0;">Buscar</button>
</div>

<div id="no-hay-resultados" class="mensaje" style="display:none;">
	No hay ningún resultado acorde con la búsqueda.
</div>

<div id="preguntas">
	<?php
		
		$questions = Forum::SELECT("ResponseTo = 0 ORDER BY Timestamp DESC");
		foreach ($questions as $q)
			$q->drawQuestion();
	
	?>
</div>

<div class="margen" id="make-question">
	<div style="padding:8px; background-color:white;">
		<textarea id="make-question-text" style="border:none; padding:0; margin:0; width:100%;" onkeyup="ajustarTextarea(this);"></textarea>
	</div>
	<div style="margin-top:16px; text-align:right;">
		<button class="shadow-button" onclick="makeQuestion(document.getElementById('make-question-text').value); document.getElementById('make-question-text').value='';">Hacer una pregunta</button>
	</div>
</div>

<?php } ?>