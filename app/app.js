'use strict';

var app = angular.module('paymentGateway', [
    'ngRoute',
    'angular-md5',
    'paymentGateway.form',
    'paymentGateway.status'
]);

app.config(function ($routeProvider, $locationProvider) {
    $locationProvider.hashPrefix('!');
});

app.controller(
    'MakePaymentController', function ($scope, $http, $httpParamSerializer, $window, md5) {
        $scope.originalPayment = {
            productID: '',
            customerID: '',
            GPID: '',
            amount: 0,
            description: ''
        };
        $scope.payment = angular.copy($scope.originalPayment);
        $scope.makePayment = function () {
            var gpid = $scope.payment.GPID;
            var amount = $scope.payment.amount;
            var desc = $scope.payment.description;
            var productID = $scope.payment.productID;
            var customerID = $scope.payment.customerID;
            var transactionInitTime = new Date().toJSON("yyyy/MM/dd HH:mm:ss");
            var referenceID = md5.createHash(gpid + amount + desc + productID + customerID + transactionInitTime || '');
            referenceID = referenceID.slice(0, 5) + gpid;

            var onSuccess = function (data, status, headers, config) {
                var transactionID = data.data;
                var urlParameters = $httpParamSerializer({
                    tid: transactionID,
                    GPID: gpid,
                    amount: amount,
                    desc: desc,
                    referenceID: referenceID,
                    productID: productID,
                    customerID: customerID
                });
                $window.location.href = 'https://www.zenithbank.com.gh/api.globalpay/Test/PaySecure?' + urlParameters;
            };

            var onError = function (data, status, headers, config) {
                alert('Transaction failed!!! Please retry');
            };
            $http.post(
                'https://www.zenithbank.com.gh/api.globalpay/Test/SecurePaymentRequest',
                $httpParamSerializer({
                    GPID: gpid,
                    amount: amount,
                    desc: desc,
                    referenceID: referenceID,
                    productID: productID,
                    customerID: customerID
                }),
                {
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    }
                }).then(onSuccess, onError);
        }
    }
);

app.controller(
    'PageLoadDisplayController', function ($scope, $location) {
        $scope.initPage = function () {
            if ($location.path() === '/' || $location.path() === '') {
                var refId = '';
                var absUrl = $location.absUrl();
                if (absUrl.split('?').length > 1) {
                    var queryParams = absUrl.split('?')[1];
                    var keyValuePairs = queryParams.split('&');
                    for (var i = 0; i < keyValuePairs.length; i++) {
                        var keyValuePair = keyValuePairs[i].split('=');
                        console.log(keyValuePair);
                        if (keyValuePair.length > 1) {
                            if (keyValuePair[0] === 'ref') {
                                refId = keyValuePair[1];
                            }
                        }
                    }
                }
                if (refId === "") {
                    $location.path('/form');
                } else {
                    $location.path('/status').search('ref=' + refId);
                }
            }
        };
    }
);
