define(['./module'], function(controllers) {
	//专场首页
	controllers.controller('zhuanchangMainController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.$on('$stateChangeSuccess', function() {
			$scope.$emit('globalTit', '拍卖专场首页');

			$(".slideBoxx").slide({
				mainCell: ".bd ul",
				autoPlay: true
			});
			
			$('img.lazy').lazyload({
				placeholder: "/plugins/jquery.lazyload/img/grey.gif",
				effect: "fadeIn",
				threshold: 300
			});
			_PageTag = "index";
			$(".yomibox2").each(function() {
				$(this).yomi(_CurrentServerTime, _PageTag)
			});
		});
		//end
	}]);
	//专场列表
	controllers.controller('zhuanchangListController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.$on('$stateChangeSuccess', function() {
			$scope.$emit('globalTit', '拍卖专场');
			$('img.lazy').lazyload({
				placeholder: "/plugins/jquery.lazyload/img/grey.gif",
				effect: "fadeIn",
				threshold: 300
			});
			_PageTag = "list";
			$(".yomibox2").each(function() {
				$(this).yomi(_CurrentServerTime, _PageTag)
			});
			
			//$scope.bdShareFun();分享暂时不用
		});
		
			//end
	}]);


});