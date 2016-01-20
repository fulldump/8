<?php

	$background_height = 300;

	$slideshow = GraphicSlideshow::ROW($data['id']);

	$items = $slideshow->getAll();

	$num_items = count($items);

?>
<div class="GraphicSlideshow">
	<div class="GraphicSlideshow-points">
		<div class="GraphicSlideshow-points-frame">
			<?php for ($i=0; $i<$num_items; $i++) { ?><div class="GraphicSlideshow-point" onclick="fake(<?=$i?>);"></div><?php } ?>
		</div>
	</div>
	<div class="GraphicSlideshow-roll">
		<div class="GraphicSlideshow-roll-frame" id="fake">
			<?php foreach ($items as $item) {
			?><div id="GraphicSlideshow-item-<?php echo $item->getId(); ?>" class="GraphicSlideshow-item" style="background-image:url('/img/<?=$item->getBackground()->getId()?>/<?=$background_height?>')">

				<?php if (Lib::editingMode()): ?>
					<div class="GraphicSlideshow-imageSelector"><button class="shadow-button shadow-button-green" onclick="selectImage('<? echo $item->getId(); ?>')">Select image</button></div>
				<?php endif; ?>
				<div class="GraphicSlideshow-item-frame">
					<div class="GraphicSlideshow-title">[[COMPONENT name=Label id=$item->getTitle()->getId()]]</div>
					<div class="GraphicSlideshow-text">[[COMPONENT name=SimpleText id=$item->getText()->getId()]]</div>
				</div>
			</div><?php
			} ?>
		</div>
	</div>
</div>