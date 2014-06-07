<div class="view view-blogs">
	<ul>
		<li ng-repeat="blog in list" click-link="/blog/{{blog.id}}">
			<div class="box" style="background-image: url('/img/{{blog.id_image}}')">
				<div class="title">{{blog.name}}</div>
			</div>
		</li>
	</ul>
</div>