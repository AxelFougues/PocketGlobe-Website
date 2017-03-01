/**
 * Created by Guibe on 19/02/2017.
 */


angular.module('pocketGlobe').factory('FicheModel', ['$http', 'StorageService', 'API_URI', 'FolderModel',
    function ($http, StorageService, API_URI, FolderModel) {
        var path="/fiche.php";
        return {
            create : function (id_folder, title, visited, callback) {
                $http({
                    method: 'POST',
                    url: API_URI+path,
                    data: {id_folder: id_folder, title: title, visited: visited}
                }).then(function (resultat) {
                    if(!resultat.data) {
                        return callback(null);
                    }
                    return callback(resultat.data);
                })
            },
            createByNameFolder: function (name_folder, title, visited, callback) {
                FolderModel.getByName(name_folder, function (folder) {
                    $http({
                        method: 'POST',
                        url: API_URI+path,
                        data: {id_folder: folder.id_folder, title: title, visited: visited}
                    }).then(function (resultat) {
                        if(!resultat.data) {
                            return callback(null);
                        }
                        return callback(resultat.data);
                    })
                })
            },
            getByUserId: function(id_user, callback) {
                id_user = id_user || StorageService.getUser().id_user;
                $http({
                    method: 'GET',
                    url: API_URI+path+"/getByUserId/"+id_user
                }).then(function (fiches) {
                    if(!fiches.data) {
                        return callback(null);
                    }
                    return callback(fiches.data);
                })
            },
            getByFolderId: function (id_folder, callback) {
                $http({
                    method: 'GET',
                    url: API_URI+path+"/getByFolderId/"+id_folder
                }).then(function (fiches) {
                    if(!fiches.data) {
                        return callback(null);
                    }
                    return callback(fiches.data);
                });
            },
            editFiche: function (fiche, edited, callback) {
                edited.id_fiche = fiche.id_fiche;
                $http({
                    method: 'PUT',
                    url: API_URI+path,
                    data: edited
                }).then(function (res) {
                    return callback(res.data=="EDITED");
                });
            },
            deleteFiche: function (fiche, callback) {
                $http({
                    method: 'DELETE',
                    url: API_URI+path+'/deleteFiche',
                    data: {id_fiche: fiche.id_fiche}
                }).then(function (res) {
                    return callback(res.data=="DELETED");
                });
            }
        }
}]);