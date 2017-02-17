/**
 * Created by Vinchenzo on 16/02/2017.
 */


angular.module('pocketGlobe').controller('index', ['$scope', '$http', 'StorageService', '$mdDialog', 'UserModel',
    function ($scope, $http, StorageService, $mdDialog, UserModel) {
        $scope.user = {nickname: '', password: ''};
        $scope.storage = StorageService;
        /*$http.get("API/user.php/getByNickname/Tita").then(function (response) {
            $scope.response = response.data;
        });*/

        $scope.Fiche = "Views/Fiche.html";
        $scope.Map = "Views/Map.html";

        $scope.showDialog = function (action) {
            console.log(action);
            if(action == "login"){
                $mdDialog.show({
                    templateUrl: 'Views/Dialog/Login.html',
                    scope: $scope,
                    controllerAs: 'IndexController',
                    clickOutsideToClose: true,
                    preserveScope: true
                });
            } else if (action == "register") {

            } else {

            }
        };

        $scope.login = function () {
            console.log($scope.user.nickname);
            console.log($scope.user.password);
            UserModel.login($scope.user.nickname, $scope.user.password);
        };

    }


]);