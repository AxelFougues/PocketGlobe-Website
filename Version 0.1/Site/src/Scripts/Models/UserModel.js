/**
 * Created by Vinchenzo on 17/02/2017.
 */


angular.module('pocketGlobe').factory('UserModel', ['$http', 'StorageService', 'API_URI',
    function ($http, StorageService, API_URI) {
        var path = "/user.php";
        return {
            login: function (nick, pass) {
                console.log(nick, pass);
                if(nick == "" || pass == "") {
                    console.log("Error, nickname or password empty");
                } else {

                    $http({
                        method: 'POST',
                        url: API_URI+path+'/login',
                        data: {'nickname': nick, 'password': pass}
                    }).then(function successCallback(response) {
                        console.log("SUCCESS");
                        console.log(response.data);
                    }, function errorCallback(response) {
                        console.log("ERROR");
                    });



                    /*$http.post(API_URI+path+"/login", {nickname: nick, password: pass}).then(function successCallback(response) {
                        console.log("SUCCESS");
                        console.log(response.data);
                    }, function errorCallback(response) {
                        console.log("ERROR");
                    });*/
                }
            }
        };
}]);