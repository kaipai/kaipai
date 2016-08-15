define(['./module'], function(controllers) {
	//账户设置
	controllers.controller('infoCustomerController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.curTit = "基本资料";
		$scope.$on('$stateChangeSuccess', function() {
			$scope.$emit('globalTit', '基本资料');

		});
		//end
	}]);
	//修改密码
	controllers.controller('passwordCustomerController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.curTit = "修改密码";
		$scope.$on('$stateChangeSuccess', function() {
			$scope.$emit('globalTit', '修改密码');

		});
		//end
	}]);
	//绑定手机
	controllers.controller('mobileCustomerController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.curTit = "绑定手机";
		$scope.$on('$stateChangeSuccess', function() {
			$scope.$emit('globalTit', '绑定手机');

		});
		//end
	}]);
	//收货地址
	controllers.controller('addressCustomerController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.curTit = "收货地址";
		$scope.$on('$stateChangeSuccess', function() {
			$scope.$emit('globalTit', '收货地址');

		});
		//end
	}]);
	//我的银行卡
	controllers.controller('bankcardCustomerController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.curTit = "我的银行卡";
		$scope.$on('$stateChangeSuccess', function() {
			$scope.$emit('globalTit', '我的银行卡');

		});
		//end
	}]);
	//我的余额
	controllers.controller('mybalanceCustomerController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.curTit = "我的余额";
		$scope.$on('$stateChangeSuccess', function() {
			$scope.$emit('globalTit', '我的余额');

		});
		//end
	}]);
	
	//我的反馈
	controllers.controller('feedbackListController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.curTit = "我的反馈";
		$scope.$on('$stateChangeSuccess', function() {
			$scope.$emit('globalTit', '我的反馈');


		});
		//end
	}]);
	//我要反馈
	controllers.controller('feedbackAddController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.curTit = "我要反馈";
		$scope.$on('$stateChangeSuccess', function() {
			$scope.$emit('globalTit', '我要反馈');
			$(".cause>li").on("click", function() {
				$(".cause .selected").each(function(i) {
					$(this).addClass("hide");
				});
				$(this).addClass("cause-border").siblings("li").removeClass('cause-border');
				$(this).find(".selected").removeClass("hide");
			});
		});
		//end
	}]);
	//拍品收藏
	controllers.controller('collectionAuctionController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.curTit = "拍品收藏";
		$scope.$on('$stateChangeSuccess', function() {
			$scope.$emit('globalTit', '拍品收藏');

		});
		$(function() {
			$('.wdsc_con .div1 .hidden_0').hide();
			$('.wdsc_con li .Im0 i').click(function() {
				$(this).parent().next().show();
				$('.wdsc_con li .Im0 i').hide();
			});

			$('.wdsc_con li .Im0 i').hide();
			$('.wdsc_con .div1 li').hover(function() {
				$(this).find('i').show();
			}, function() {
				$(this).find('i').hide();
			});

			//取消
			$('.wdsc_con li .click_cancel').click(function() {
				$(this).parent().parent().hide();
			});

			//删除收藏
			$('.wdsc_con li .click_delete').click(function() {
				$.ajax({
					type: "POST",
					async: false,
					url: "/Collection/CancelCollection/",
					data: {
						"id": $(this).data('goodsid'),
						"type": "1",
						"__RequestVerificationToken": $('[name=__RequestVerificationToken]').val()
					},
					success: function(result) {
						if (result.status === 'success') {
							window.location.reload();
						} else if (result.status === 'nologin') {
							window.location.href = "/personal/account/login/";
						} else if (result.status === 'fail') {
							window.yishu.confirm(result.message, window.yishu.confirm.typeEnum.error);
						} else {
							window.location.href = result.url;
						}
					}
				});

			});
		});

		//end
	}]);
	//店铺收藏
	controllers.controller('collectionShoplistPersonal', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.curTit = "店铺收藏";
		$scope.$on('$stateChangeSuccess', function() {
			$scope.$emit('globalTit', '店铺收藏');

		});
		$(function() {
			$('.wdsc_con .div1 .hidden_0').hide();
			$('.wdsc_con li .Im0 i').click(function() {
				$(this).parent().next().show();
				$('.wdsc_con li .Im0 i').hide();
			});

			$('.wdsc_con li .Im0 i').hide();
			$('.wdsc_con .div1 li').hover(function() {
				$(this).find('i').show();
			}, function() {
				$(this).find('i').hide();
			});

			//取消
			$('.wdsc_con li .click_cancel').click(function() {
				$(this).parent().parent().hide();
			});

			//删除收藏
			$('.wdsc_con li .click_delete').click(function() {
				$.ajax({
					type: "POST",
					async: false,
					url: "/Collection/CancelCollection/",
					data: {
						"id": $(this).data('goodsid'),
						"type": "1",
						"__RequestVerificationToken": $('[name=__RequestVerificationToken]').val()
					},
					success: function(result) {
						if (result.status === 'success') {
							window.location.reload();
						} else if (result.status === 'nologin') {
							window.location.href = "/personal/account/login/";
						} else if (result.status === 'fail') {
							window.yishu.confirm(result.message, window.yishu.confirm.typeEnum.error);
						} else {
							window.location.href = result.url;
						}
					}
				});

			});
		});
		//end
	}]);



});