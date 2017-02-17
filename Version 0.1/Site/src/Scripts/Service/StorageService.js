/**
 * Created by Vinchenzo on 17/02/2017.
 */


angular.module('pocketGlobe').factory('StorageService', ['$localStorage',
    function ($localStorage) {
        return {
            getUser: function () {
                return $localStorage.user ? $localStorage.user : null;
            },
            setUser: function (user) {
                if($localStorage.getUser() == null)
                    $localStorage.user = user;
            }
        };
}]);