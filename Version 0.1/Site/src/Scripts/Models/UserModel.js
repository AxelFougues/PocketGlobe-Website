/**
 * Created by Vinchenzo on 17/02/2017.
 */


angular.module('pocketGlobe').factory('UserModel', ['$http', 'StorageService', 'API_URI',
    function ($http, StorageService, API_URI) {
        var path = "/user.php";
        return {
            login: function (nickname, password, callback) {
                if(nickname == "" || password == "") {
                    return callback(null);
                } else {
                    $http({
                        method: 'POST',
                        url: API_URI+path+'/login',
                        data: {'nickname': nickname, 'password': password}
                    }).then(function (user) {
                        if(!user.data) {
                            return callback(null);
                        }
                        return callback(user.data);
                    });
                }
            },
            register: function (nickname, mail, password, callback) {
                $http({
                    method: 'POST',
                    url: API_URI+path+'/register',
                    data:
                    {
                        'nickname': nickname,
                        'mail': mail,
                        'password': password
                    }
                }).then(function (resultat) {
                    if(!resultat.data) {
                        return callback(null);
                    }
                    return callback(resultat.data);
                })
            },
            logout: function (callback) {
                return callback(StorageService.removeUser());
            },
            getByNickname: function (nickname, callback) {
                $http({
                    method: 'GET',
                    url: API_URI+path+"/getByNickname/"+nickname
                }).then(function (user) {
                    if(!user.data) {
                        return callback(null);
                    }
                    return callback(user.data);
                })
            }
        };
}]);