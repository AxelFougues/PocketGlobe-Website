/**
 * Created by Guibe on 19/02/2017.
 */


angular.module('pocketGlobe').factory('FolderModel', ['$http', 'API_URI', 'StorageService',
    function ($http, API_URI, StorageService) {
        var path = "/folder.php";
        return {
            create: function (name_folder, callback) {

                $http({
                    method: 'POST',
                    url: API_URI+path,
                    data: {id_user: StorageService.getUser().id_user, name_folder: name_folder, defaut: 0}
                }).then(function (resultat) {
                    if(!resultat.data) {
                        return callback(null);
                    }
                    return callback(resultat.data);
                })
            },
            createDefault: function (id_user, callback) {
                $http({
                    method: 'POST',
                    url: API_URI+path,
                    data: {"id_user": id_user, "name_folder": "Default", "defaut": 1}
                }).then(function (resultat) {
                    if(!resultat.data) {
                        return callback(null);
                    }
                    return callback(resultat.data);
                })
            },
            getFolders: function (callback) {
                $http({
                    method: 'GET',
                    url: API_URI+path+"/"+StorageService.getUser().id_user+"/getByUserId"
                }).then(function (folders) {
                    if(!folders.data) {
                        return callback(null);
                    }
                    return callback(folders.data);
                })
            },
            getByFolderId: function (id_folder, callback) {
                $http({
                    method: 'GET',
                    url: API_URI+path+"/"+StorageService.getUser().id_user+"/getByFolderId/"+id_folder
                }).then(function (folder) {
                    if(!folder.data) {
                        return callback(null);
                    }
                    return callback(folder.data);
                });
            }
        }

}]);