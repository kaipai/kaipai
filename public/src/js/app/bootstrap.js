/**
 * bootstraps angular onto the window.document node
 * NOTE: the ng-app attribute should not be on the index.html when using ng.bootstrap
 */
define([
    'require',
    'angular',
    './app',
    './routes',//路由
    'jquery',
    'widget',
    'layer',
    'bootstrap'
    
], function (require, ng) {
    'use strict';

    require(['domReady!'], function (document) {
        var animationSpeed = 500;     


        ng.bootstrap(document, ['app']);
    });
});
