define(['./module'], function(controllers) {
	//商家店铺首页
	controllers.controller('indexShopController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.$on('$stateChangeSuccess', function() {
			$scope.$emit('globalTit', '商家店铺');
			$('img.lazy').lazyload({
				placeholder: "/plugins/jquery.lazyload/img/grey.gif",
				effect: "fadeIn",
				threshold: 300
			});
		});
		//end
	}]);
	//热推店铺街
	controllers.controller('listShopController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.$on('$stateChangeSuccess', function() {
			$scope.$emit('globalTit', '热推店铺街');
			$('img.lazy').lazyload({
				placeholder: "/plugins/jquery.lazyload/img/grey.gif",
				effect: "fadeIn",
				threshold: 300
			});
		});
		//end
	}]);


});