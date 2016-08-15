/**
 * bootstraps angular onto the window.document node
 * NOTE: the ng-app attribute should not be on the index.html when using ng.bootstrap
 */
define([
    'require',
    'angular',
    './app',
    './routesLogin',//路由
    // 'jquery'
    
], function (require, ng) {
    'use strict';

    /*
     * place operations that need to initialize prior to app start here
     * using the `run` function on the top-level module
     */

    require(['domReady!'], function (document) {
        /*var permissionList='';
        ng.element(document).ready(function() {  
          $.get('/api/UserPermission.json', function(data) {  
           permissionList = data;  

          });  
        }); */ 


        ng.bootstrap(document, ['app']);
    });
});
