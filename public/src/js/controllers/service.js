define(['./module'], function(controllers) {
	//用户注册
	controllers.controller('infoServiceController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {

		$scope.$on('$stateChangeSuccess', function() {

			$scope.$emit('globalTit', '服务介绍');
			$scope.init(1);

		});
		$scope.init=function(id){
			$scope.curObj={
				title:'关于我们',
			}
		};
		//end

	}]);



});