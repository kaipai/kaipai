define(['./module'], function(controllers) {
	controllers.controller('mainController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.$on('$stateChangeSuccess', function() {

			$scope.$emit('globalTit', '开拍网首页');
			$(".slideBoxx").slide({
				mainCell: ".bd ul",
				autoPlay: true
			});
			$(".slideBox").slide({mainCell:".bd ul",autoPlay:true,effect:"left",delayTime:700});
			//detail page bottom
			$(".luara-left").slide({
				titCell: ".adtit ol",
				mainCell: ".adcont ul",
				vis: 1,
				scroll: 1,
				effect: "leftLoop",
				interTime: 3500,
				delayTime: 375,
				autoPlay: true,
				autoPage: true
			});
			$(".qmenunav li").hover(function() {
				$(this).children(".sqm").css("width", Math.ceil($(this).find(".cc").val() / 11) * 120 + "px").animate({
					opacity: 'show'
				}, 400);
			}, function() {
				$(this).children(".sqm").css("width", Math.ceil($(this).find(".cc").val() / 11) * 120 + "px").animate({
					opacity: 'hide'
				}, 400);
			});

		});

	}]);
	controllers.controller('alertController', ['$scope', '$timeout', '$http', '$state', '$stateParams', 'commonService', function($scope, $timeout, $http, $state, $stateParams, commonService) {
		$scope.$on('$stateChangeSuccess', function() {

			console.log('alertController');
			$(document).on('click', '.alert-text', function() {
				console.log('alert-text');
				$.alert('Here goes alert text');
			});

			$(document).on('click', '.alert-text-title', function() {
				$.alert('Here goes alert text', 'Custom Title!');
			});

			$(document).on('click', '.alert-text-title-callback', function() {
				$.alert('Here goes alert text', 'Custom Title!', function() {
					$.alert('Button clicked!')
				});
			});

			$(document).on('click', '.alert-text-callback', function() {
				$.alert('Here goes alert text', function() {
					$.alert('Button clicked!')
				});
			});
		});

	}]);


});