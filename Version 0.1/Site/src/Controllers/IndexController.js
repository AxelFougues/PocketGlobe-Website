/**
 * Created by Vinchenzo on 16/02/2017.
 */


angular.module('pocketGlobe').controller('index', ['$scope', '$http',
    function ($scope, $http) {
        $scope.response = "";
        $http.get("API/user.php/getByNickname/Tita").then(function (response) {
            $scope.response = response.data;
        });

        $scope.Fiche = "Views/Fiche.html";
        $scope.Map = "Views/Map.html";

    }


]);