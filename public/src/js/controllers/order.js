define(['./module'], function(controllers) {
	//订单管理
	controllers.controller('listOrderController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.curTit="订单管理";
		$scope.$on('$stateChangeSuccess', function() {
			$scope.$emit('globalTit', '订单管理');
			console.log('indexPersonal');

		});

		//end
	}]);
	//我要送拍
	controllers.controller('demyauctionOrderController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.curTit="我要送拍";
		$scope.$on('$stateChangeSuccess', function() {
			$scope.$emit('globalTit', '我要送拍');
			console.log('indexPersonal');

		});

		//end
	}]);

	//订单详情
	controllers.controller('detialOrderController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.$on('$stateChangeSuccess', function() {
			$scope.$emit('globalTit', '订单详情');


		});
		$scope.selectpayment = function() {
			layer.open({
				type: 1,
				title: '修改支付方式',
				area: ['826px', 'auto'],
				content: $('#selectpayment'),
				success: function(layero, index) {
					$(".payment_con>li").on("click", function() {
						$(".payment_con .xtj-balance-selected").each(function(i) {
							$(this).css("display", "none");
						});
						$(this).addClass("default_border").siblings("li").removeClass('default_border');
						$(this).find(".xtj-balance-selected").css("display", "inline-block");
					});
					//支付修改提交
					$("#paysubmit").click(function() {
						$(".payment_con>li").each(function(i) {
							if ($(this).find(".xtj-balance-selected").css("display") !== "none") {
								var id = $(this).data("payid");
								if (id !== 0 && id !== "" && id !== "undefined") {
									//putorder("payment", id);
								}
							}
							
						});
						layer.close(index);
					});


				}

			});
		};
		//修改地址
		$scope.selectaddress = function() {
			layer.open({
				type: 1,
				title: '修改地址',
				area: ['776px', 'auto'],
				content: $('#selectaddress'),
				success: function(layero, index) {
					$(".default_con>li").on("click", function() {
						$(".default_con .xtj-balance-selected").each(function(i) {
							$(this).css("display", "none");
						});
						$(this).addClass("default_border").siblings("li").removeClass('default_border');
						$(this).find(".xtj-balance-selected").css("display", "inline-block");
					});
					//地址修改提交
					$("#shipsubmit").click(function() {
						$(".default_con>li").each(function(i) {
							if ($(this).find(".xtj-balance-selected").css("display") !== "none") {
								var id = $(this).data("addressid");
								if (id !== 0 && id !== "" && id !== "undefined") {
									//putorder("shipping", id);
									console.log(id);

								}
							}
							
						});
						layer.close(index);
					});

				}

			});

		};
		//end
	}]);


});