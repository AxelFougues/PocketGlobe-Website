/**
 * Created by Vinchenzo on 17/02/2017.
 */


angular.module('pocketGlobe').controller('FicheController', ['$scope', '$mdDialog', 'StorageService', 'FicheModel', 'FolderModel', 'toastr',
    function ($scope, $mdDialog, StorageService, FicheModel, FolderModel, toastr) {

        $scope.folder_name = "";
        $scope.fiche = {folder: "", title: "", visited: 0};


        FolderModel.getFolders(function (folders) {
            $scope.folders = folders;
            FicheModel.getByFolderId($scope.folders[$scope.selectedIndex].id_folder, function (fiches) {
                $scope.fiches = fiches;
            });
        });

        $scope.majFiche = function () {
            FicheModel.getByFolderId($scope.folders[current].id_folder, function (fiches) {
                $scope.fiches = fiches;
            });
        };

        $scope.$watch('selectedIndex', function (current, old) {
            if ($scope.folders !== null && $scope.folders !== undefined) {
                if ($scope.folders[current] !== null && $scope.folders[current] !== undefined) {
                    FicheModel.getByFolderId($scope.folders[current].id_folder, function (fiches) {
                        $scope.fiches = fiches;
                    });
                }
                else
                    $scope.fiches = null;
            }
            $scope.selectedFiche = null;

        });

        $scope.selectedFiche = null;
        $scope.toggleFiche = function (index) {
            if($scope.selectedFiche === index) {
                $scope.selectedFiche = null;
            } else {
                $scope.selectedFiche = index;
            }
        };

        $scope.deleteFiche = function (fiche) {
            FicheModel.deleteFiche(fiche, function (isDeleted) {
                if(isDeleted) {
                    $scope.fiches.splice($scope.fiches.indexOf(fiche), 1);
                    return toastr.success("Fiche bien supprimée.", "Success");
                }
                return toastr.error("Fiche non supprimée.", "Erreur");
            });
        };

        $scope.editFiche = function () {
            if($scope.editedFiche.id_folder === $scope.originFiche.id_folder
                && $scope.editedFiche.title === $scope.originFiche.title
                && $scope.editedFiche.visited === $scope.originFiche.visited) {

                $mdDialog.hide();
                $scope.editedFiche = null;
                $scope.originFiche = null;
                return toastr.error("Rien de changé.", "Info");
            } else {
                $scope.edited = {};
                if($scope.editedFiche.id_folder !== $scope.originFiche.id_folder)
                    $scope.edited.id_folder = $scope.editedFiche.id_folder;
                if($scope.editedFiche.title !== $scope.originFiche.title)
                    $scope.edited.title = $scope.editedFiche.title;
                if($scope.editedFiche.visited !== $scope.originFiche.visited)
                    $scope.edited.visited = $scope.editedFiche.visited;


                FicheModel.editFiche($scope.originFiche, $scope.edited, function (isEdited) {
                    if(isEdited) {
                        $scope.selectedIndex = $scope.folders.findIndex(function (folder) {
                            return folder.id_folder == $scope.editedFiche.id_folder;
                        });
                        $mdDialog.hide();
                        for(var champ in $scope.edited) {
                            $scope.originFiche[champ] = $scope.edited[champ];
                        }
                        $scope.editedFiche = null;
                        $scope.originFiche = null;
                        return toastr.success("Changement bien effectués.", "Success");
                    } else {
                        $mdDialog.hide();
                        $scope.editedFiche = null;
                        $scope.originFiche = null;
                        return toastr.error("Erreur dans les changements des données.", "Erreur");
                    }
                });

            }

        };

        $scope.showEditDialog = function (fiche) {
            $scope.editedFiche = Object.create(fiche);
            $scope.originFiche = fiche;
            $mdDialog.show({
                templateUrl: 'Views/Dialog/EditFiche.html',
                scope: $scope,
                controllerAs: 'FicheController',
                clickOutsideToClose: true,
                preserveScope: true
            });

        };

        $scope.showDialog = function (param) {
            switch (param) {
                case 'fiche':
                    $mdDialog.show({
                        templateUrl: 'Views/Dialog/CreateFiche.html',
                        scope: $scope,
                        controllerAs: 'FicheController',
                        clickOutsideToClose: true,
                        preserveScope: true
                    });
                    break;
                case 'folder':
                    $mdDialog.show({
                        templateUrl: 'Views/Dialog/CreateFolder.html',
                        scope: $scope,
                        controllerAs: 'FicheController',
                        clickOutsideToClose: true,
                        preserveScope: true
                    });
                    break;
            }
        };

        $scope.createFolder = function () {
            if($scope.folder_name == "") {
                return toastr.error("Vous devez entrer un nom de dossier.", "Erreur");
            }
            FolderModel.create($scope.folder_name, function (resultat) {
                if (resultat == null) {
                    return toastr.error("Erreur dans la création de dossier.", "Erreur");
                }

                if(typeof resultat === "object") {
                    $mdDialog.hide();
                    $scope.folder_name = "";
                    $scope.folders.push(resultat);
                    $scope.selectedIndex = $scope.folders.length;
                    return toastr.success("Dossier bien créé.", "Succés");
                } else {
                    if(resultat.trim() == 'NAME') {
                        return toastr.error("Nom de dossier déjà utilisé.", "Erreur");
                    }
                }
            });
        };

        $scope.createFiche = function () {
            if($scope.fiche.folder == "" || $scope.fiche.title == "") {
                return toastr.error("Vous devez remplir le formulaire correctement.", "Erreur");
            }
            $scope.fiche.visited = ($scope.fiche.visited) ? 1 : 0;

            FicheModel.create($scope.fiche.folder.id_folder, $scope.fiche.title, $scope.fiche.visited, function (resultat) {
                if(resultat == null) {
                    return toastr.error("Erreur dans la création de la fiche.", "Erreur");
                }

                if(typeof resultat === "object") {
                    $mdDialog.hide();
                    $scope.selectedIndex = $scope.folders.indexOf($scope.fiche.folder);
                    if($scope.fiches !== null && $scope.fiches !== undefined)
                        $scope.fiches.push(resultat);
                    else
                        $scope.fiches = [resultat];
                    $scope.fiche = {folder: "", title: "", visited: 0};
                    return toastr.success("Fiche bien créée.", "Succès");
                } else if(resultat.trim() === 'Title') {
                    return toastr.$error("Titre déjà utilisé dans ce dossier.", "Erreur");
                }
            });
        }
}]);