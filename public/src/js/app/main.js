/**
 * configure RequireJS
 * prefer named modules to long paths, especially for version mgt
 * or 3rd party libraries
 */
require.config({
    baseUrl: '',
    paths: {
        'angular': './plugins/angular/angular.min',
        'ui-router': './plugins/angular-ui-router/angular-ui-router.min',
        'domReady': './plugins/requirejs-domready/domReady.min',
        'jquery': './plugins/jquery/jQuery-2.1.4.min',
        'bootstrap':'/plugins/bootstrap/js/bootstrap.min',
        'ngCookies': './plugins/ngCookies/angular-cookies', //本地存储
        'widget': './src/js/common/widget',//组件封装
        'layer': './plugins/layer/layer',//弹窗
        
    },
    urlArgs: "ver=" + version, //缓存
    //引用按需加载插件
    map: {
        '*': {
            css: './plugins/requirejs/css'+_suffix+'.js'
                //text : 'vendor/require/text'
        }
    },
    shim: {
        'angular': {
            exports: 'angular',
            init: function() {
                // ---------------------重要代码段！------------------------------
                // 应用启动后不能直接用 module.controller 等方法，否则会报控制器未定义的错误，
                // 见 http://stackoverflow.com/questions/20909525/load-controller-dynamically-based-on-route-group
                var _module = angular.module;
                angular.module = function() {
                    var newModule = _module.apply(angular, arguments);
                    if (arguments.length >= 2) {
                        newModule.config([
                            '$controllerProvider',
                            '$compileProvider',
                            '$filterProvider',
                            '$provide',
                            function($controllerProvider, $compileProvider, $filterProvider, $provide) {
                                newModule.controller = function() {
                                    $controllerProvider.register.apply(this, arguments);
                                    return this;
                                };
                                newModule.directive = function() {
                                    $compileProvider.directive.apply(this, arguments);
                                    return this;
                                };
                                newModule.filter = function() {
                                    $filterProvider.register.apply(this, arguments);
                                    return this;
                                };
                                newModule.factory = function() {
                                    $provide.factory.apply(this, arguments);
                                    return this;
                                };
                                newModule.service = function() {
                                    $provide.service.apply(this, arguments);
                                    return this;
                                };
                                newModule.provider = function() {
                                    $provide.provider.apply(this, arguments);
                                    return this;
                                };
                                newModule.value = function() {
                                    $provide.value.apply(this, arguments);
                                    return this;
                                };
                                newModule.constant = function() {
                                    $provide.constant.apply(this, arguments);
                                    return this;
                                };
                                newModule.decorator = function() {
                                    $provide.decorator.apply(this, arguments);
                                    return this;
                                };
                            }
                        ]);
                    }
                    return newModule;
                };
            }
        },
      

        'ngCookies': {
            exports: 'ngCookies',
            deps: ['angular']
        },
        'jquery': {
            exports: 'jquery'
        },
        'bootstrap': {
            exports: 'bootstrap',
            deps: ['jquery']
        },
        'widget': {
            exports: 'widget',
            deps: ['jquery']
        },
        'layer': {
            exports: 'layer',
            deps: ['jquery']
        },
        'ui-router': {
            deps: ['angular']
        },
    },
    deps: [
        './'+_dir+'/js/app/bootstrap'+_suffix
    ]
});