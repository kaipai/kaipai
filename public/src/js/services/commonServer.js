/**
 *通用服务--常量、
 *wanrengang
 *20150703
 **/
define(['./module'], function(services) {
  //常量服务
  services.constant('apiKey', {
    //接口
   //'BASE_URL': 'http://192.168.18.207:8089/', //章
    //'BASE_URL': 'http://192.168.18.9:8089/', //唐勇
   'BASE_URL': ' http://114.55.36.109/api/v1/', //测试地址
    //'BASE_URL': 'http://121.43.116.1:80/youxuan/', //正式地址
    //'BASE_URL': 'http://myapi.com/',
    //方法
    'doLogin':'login/do-login',//登录
    'logout':'member/logout',//退出
    'getPicVerifyCode':'login/get-pic-verify-code',//获取图片验证码接口
    'getVerifyCode':'login/get-verify-code',//获取手机验证码
    'Register':'login/reg',//用户注册
    'memberProfile':'member/profile',//


    //常量

    'footText': '天搜版权所有',
  });

  var param = function(obj) {
    var query = '',
      name, value, fullSubName, subName, subValue, innerObj, i;

    for (name in obj) {
      value = obj[name];

      if (value instanceof Array) {
        for (i = 0; i < value.length; ++i) {
          subValue = value[i];
          fullSubName = name + '[' + i + ']';
          innerObj = {};
          innerObj[fullSubName] = subValue;
          query += param(innerObj) + '&';
        }
      } else if (value instanceof Object) {
        for (subName in value) {
          subValue = value[subName];
          fullSubName = name + '[' + subName + ']';
          innerObj = {};
          innerObj[fullSubName] = subValue;
          query += param(innerObj) + '&';
        }
      } else if (value !== undefined && value !== null)
        query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&';
    }

    return query.length ? query.substr(0, query.length - 1) : query;
  };


  services.factory('commonService', ['$q', '$http', '$rootScope', 'apiKey', '$timeout', 'comModal', function($q, $http, $rootScope, apiKey, $timeout, comModal) {


    return {
      comAjax: function(action, data) { //通用ajax请求
        var deferred = $q.defer();
        // Override $http service's default transformRequest
        $http.defaults.transformRequest = [function(data) {
          return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
        }];
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

        $http.post(apiKey.BASE_URL + action, data, {

        }).success(function(data, status, headers, config) {
          deferred.resolve(data);
        }).error(function(data, status, headers, config) {
          console.log(data);
          console.log(status);
          console.log(headers);
          console.log(config);
          deferred.reject(data, status, headers, config); // 声明执行失败，即服务器返回错误  
        });
        return deferred.promise;
      },
      comAjax2: function(action, data) { //通用ajax请求
        var deferred = $q.defer();
        // Override $http service's default transformRequest
        $http.defaults.transformRequest = [function(data) {
          return angular.isObject(data) && String(data) !== '[object File]' ? param(data) : data;
        }];
        $http.defaults.headers.post["Content-Type"] = "application/x-www-form-urlencoded";

        $http.post(action, data, {

        }).success(function(data, status, headers, config) {
          deferred.resolve(data);
        }).error(function(data, status, headers, config) {
          deferred.reject(data); // 声明执行失败，即服务器返回错误  
        });
        return deferred.promise;
      },
      jsonp: function(action, data) {
        var J_url = '?callback=JSON_CALLBACK'; //jsonp只能是get ，此处拼装url
        var deferred = $q.defer();
        for (var i in data) {
          J_url = J_url + '&' + i + '=' + data[i];
        }

        $http.jsonp(apiKey.BASE_URL + action + J_url, {

        }).success(function(data, status, headers, config) {
          deferred.resolve(data);
        }).error(function(data, status, headers, config) {
          deferred.reject(data); // 声明执行失败，即服务器返回错误  
        });
        return deferred.promise;
      },
      //通用弹窗关闭
      comPOPClose: function() {
        $(document.body).find('#compop_modal').modal('hide');
      },
      //提示弹窗
      Tip: function(opts) {
        var def = {
          title: '提示',
          tipCon: '我是提示内容',
          isShowIcon: true,
          iconNum: 0,
          autoHide: true,
          autoHideTime: 1500

        }
        opts = $.extend(def, opts);
        comModal.comPOP({
          title: opts.title,
          tipCon: opts.tipCon,
          isShowIcon: opts.isShowIcon,
          iconNum: opts.iconNum,
          autoHide: opts.autoHide,
          autoHideTime: opts.autoHideTime,
          type: 'tip'

        })
      },
      //alert
      Alert: function(opts) {
        var def = {
          title: '提示',
          tipCon: '我是alert内容',
          isShowIcon: false,
          iconNum: 0,
          autoHide: false,
          autoHideTime: 1500,
          callback: function() {
            $(document.body).find('#compop_modal').modal('hide');
          }

        }
        opts = $.extend(def, opts);
        comModal.comPOP({
          title: opts.title,
          tipCon: opts.tipCon,
          isShowIcon: opts.isShowIcon,
          iconNum: opts.iconNum,
          autoHide: opts.autoHide,
          autoHideTime: opts.autoHideTime,
          type: 'alert',
          btn: [{
            callback: function() {

            }
          }, {
            callback: function() {
              opts.callback();
            }
          }]

        })
      },
      //layer
      Layer: function(opts) {
        var def = {
          title: '提示',
          tipCon: '我是alert内容',
          isShowIcon: false,
          isLayer: true,
          urlTpl: '',
          alertWidth: 270,
          iconNum: 0,
          autoHide: false,
          autoHideTime: 1500,
          callback: function() {

          }

        }
        opts = $.extend(def, opts);

        comModal.comPOP({
          title: opts.title,
          tipCon: opts.tipCon,
          isLayer: opts.isLayer,
          alertWidth: opts.alertWidth,
          urlTpl: opts.urlTpl,
          isShowIcon: opts.isShowIcon,
          iconNum: opts.iconNum,
          autoHide: opts.autoHide,
          autoHideTime: opts.autoHideTime,
          btn: [{
            callback: function() {

            }
          }, {
            callback: function() {
              opts.callback();
            }
          }]

        })
      },
      Confirm: function(opts) {
        var def = {
          title: '提示',
          tipCon: '我是confirm内容',
          isShowIcon: false,
          iconNum: 0,
          autoHide: false,
          autoHideTime: 1500,
          callback_1: function() {
            $(document.body).find('#compop_modal').modal('hide');
          },
          callback_2: function() {
            $(document.body).find('#compop_modal').modal('hide');
          }

        }
        opts = $.extend(def, opts);
        comModal.comPOP({
          title: opts.title,
          tipCon: opts.tipCon,
          isShowIcon: opts.isShowIcon,
          iconNum: opts.iconNum,
          autoHide: opts.autoHide,
          autoHideTime: opts.autoHideTime,
          type: 'confirm',
          btn: [{
            callback: function() {
              opts.callback_1();
            }
          }, {
            callback: function() {
              opts.callback_2();
            }
          }]

        })
      }

    };

  }]);

  services.factory('comModal', ['$timeout', function($timeout) {
    return {
      /*通用弹窗
             isLayer:指定html,
             urlTpl:html路径
             alertWidth:alert宽度，
             title:alert标题，
             tipCon:alert内容,
             isShowIcon:是否显示alert icon
             iconNum:显示alert icon编号
             autoHide:自动关闭
             autoHideTime：自动关闭时间
             btn:两个按钮的回调方法

           */
      comPOP: function(opts) {
        var defaultOpts = {
          type: 'alert', //confirm、alert、laryer
          isLayer: false,
          urlTpl: '',
          alertWidth: 270,
          title: '提示',
          tipCon: '提示内容',
          isShowIcon: true,
          iconNum: 2,
          autoHide: false,
          autoHideTime: 1000,
          btn: [{
            callback: function() {
              $(document.body).find('#compop_modal').modal('hide');
            }
          }, {
            callback: function() {

            }
          }]
        }

        opts = $.extend({}, defaultOpts, opts);

        var J_compophtml = "<div class='modal fade' id='compop_modal' tabindex='-1' role='dialog'    aria-labelledby='myModalLabel' aria-hidden='true'>"
        J_compophtml = J_compophtml + "<div class='modal-dialog'><div class='modal-content'></div></div></div>";
        $(document.body).append(J_compophtml);

        var J_compopModal = $('#compop_modal');

        J_compopModal.find('.modal-dialog').css({
          width: opts.alertWidth + 'px'
        });
        //动态加载模板
        if (!opts.isLayer) {
          J_compopModal.find('.modal-content').load('tpl/modal/comPOP.html' + '?t=' + Math.random(1000), function() {
            //非指定html
          

            J_compopModal.find('.layui-layer-title').html(opts.title);
            //alert
            if (opts.type == 'alert') {

              J_compopModal.find('.layui-layer-btn a').eq(0).hide();
            }
            //tip
            if (opts.type == 'tip') {
              J_compopModal.find('.layui-layer-btn').hide();
            }
            //显示icon
            if (opts.isShowIcon) {
              J_compopModal.find('.layui-layer-content').addClass('layui-layer-padding').html('<i class="layui-layer-ico layui-layer-ico' + opts.iconNum + '"></i>' + opts.tipCon);
            } else {
              J_compopModal.find('.layui-layer-content').html(opts.tipCon);
            }
            //关闭按钮
            J_compopModal.find('.layui-layer-close').on('click', function(event) {
              event.preventDefault();
              J_compopModal.modal('hide');
            });
            //按钮事件
            J_compopModal.find('.layui-layer-btn a').on('click', function() {
              var ind = $(this).index();
              opts.btn[ind].callback();
              //$.PopClose(opts.mask);
            });
            //自动关闭
            if (opts.autoHide) {
              $timeout(function() {
                J_compopModal.modal('hide');
              }, opts.autoHideTime)
            }



          });
          //弹层
        } else {
          J_compopModal.find('.modal-content').load(opts.urlTpl + '?t=' + Math.random(1000), function() {
            //关闭按钮
            J_compopModal.find('.layui-layer-close').on('click', function(event) {
              event.preventDefault();
              J_compopModal.modal('hide');
            });
            J_compopModal.find('.layui-layer-title').html(opts.title);
            //处理回调
            //var ind = $(this).index();
            opts.btn[1].callback();
          })
        }

        J_compopModal.modal().on('shown.bs.modal', function() {

        }).on('hidden.bs.modal', function(e) {
          J_compopModal.remove();
        });
        return '';
      },
    }
  }]);

  services.factory('comCheck', ['$timeout', function($timeout) {
    return {
      
    }
  }]);

});