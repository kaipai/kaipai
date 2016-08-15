
define(['./module'], function(services) {
  services.factory('accountService', [ 'apiKey','commonService', function(apiKey,commonService) {
    return {
      doLogin:function(data){
        return commonService.comAjax(apiKey.doLogin, data);
      },
      memberProfile:function(data){
        return commonService.comAjax(apiKey.memberProfile, data);
      },
      getPicVerifyCode:function(data){
        return commonService.comAjax(apiKey.getPicVerifyCode, data);
      },
      getVerifyCode:function(data){
        return commonService.comAjax(apiKey.getVerifyCode, data);
      },
      Register:function(data){
        return commonService.comAjax(apiKey.Register, data);
      },
      logout:function(data){
        return commonService.comAjax(apiKey.logout, data);
      },
      
    };

  }]);



});