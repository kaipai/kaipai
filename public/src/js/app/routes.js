define(['./app'], function(app) {
    app.run(function($rootScope, $state, $stateParams, $http, $cookieStore, $location) {
        $rootScope.$on('$stateChangeStart', function(event, toState, toParams, fromState, fromParams) {
            var J_token = $cookieStore.get('token');
            if (toState.name.indexOf("Personal") != -1) {//权限控制
                if (J_token == undefined) {
                    event.preventDefault(); // 取消默认跳转行为
                    location.href = "/#/account/login";
                }
            };

        });
        $rootScope.go = function(path, pageAnimationClass) {
            if (typeof(pageAnimationClass) === 'undefined') { // Use a default, your choice
                $rootScope.pageAnimationClass = 'crossFade';
            } else { // Use the specified animation
                $rootScope.pageAnimationClass = pageAnimationClass;
            }

            if (path === 'back') { // Allow a 'back' keyword to go to previous page
                $window.history.back();
            } else { // Go to the specified path
                $location.path(path);
            }
        };

        $rootScope.$state = $state;
        $rootScope.$stateParams = $stateParams; //URL的参数

    });
    //拦截器

    //测试请求时间
    app.factory('timestampMarker', ['$rootScope', function($rootScope) {
        var timestampMarker = {
            request: function(config) {
                $rootScope.loading = true;
                config.requestTimestamp = new Date().getTime();
                return config;
            },
            response: function(response) {
                $rootScope.loading = false;
                response.config.responseTimestamp = new Date().getTime();
                return response;
            }
        };
        return timestampMarker;
    }]);
    return app.config(function($stateProvider, $urlRouterProvider, $httpProvider) {
        if (testNoline) {
            //禁止html缓存start
            if (!$httpProvider.defaults.headers.get) {
                $httpProvider.defaults.headers.get = {};
            }
            $httpProvider.defaults.headers.get['Cache-Control'] = 'no-cache';
            //禁止html缓存en
        }

        //默认首页
        $urlRouterProvider.otherwise('/index');
        $stateProvider
        //帐户设置
        .state('infoCustomerPersonal', {
                url: '/personal/customer/info',
                views: {
                    '': {
                        templateUrl: 'tpl/common/personalFrame.html',

                    },
                    'personalFrame@infoCustomerPersonal': {
                        templateUrl: 'tpl/personal/customer/info.html',
                        controller: 'infoCustomerController'

                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/customer' + _suffix,

                        'css!dist/styles/main/index.min',
                    ])
                },

        })
        .state('passwordCustomerPersonal', {
                url: '/personal/customer/password',
                views: {
                    '': {
                        templateUrl: 'tpl/common/personalFrame.html',

                    },
                    'personalFrame@passwordCustomerPersonal': {
                        templateUrl: 'tpl/personal/customer/password.html',
                        controller: 'passwordCustomerController'

                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/customer' + _suffix,

                        'css!dist/styles/main/index.min',
                    ])
                },

        })
        .state('mobileCustomerPersonal', {
                url: '/personal/customer/mobile',
                views: {
                    '': {
                        templateUrl: 'tpl/common/personalFrame.html',

                    },
                    'personalFrame@mobileCustomerPersonal': {
                        templateUrl: 'tpl/personal/customer/mobile.html',
                        controller: 'mobileCustomerController'

                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/customer' + _suffix,

                        'css!dist/styles/main/index.min',
                    ])
                },

        })
        .state('addressCustomerPersonal', {
                url: '/personal/customer/address',
                views: {
                    '': {
                        templateUrl: 'tpl/common/personalFrame.html',

                    },
                    'personalFrame@addressCustomerPersonal': {
                        templateUrl: 'tpl/personal/customer/address.html',
                        controller: 'addressCustomerController'

                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/customer' + _suffix,

                        'css!dist/styles/main/index.min',
                    ])
                },

        })
        .state('bankcardCustomerPersonal', {
                url: '/personal/customer/bankcard',
                views: {
                    '': {
                        templateUrl: 'tpl/common/personalFrame.html',

                    },
                    'personalFrame@bankcardCustomerPersonal': {
                        templateUrl: 'tpl/personal/customer/bankcard.html',
                        controller: 'bankcardCustomerController'

                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/customer' + _suffix,

                        'css!dist/styles/main/index.min',
                    ])
                },

        })
        .state('mybalanceCustomerPersonal', {
                url: '/personal/assets/mybalance',
                views: {
                    '': {
                        templateUrl: 'tpl/common/personalFrame.html',

                    },
                    'personalFrame@mybalanceCustomerPersonal': {
                        templateUrl: 'tpl/personal/customer/mybalance.html',
                        controller: 'mybalanceCustomerController'

                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/customer' + _suffix,

                        'css!dist/styles/main/index.min',
                    ])
                },

        })
        //反馈
        .state('feedbackListPersonal', {
                url: '/personal/feedback/list',
                views: {
                    '': {
                        templateUrl: 'tpl/common/personalFrame.html',

                    },
                    'personalFrame@feedbackListPersonal': {
                        templateUrl: 'tpl/personal/feedback/list.html',
                        controller: 'feedbackListController'

                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/customer' + _suffix,

                        'css!dist/styles/main/index.min',
                    ])
                },

        })
        .state('feedbackAddPersonal', {
                url: '/personal/feedback/myfeed',
                views: {
                    '': {
                        templateUrl: 'tpl/common/personalFrame.html',

                    },
                    'personalFrame@feedbackAddPersonal': {
                        templateUrl: 'tpl/personal/feedback/add.html',
                        controller: 'feedbackAddController'
                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/customer' + _suffix,

                        'css!dist/styles/main/index.min',
                    ])
                },

        })
        .state('collectionAuctionlistPersonal', {
                url: '/personal/collection/auctionlist',
                views: {
                    '': {
                        templateUrl: 'tpl/common/personalFrame.html',

                    },
                    'personalFrame@collectionAuctionlistPersonal': {
                        templateUrl: 'tpl/personal/collection/auctionlist.html',
                        controller: 'collectionAuctionController'
                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/customer' + _suffix,

                        'css!dist/styles/main/index.min',
                    ])
                },
        })
        .state('collectionShoplistPersonal', {
                url: '/personal/collection/shoplist',
                views: {
                    '': {
                        templateUrl: 'tpl/common/personalFrame.html',

                    },
                    'personalFrame@collectionShoplistPersonal': {
                        templateUrl: 'tpl/personal/collection/shoplist.html',
                        controller: 'collectionShoplistPersonal'
                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/customer' + _suffix,

                        'css!dist/styles/main/index.min',
                    ])
                },

        })
        //我要送拍
        .state('myauctionOrderPersonal', {
                url: '/order/myauction',
                views: {
                    '': {
                        templateUrl: 'tpl/common/personalFrame.html',

                    },
                    'personalFrame@myauctionOrderPersonal': {
                        templateUrl: 'tpl/personal/order/myauction.html',
                        controller: 'demyauctionOrderController'

                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/order' + _suffix,
                       
                    ])
                },

            })
        //订单详情
        .state('infoOrderPersonal', {
                url: '/order/info/{id:[0-9]{1,20}}',
                views: {
                    '': {
                        templateUrl: 'tpl/common/personalFrame.html',

                    },
                    'personalFrame@infoOrderPersonal': {
                        templateUrl: 'tpl/personal/order/info.html',
                        controller: 'detialOrderController'

                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/order' + _suffix,
                       
                    ])
                },

            })
        //个人中心首页

            .state('indexPersonal', {
                url: '/personal/index',
                views: {
                    '': {
                        templateUrl: 'tpl/common/personalFrame.html',

                    },
                    'personalFrame@indexPersonal': {
                        templateUrl: 'tpl/personal/index.html',
                        controller: 'indexPersonalController'

                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/personalIndex' + _suffix,

                        'css!dist/styles/main/index.min',
                    ])
                },

            })
            //首页
            .state('main', {
                url: '/index',
                views: {
                    '': {
                        templateUrl: 'tpl/common/frontFrame.html',

                    },
                    'frontFrame@main': {
                        templateUrl: 'tpl/index/index.html',
                        controller: 'mainController'

                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/main' + _suffix,
                        '/plugins/superSlide/jquery.SuperSlide.2.1.1.js',
                        'css!dist/styles/main/index.min',
                    ])
                },

            })
            .state('mainShop', {
                url: '/shop/index/{id:[0-9]{1,20}}',
                views: {
                    '': {
                        templateUrl: 'tpl/common/frontFrame.html',

                    },
                    'frontFrame@mainShop': {
                        templateUrl: 'tpl/shop/index.html',
                        controller: 'indexShopController'
                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/shop' + _suffix,
                        '/plugins/jquery.lazyload/jquery.lazyload.min.js',
                        'css!dist/styles/main/good.min',
                    ])
                },

            })
            //店铺街
            .state('listShop', {
                url: '/shop/adlist',
                views: {
                    '': {
                        templateUrl: 'tpl/common/frontFrame.html',

                    },
                    'frontFrame@listShop': {
                        templateUrl: 'tpl/shop/list.html',
                        controller: 'listShopController'
                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/shop' + _suffix,
                        '/plugins/jquery.lazyload/jquery.lazyload.min.js',
                        'css!dist/styles/main/good.min',
                    ])
                },

            })
            //专场主页
            .state('zhuanchangMain', {
                url: '/zhuanchang/index',
                views: {
                    '': {
                        templateUrl: 'tpl/common/frontFrame.html',
                    },
                    'frontFrame@zhuanchangMain': {
                        templateUrl: 'tpl/zhuanchang/main.html',
                        controller: 'zhuanchangMainController'
                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/zhuanchang' + _suffix,
                        _dir + '/js/common/widget' + _suffix,
                        '/plugins/superSlide/jquery.SuperSlide.2.1.1.js',
                        '/plugins/jquery.lazyload/jquery.lazyload.min.js',
                        'css!dist/styles/main/good.min',
                    ])
                }
            })
            //专场列表
            .state('zhuanchangList', {
                url: '/zhuanchang/list/{id:[0-9]{1,20}}',
                views: {
                    '': {
                        templateUrl: 'tpl/common/frontFrame.html',

                    },
                    'frontFrame@zhuanchangList': {
                        templateUrl: 'tpl/zhuanchang/list.html',
                        controller: 'zhuanchangListController'

                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/zhuanchang' + _suffix,
                        _dir + '/js/common/widget' + _suffix,
                        '/plugins/jquery.lazyload/jquery.lazyload.min.js',
                        'css!dist/styles/main/good.min',
                    ])
                }
            })
            //商品列表
            .state('goodList', {
                url: '/good/list',
                views: {
                    '': {
                        templateUrl: 'tpl/common/frontFrame.html',
                    },
                    'frontFrame@goodList': {
                        templateUrl: 'tpl/good/list.html',
                        controller: 'listGoodController'

                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/good' + _suffix,
                        '/plugins/jquery.lazyload/jquery.lazyload.min.js',
                        'css!dist/styles/main/good.min',
                    ])
                }
            })
            //商品详情
            .state('goodDetial', {
                url: '/good/detial/{productID:[0-9]{1,20}}',
                views: {
                    '': {
                        templateUrl: 'tpl/common/frontFrame.html',

                    },
                    'frontFrame@goodDetial': {
                        templateUrl: 'tpl/good/detial.html',
                        controller: 'detialGoodController'

                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/good' + _suffix,
                        '/plugins/CloudZoom/cloudzoom.js',
                        _dir + '/js/common/widget' + _suffix,
                        'css!dist/styles/main/good.min',
                    ])
                }
            })
            
            //服务信息
            .state('aboutService', {
                url: '/service/about',
                views: {
                    '': {
                        templateUrl: 'tpl/common/frontFrame.html',

                    },
                    'frontFrame@aboutService': {
                        templateUrl: 'tpl/service/info.html',
                        controller: 'infoServiceController'
                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/service' + _suffix,
                        'css!dist/styles/main/introduce.min',
                    ])
                }
            })
            //注册
            .state('userRegister', {
                url: '/account/register',
                templateUrl: 'tpl/account/register.html',

                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/account' + _suffix,
                        'css!dist/styles/main/account.min',
                    ])
                },
                controller: 'accountRegisterController'
            })
            //找回密码
            .state('userForgetpwd', {
                url: '/account/forgetpwd',
                templateUrl: 'tpl/account/forgetpwd.html',

                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/account' + _suffix,
                        _dir + '/js/services/account' + _suffix,
                        'css!dist/styles/main/account.min',
                    ])
                },
                controller: 'accountForgetpwdController'
            })
            //登录
            .state('userLogin', {
                url: '/account/login',
                templateUrl: 'tpl/account/login.html',
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/account' + _suffix,
                        _dir + '/js/services/account' + _suffix,
                        'css!dist/styles/main/account.min',
                    ])
                },
                controller: 'accountLoginController'
            })
            .state('map', {
                url: '/map',
                templateUrl: 'tpl/map.html',

            })
            .state('listOrderPersonal', {
                url: '/personal/orderList',
                views: {
                    '': {
                        templateUrl: 'tpl/common/personalFrame.html',

                    },
                    'personalFrame@listOrderPersonal': {
                        templateUrl: 'tpl/personal/order/list.html',
                        controller: 'listOrderController'

                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/order' + _suffix,

                        'css!dist/styles/main/index.min',
                    ])
                },

            })
            .state('uiShow', {
                url: '/ui/show',

                views: {
                    '': {
                        templateUrl: 'tpl/common/frontFrame.html',

                    },
                    'frontFrame@uiShow': {
                        templateUrl: 'tpl/ui/imgshow.html',
                        controller: 'showUIController'

                    },
                },
                resolve: {
                    load: loadDeps([
                        _dir + '/js/controllers/ui' + _suffix,
                        '/plugins/CloudZoom/cloudzoom.js',
                        'css!dist/styles/main/good.min',
                    ])
                }

            })

        ;

        /**
         * 加载依赖的辅助函数
         * @param deps
         * @returns {*[]}
         */
        function loadDeps(deps) {
            return [
                '$q',
                function($q) {
                    var def = $q.defer();
                    require(deps, function() {
                        def.resolve();
                    });
                    return def.promise;
                }
            ];
        }


    });

});