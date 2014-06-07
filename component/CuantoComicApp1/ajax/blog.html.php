<div class="view view-blog">

	<h1>This is the fucking blog #{{blog_id}}</h1>

	<div class="item" ng-repeat="item in items">
		<h1>{{item.title}}</h1>
		<p>{{item.text}}</p>
		<img src="{{item.image}}">
	</div>
</div>