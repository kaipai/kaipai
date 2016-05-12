//全局变量
define(['./app'], function (app) {
    app.constant('WAPAPI', {
        //接口
        'BASE_URL':'http://cgj.com/api/v1/',//基础地址

        //方法
        'getVerifyCode':'login/get-verify-code',
        'doLogin':'login/do-login'
        
        //常量
  
    });
});
