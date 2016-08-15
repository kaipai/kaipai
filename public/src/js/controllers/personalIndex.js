define(['./module'], function(controllers) {
	//个人首页
	controllers.controller('indexPersonalController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.$on('$stateChangeSuccess', function() {
			$scope.$emit('globalTit', '个人中心首页');
			console.log('indexPersonal');

		});

		//end
	}]);
	

});