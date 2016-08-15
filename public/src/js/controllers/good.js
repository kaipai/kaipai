define(['./module'], function(controllers) {
	//商品列表
	controllers.controller('listGoodController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.$on('$stateChangeSuccess', function() {
			$scope.$emit('globalTit', '商品列表');
			$('img.lazy').lazyload({
				placeholder: "/plugins/jquery.lazyload/img/grey.gif",
				effect: "fadeIn",
				threshold: 300
			});

		});
	}]);
	//专场拍卖会
	controllers.controller('historyGoodController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.$on('$stateChangeSuccess', function() {
			$scope.$emit('globalTit', '专场拍卖会');
			$('img.lazy').lazyload({
				placeholder: "/plugins/jquery.lazyload/img/grey.gif",
				effect: "fadeIn",
				threshold: 300
			});

		});
	}]);
	//商品详情
	controllers.controller('detialGoodController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.$on('$stateChangeSuccess', function() {
			$scope.$emit('globalTit', '商品详情');
			//产品详情图片展示
			CloudZoom.quickStart();
			_PageTag = "detail";
			console.log(_CurrentServerTime);
			$(".yomibox2").each(function() {
				console.log('dd');
				$(this).yomi(_CurrentServerTime, _PageTag)
			});
			$scope.initEvent();
		});

		$scope.initEvent=function(){
			$('#detailed-xx-down').delegate('[data-role="addres"]', 'click', function(event) {
					console.log('[data-role="addres"]');
				});
			//产品详情介绍的tab
			$('.detailed-xx-top').delegate('a', 'click', function() {
				$(this).addClass('on').siblings().removeClass('on');
				var J_index=$(this).index();
				console.log(J_index);
				var J_detailed_xx_down=$('#detailed-xx-down .shop-show-item');
				J_detailed_xx_down.hide();
				J_detailed_xx_down.eq(J_index).show();


				
			});
		};
		//end
	}]);


});