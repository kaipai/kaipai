/*
baseController 通用控制器--初始化数据
20160223
wanrengang
365700955@qq.com
*/
define(['./module'], function(controllers) {
	controllers.controller('baseController', ['$scope', '$q', '$http', '$cookieStore', '$rootScope', '$state','$timeout', 
		function($scope, $q, $http, $cookieStore, $rootScope, $state,$timeout) {
		$scope.nickName = $cookieStore.get('nickName');
		$scope.token = $cookieStore.get('token');
	
		if($scope.token){
			$scope.hasLogin=true;
		}else{
			$scope.hasLogin=false;
		}
		console.log($scope.hasLogin);
		$scope.J_userType = $cookieStore.get('userType');
		$scope.globalTitEnd = "--开拍网";
		$scope.$on('globalTit', function(event, data) {
			$scope.globalTit = data + $scope.globalTitEnd;
			$scope.curNavFun();
		});

		//定位当前栏目
		$scope.curNavFun = function() {
			var J_curRoute = $state.current.name;

			var J_navtree = $('#leftBar dl');

			for (var i = 0; i < J_navtree.length; i++) {

				if ($(J_navtree[i]).data('route').indexOf($state.current.name) != -1) {
		
					$(J_navtree).removeClass('selected');
					$(J_navtree[i]).addClass('selected');
					$(J_navtree[i]).find('dt').addClass('selected')
					var J_threeNav=$(J_navtree[i]).find('dd');
					for(var j=0;j<J_threeNav.length;j++){

						if($(J_threeNav[j]).data('route').indexOf($state.current.name) != -1){
							$(J_threeNav).find('a').removeClass('selected');
							$(J_threeNav[j]).find('a').addClass('selected');
						}
					}
				}
			}

		}
		//全局错误提示start
		$scope.$on('globalErrTip', function(event, data) {
			$scope.globalErrTip = data;
		});
		$scope.clearTimeText = function(msg, time) {
			$timeout(function() {
				$scope.globalErrTip = msg;
			}, time);
		};
		//全局错误提示end
		//返回首页
		$scope.indexPage = function() {
				location.href = "/";
		}
			//退出登录
		$scope.loginOut = function() {
				$cookieStore.remove('token');
				$cookieStore.remove('nickName');
				location.href = "/#/account/login";
		}
			//分享
		$scope.bdShareFun = function() {
			var kpcuid = 'pc';
			//分享
			window._bd_share_config = {
				"common": {
					"bdSnsKey": {},
					"bdText": "",
					"bdMini": "2",
					"bdPic": "",
					"bdStyle": "0",
					"bdSize": "16",
					"bdUrl": current_url + "?source=" + kpcuid
				},
				"share": {},
				"image": {
					"viewList": ["qzone", "tsina", "tqq", "renren", "weixin"],
					"viewText": "分享到：",
					"viewSize": "16"
				},
				"selectShare": {
					"bdContainerClass": null,
					"bdSelectMiniList": ["qzone", "tsina", "tqq", "renren", "weixin"]
				}
			};
			with(document) 0[(getElementsByTagName('head')[0] || body).appendChild(createElement('script')).src = 'http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion=' + ~(-new Date() / 36e5)];

		}

		//end

	}]);
});