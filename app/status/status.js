'use strict';

var app = angular.module('paymentGateway.status', ['ngRoute']);

app.config(['$routeProvider', '$locationProvider', function ($routeProvider, $locationProvider) {
    $routeProvider.when('/status', {
        templateUrl: 'status/status.html'
    })
}]);

app.controller(
    'DisplayPaymentStatusController', function ($http, $scope, $location) {

        $scope.initStatus = function () {
            $scope.originalStatus = {
                datetime: '',
                description: '',
                refID: '',
                isPaymentSuccess: false,
                isPaymentPending: false,
                isPaymentFailed: false
            };
            $scope.status = angular.copy($scope.originalStatus);
            var onSuccess = function (data, status, headers, config) {
                $scope.status.status = data.data.status;
                $scope.status.datetime = data.data.dateTime;
                $scope.status.description = data.data.description;
                $scope.status.refID = data.data.refID;
                if ($scope.status.status === true) {
                    $scope.status.isPaymentSuccess = true;
                } else if ($scope.status.status === false) {
                    $scope.status.isPaymentPending = true;
                }
            };
            var onError = function (data, status, headers, config) {
                $scope.status.isPaymentFailed = true;
            };
            var refid = $location.search().ref;
            console.log(refid);
            var gpid = refid.slice(5);
            $http.get(
                'https://www.zenithbank.com.gh/api.globalpay/Test/confirmTestTransaction?' +
                'ref=' + refid +
                '&gpid=' + gpid,
                {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                }).then(onSuccess, onError);
        };
    }
);
