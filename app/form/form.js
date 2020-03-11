'use strict';

var app = angular.module('paymentGateway.form', ['ngRoute']);

app.config(['$routeProvider', '$locationProvider', function ($routeProvider, $locationProvider) {
    $routeProvider.when('/form', {
        templateUrl: 'form/form.html'
    })
}]);
