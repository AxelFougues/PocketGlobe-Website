/**
 * Created by Guibe on 18/02/2017.
 */


angular.module('pocketGlobe').controller('MenuController', ['$scope', '$rootScope', 'UserModel', 'FolderModel', 'FicheModel', 'toastr',
    function ($scope, $rootScope, UserModel, FolderModel, FicheModel, toastr) {


        $rootScope.menu = false;

        $rootScope.toggle = function () {
            return (scope.menu ? scope.menu = false : scope.menu = true);
        };

        $scope.logout = function () {
            UserModel.logout(function (res) {
                if (res) {
                    return toastr.success("Vous êtes déconnecté.", "A bientôt !");
                }
                return toastr.error("Erreur dans la déconnexion.", "Erreur");
            });
        };




}]);