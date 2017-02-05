/**
 * Created by Vinchenzo on 04/02/2017.
 */


'use strict';

var pocketGlobe = angular.module('pocketGlobe', [
    'test'
]);

var test = angular.module('test', []);

test.controller('test', ['$scope', '$http',
    function ($scope, $http) {
        $scope.response = "";
        $http.get("API/user.php/getByNickname/Titae").then(function (response) {
            $scope.response = response.data;
        });
    }
]);
