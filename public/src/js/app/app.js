/**
 * loads sub modules and wraps them up into the main module
 * this should be used for top-level module definitions only
 */
define([
    'angular',
    'ui-router',
    '../controllers/index'+_suffix,
    '../services/index'+_suffix,
    '../directives/index'+_suffix,
    'ngCookies',

], function (angular) {
    'use strict';
    return angular.module('app', [
        'ui.router',
        'app.controllers',
        'app.services',
        'app.directives',
        'ngCookies',
        
    ]);
});
