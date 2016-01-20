<?php

$id = $data['id'];
$node = &ControllerPage::$node;
$path = $node->getPath();
$url = &ControllerPage::$url;
$blog = Blog::ROW($id);

if ($blog !== null) {
	if (!count($url)) {
		if (Lib::editingMode()) {
			if (isset($_POST['ACTION']) && $_POST['ACTION']=='NEW_POST') {
				$new_post = $blog->newPost(true); // ALL
				$new_post->setTitle('New Post');
			}
		?>
			<table style="width:100%;">
				<tr>
					<th>[[COMPONENT name=Label text=Title id=2]]</th>
					<th>[[COMPONENT name=Label text=Autor id=3]]</th>
					<th>[[COMPONENT name=Label text=Comentarios id=4]]</th>
				</tr>

			<?php
				$articulos = $blog->getPosts(true);
				foreach ($articulos as $articulo) {
			?>
<tr>
	<td><a href="<?php echo $path.'article/'.$articulo->getUrl(); ?>?edit"><?=$articulo->getTitle()?></a></td>
	<td><?=$articulo->getAuthor()->getName() ?></td>
	<td>0</td>
</tr>
			<?php } ?>
</table>
<form action="" method="post">
	<input type="hidden" name="ACTION" value="NEW_POST">
	<input type="submit" value="New post">
</form>

<?php
		} else {
			$articulos = $blog->getPosts(true);
			foreach ($articulos as $articulo) {?>
<article class="post">
	<h3 class="title"><a href="<?php echo $path.'article/'.$articulo->getUrl(); ?>"><?=$articulo->getTitle()?></a></h3>
	<footer class="post">
		<span class="author">
		[[COMPONENT name=Label text='Escrito por ' id=5]]<?=$articulo->getAuthor()->getName()?>
		</span>
		<span class="date" title="<?=$articulo->getTime()?>">
		[[COMPONENT name=Label text=', Publicado el ' id=6]]<?=$articulo->getDate()?>
		</span>
	</footer>
	<div class="content">
		[[COMPONENT name=TreeDoc id=$articulo->getContent()->getId() error='Component "TreeDoc" does not exists.']]
	</div>
</article>

<?php			}
		}
	} else if ($url[0]=='article') {
		array_shift($url);
		
		$post = $blog->getPostByUrl($url[0]);
		if ($post!== null) {
			array_shift($url);
			$post_id = $post->getContent()->getId();
			?>
<article class="post">
	<h3 class="title"><a href="#" <? if (Lib::editingMode()) { echo 'contentEditable onblur="blog_save_post_title('.$post->getId().', this.innerHTML)" style="min-height:20px;"';}?>><?=$post->getTitle()?></a></h3>
	<footer class="post">
		<span class="author">
		[[COMPONENT name=Label text='Escrito por ' id=7]]<?=$post->getAuthor()->getName()?>
		</span>
		<span class="date" title="<?=$post->getTime()?>">
		[[COMPONENT name=Label text=', Publicado el ' id=8]]<?=$post->getDate()?>
		</span>
	</footer>
	<div class="content">
		[[COMPONENT name=TreeDoc id=$post_id error='Component "TreeDoc" does not exists.']]
	</div>
	<div class="comments">
		Comentarios<br>
	</div>
</article>

			<?php
		}
	}

}

?>