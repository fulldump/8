
angular.module('project', ['ngRoute'])

.config(function($routeProvider) {
  $routeProvider
    .when('/', {
      controller:'BlogsCtrl',
      templateUrl:'[[AJAX name=blogs.html]]'
    })
    .when('/blog/:blog_id', {
      controller:'BlogCtrl',
      templateUrl:'[[AJAX name=blog.html]]'
    })
    .otherwise({
      redirectTo:'/'
    });
})

.controller('BlogsCtrl', function($scope, $http) {
	var timestamp = 0;

	$scope.list = [];

	$scope.refresh = function() {
		$http({
			url: '/__ajax__/v1/get_blogs',
			method: "POST",
			data: {"foo":"bar"}
		}).success(function(data, status, headers, config) {
			if (data.response_code == 'ok') {
				$scope.list = data.list;
			}
		}).error(function(data, status, headers, config) {
			console.log("FAIL");
		});	
	};

	$scope.refresh();

})

.controller('BlogCtrl', function($scope, $http, $routeParams) {

	$scope.blog_id = $routeParams.blog_id;

	$scope.items = [];

	$scope.refresh = function() {
		$http({
			headers: {'Content-Type': 'application/x-www-form-urlencoded'},
			url: '/__ajax__/v1/get_blog_items',
			method: 'POST',
			data: { 'blog_id' : $scope.blog_id},
			transformRequest: function(obj) {
				var str = [];
				for(var p in obj)
				str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
				return str.join("&");
			},
		}).success(function(data, status, headers, config) {
			console.log(data);
			if (data.response_code == 'ok') {
				$scope.items = data.items;
			}
		}).error(function(data, status, headers, config) {
			console.log("FAIL");
		});	
	};

	$scope.refresh();

})

// Click to navigate
// similar to <a href="#/partial"> but hash is not required, 
// e.g. <div click-link="/partial">
.directive('clickLink', ['$location', function($location) {
    return {
        link: function(scope, element, attrs) {
            element.on('click', function() {
                scope.$apply(function() {
                    $location.path(attrs.clickLink);
                });
            });
        }
    }
}]);