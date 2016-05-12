define(['./module'], function(directives) {
	/*loading 效果*/
	directives.directive('butterbar', ['$rootScope',
		function($rootScope) {
			return {
				link: function(scope, element, attrs) {
					element.addClass('hide');

					$rootScope.$on('$routeChangeStart', function() {
						element.removeClass('hide');
					});

					$rootScope.$on('$routeChangeSuccess', function() {
						element.addClass('hide');
					});
				}
			};
		}
	]);
	//ng-repeat完之后执行一个function
	directives.directive('repeatDone', function() {
			return {
				link: function(scope, element, attrs) {
					if (scope.$last) { // 这个判断意味着最后一个 OK
						scope.$eval(attrs.repeatDone) // 执行绑定的表达式
					}
				}
			}
	});
		//通用测试
	directives.directive('hello', ['$rootScope',
		function($rootScope) {
			var option = {
				scope: {
					conf: '=',
					say: '&'
				},
				restrict: "AEC",
				template: "<div>Hello,{{viewTit}} Directive444fdwg<span ng-transclude></span></div>",
				replace: true,
				transclude: true,
				link: function(scope, elem, attrs) {
					elem.bind('click', function() {
						elem.css('background-color', 'red'); //
						scope.$apply(function() {
							scope.viewTit = "white";
							console.log(scope.conf);
							scope.say(); //调用方法
							//scope.
							//scope.sayHelloIsolated(); //调用当前scope方法
							//console.log(attrs.myatt); //获取属性
						});
					});
					elem.bind('mouseover', function() {
						elem.css('cursor', 'pointer');
					});
				}
			};
			return option;
		}
	]);


});