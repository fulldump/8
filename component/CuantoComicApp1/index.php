
<div class="application" ng-app="project">
	<div class="slidemenu view" id="slidemenu1">
		<div class="background" onclick="document.getElementById('slidemenu1').classList.remove('slidemenu-open');"></div>
		<ul onclick="document.getElementById('slidemenu1').classList.remove('slidemenu-open');">
			<li click-link="/">Blogs</li>
		</ul>
	</div>

	<div class="toolbar" id="toolbar-main"><div class="icon" id="main-icon" onclick="document.getElementById('slidemenu1').classList.toggle('slidemenu-open');"></div><div class="title">CuantoComic+</div></div>
	<div ng-view></div>
</div>