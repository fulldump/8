<?php
$id = $data['id'];
$notes = Notes::ROW($id);
?>
<div class="notes notes-<?php echo $id; ?>">
<?php if ($notes !== null): ?>
	<?php
		// Busco la página
		$url = &ControllerAbstract::$url;
		$page = 0;
		if (count($url) && intval($url[0]) == $url[0] && $url[0]>0 )
			$page = intval($url[0]);
	?>

	<?php if (Lib::editingMode()): ?>
		<div style="text-align:right;">
			<button onclick="NotesMakeEntry('<?php echo $notes->getId(); ?>')" class="shadow-button shadow-button-blue">Nuevo</button>
		</div>
	<?php endif; ?>
	
	<?php
		list($entries, $has_prev, $has_next) = $notes->getEntries($page, Lib::editingMode());
		if ($has_prev || $has_next)
			array_shift($url);			
	?>
	<?php foreach ($entries as $entry): ?>
		<div class="entry entry-<?php echo $entry->getId()?>">
			<div class="entry-top"></div>
			<div class="entry-mid">
				<?php if(Lib::editingMode()): ?>
					<div style="float:right;">
						<?php if(!$entry->isPublished()): ?>
							<button onclick="NotesPublishEntry('<?php echo $entry->getId(); ?>')" class="shadow-button shadow-button-blue">Publicar</button>
						<?php endif; ?>
						<button onclick="NotesRemoveEntry('<?php echo $entry->getId(); ?>')" class="shadow-button shadow-button-red">Eliminar</button>
					</div>
				<?php endif; ?>
				<div class="title"><h2>[[COMPONENT name=Label id=$entry->getTitle()->getId()]]</h2></div>
				<div class="info">
					<?php /*echo $entry->getAuthor()->getName();*/ ?> <?php echo date('j / M / Y', $entry->getCreation()); ?>
				</div>
				<div class="content">[[COMPONENT name=SimpleText id=$entry->getContent()->getId()]]</div>
			</div>
			<div class="entry-bot"></div>
		</div>
	<?php endforeach; ?>

	<?php
		// Monto las rutas anterior y siguiente:
		$path = ControllerAbstract::$node->getPath();
		$n_prev = $page-1; if ($n_prev == 0) $n_prev = '';
		$n_next = $page+1;

		if (array_key_exists('edit', $_GET)) {
			$n_prev .= '?edit';
			$n_next .= '?edit';
		}
	?>
	<div class="pagination">
		<?php if($has_prev): ?>
			<a class="pagination-previous" href="<?php echo $path.$n_prev; ?>">Anterior</a>
		<?php endif; ?>
		<?php if($has_next): ?>
			<a class="pagination-next" href="<?php echo $path.$n_next; ?>">Siguiente</a>
		<?php endif; ?>
	</div>
<?php endif; ?>
</div>