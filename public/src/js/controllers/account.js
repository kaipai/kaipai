define(['./module'], function(controllers) {
	//用户注册
	controllers.controller('accountRegisterController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.curObj = {
				mobile: '15068116642',
				picVerifyCode: '123'
		};
		var J_btn_getcode = $('#btn_getcode');
		$scope.$on('$stateChangeSuccess', function() {

			$scope.$emit('globalTit', '注册');
			$scope.reSend = true; //发送按钮状态

		});
		//验证
		$scope.checkSendFun = function() {

			if (!$.isMobile($scope.curObj.mobile)) {
				layer.open({
					type: 0,
					icon: 2,
					content: '请输入正确的手机号码',
					shadeClose: true,
					time: 2000
				});
				return
			};
			if ($.isNull($scope.curObj.picVerifyCode)) {
				layer.open({
					type: 0,
					icon: 2,
					content: '验证码不能为空',
					shadeClose: true,
					time: 2000
				});
				return
			};

			if ($scope.reSend) {
				console.log('0000');
			
				$scope.reSend = false; //禁止反复发送
				var html = '<b>5</b>秒重新获取';
				J_btn_getcode.addClass('btn_code_yz');
				J_btn_getcode.html(html);

				regCodeInterval = setInterval(function() {
					$scope.regCodeTimeinterval();
				}, 1000);

			}

		};
		//倒计时
		$scope.regCodeTimeinterval = function() {
			var currTime = J_btn_getcode.find('b').html();
			var newTime = parseInt(currTime) - 1;
			if (newTime) {
				J_btn_getcode.find('b').html(newTime);
			} else {
				clearInterval(regCodeInterval);
				J_btn_getcode.removeClass('btn_code_yz');
				J_btn_getcode.html('重新发送');
				$scope.reSend = true;
			}
		};



	}]);
	//找回密码
	controllers.controller('accountForgetpwdController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', 'accountService',
		function($scope, $timeout, $http, $state, $stateParams, commonService, accountService) {
			//测试数据
			$scope.curObj = {
				mobile: '13819149852',
				ValidCode: '123'
			};
			var J_btn_getcode = $('#btn_getcode');
			$scope.$on('$stateChangeSuccess', function() {
				$scope.$emit('globalTit', '找回密码');
				$scope.reSend = true; //发送按钮状态
				//$scope.submitFun();

			});


			$scope.getPicVerifyCode = function() {
				accountService.getPicVerifyCode().then(function(data) {
					console.log(data);
					if (data.flag > 0) {

					} else {
						layer.open({
							type: 0,
							'icon': 2,
							content: 'fdsa',
							time: 2000
						});
					}
				});
			};
			//验证
			$scope.checkSendFun = function() {
				if (!$.isMobile($scope.curObj.mobile)) {
					layer.open({
						type: 0,
						icon: 2,
						content: '请输入正确的手机号码',
						shadeClose: true,
						time: 2000
					});
					return
				};
				if ($.isNull($scope.curObj.ValidCode)) {
					layer.open({
						type: 0,
						icon: 2,
						content: '验证码不能为空',
						shadeClose: true,
						time: 2000
					});
					return
				};
				if ($scope.reSend) {
					console.log('0000');
					/*accountService.getPicVerifyCode($scope.codeObj).then(function(data) {
						if (data.status == 1) {
							$scope.reSend = false; //禁止反复发送
							var html = '已发送(<i>60</i>)';
							J_yzmBtn.html(html);
							J_yzmBtn.unbind();
							regCodeInterval = setInterval(function() {
								$scope.regCodeTimeinterval();
							}, 1000);
						} else {
							J_tip.html(data.showMessage);
						}

					});*/
					$scope.reSend = false; //禁止反复发送
					var html = '<b>5</b>秒重新获取';
					J_btn_getcode.addClass('btn_code_yz');
					J_btn_getcode.html(html);

					regCodeInterval = setInterval(function() {
						$scope.regCodeTimeinterval();
					}, 1000);

				}

			};
			//倒计时
			$scope.regCodeTimeinterval = function() {
				var currTime = J_btn_getcode.find('b').html();
				var newTime = parseInt(currTime) - 1;
				if (newTime) {
					J_btn_getcode.find('b').html(newTime);
				} else {
					clearInterval(regCodeInterval);
					J_btn_getcode.removeClass('btn_code_yz');
					J_btn_getcode.html('重新发送');
					$scope.reSend = true;
				}
			};



			$scope.submitFun = function() {


			};
			//end

		}
	]);

	//用户登录
	controllers.controller('accountLoginController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', '$cookieStore', 'accountService',
		function($scope, $timeout, $http, $state, $stateParams, commonService, $cookieStore, accountService) {
			$scope.$on('$stateChangeSuccess', function() {
				$scope.$emit('globalTit', '登录');
				//$scope.$emit('globalErrTip', '错误');
				//$.clearTimeText($('#errTip'), '15999');
				$scope.clearTimeText('', 1000);
				console.log('accountLoginController');
				$scope.curObj = {
						'mobile': '13819149852',
						'password': '123456'
					}
					//$scope.doLogin(data);
			});


			$scope.doLogin = function() {
				console.log();
				if (!$.isMobile($scope.curObj.mobile)) {
					$scope.$emit('globalErrTip', '请输入正确的手机号码！');
					$scope.clearTimeText('', 2000);
					return;
				};
				if ($.isNull($scope.curObj.password)) {
					$scope.$emit('globalErrTip', '密码不能为空！');
					$scope.clearTimeText('', 2000);
					return;
				};

				accountService.doLogin($scope.curObj).then(function(data) {
					console.log(data);
					if (data.flag > 0) {
						$cookieStore.put('token', data.data.token);
						$cookieStore.put('nickName', data.data.nickName);
						$state.go('indexPersonal');
					} else {
						$scope.$emit('globalErrTip', data.msg);
						$scope.clearTimeText('', 2000);
					}
				});
			};
			$scope.artList = function() {
				accountService.memberProfile().then(function(data) {
					console.log(data);
					if (data.flag > 0) {

					} else {

					}
				});
			};
			//end
		}
	]);


});