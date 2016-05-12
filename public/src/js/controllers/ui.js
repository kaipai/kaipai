define(['./module'], function(controllers) {
	//商品列表
	controllers.controller('showUIController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.$on('$stateChangeSuccess', function() {
			console.log('showUIController');
			 CloudZoom.quickStart();
		});
	}]);
	


});