/**
 * Created by Vinchenzo on 16/02/2017.
 */


angular.module('pocketGlobe').controller('index', ['$scope', '$rootScope', '$http', 'StorageService', '$mdDialog', 'UserModel', 'FolderModel', 'FicheModel', 'toastr',
    function ($scope, $rootScope, $http, StorageService, $mdDialog, UserModel, FolderModel, FicheModel, toastr) {
        $scope.RegForm = {nickname: '', mail: '', password: ''};
        $scope.LogForm = {nickname: '', password: ''};
        $scope.storage = StorageService;

        $scope.Menu = "Views/Menu.html";
        $scope.Map = "Views/Map.html";
        $scope.Header = "Views/Header.html";
        $scope.Fiche = "Views/Fiche.html";


        $scope.showDialog = function (action) {
            if(action == "login"){
                $mdDialog.show({
                    templateUrl: 'Views/Dialog/Login.html',
                    scope: $scope,
                    controllerAs: 'IndexController',
                    clickOutsideToClose: true,
                    preserveScope: true
                });
            } else if (action == "register") {
                $mdDialog.show({
                   templateUrl: 'Views/Dialog/Register.html',
                    scope: $scope,
                    controllerAs: 'IndexController',
                    clickOutsideToClose: true,
                    preserveScope: true
                });
            }
        };

        $scope.login = function () {
            if($scope.LogForm.nickname == "" || $scope.LogForm.password == "") {
                return toastr.error("Il faut remplir tous les champs.", "Erreur");
            }

            UserModel.login($scope.LogForm.nickname, $scope.LogForm.password, function (user) {
                if(user == null) {
                    return toastr.error('Erreur login', 'Erreur');
                }
                $mdDialog.hide();
                StorageService.setUser(user);
                $scope.LogForm = {nickname: '', password: ''};
                return toastr.success("Bonjour "+user.nickname, 'Bienvenue');

            });
        };

        $scope.register = function () {
            if($scope.RegForm.nickname == "" || $scope.RegForm.mail == "" || $scope.RegForm.password == "") {
                return toastr.error("Il faut remplir tous les champs.", "Erreur");
            }

            UserModel.register($scope.RegForm.nickname, $scope.RegForm.mail, $scope.RegForm.password, function (resultat) {
                if(!resultat) {
                    $mdDialog.hide();
                    return toastr.error("Erreur dans l'enregistrement.", "Erreur");
                }


                switch (resultat.trim()) {
                    case 'DONE':
                        toastr.success("Vous êtes désormais enregistré !", "Bienvenue");
                        UserModel.getByNickname($scope.RegForm.nickname, function (user) {
                            if(user == null) {
                                return toastr.error("Erreur dans la création du dossier par défaut.", "Erreur");
                            }
                            FolderModel.createDefault(user.id_user, function (resultat) {
                                if(resultat == null) {
                                    return toastr.error("Erreur dans la création du dossier par défaut.", "Erreur");
                                }
                                /*
                                switch (resultat.trim()) {
                                    case 'DONE':
                                        break;
                                    case 'NAME':
                                        break;
                                }*/
                            })
                        });
                        $scope.RegForm = {nickname: '', mail: '', password: ''};
                        break;
                    case 'MAIL':
                        toastr.error("Ce mail est déjà utilisé.", "Erreur");
                        break;
                    case 'NICKNAME':
                        toastr.error("Ce pseudo est déjà utilisé.", "Erreur");
                        break;
                }
                $mdDialog.hide();
            });

        }

    }


]);