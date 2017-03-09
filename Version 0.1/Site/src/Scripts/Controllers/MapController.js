/**
 * Created by Guibe on 18/02/2017.
 */


angular.module('pocketGlobe').controller('MapController', ['$rootScope',
    function ($rootScope) {

        var mapOptions = {
            zoom: 4,
            center: new google.maps.LatLng(40.0000, -98.0000),
            scrollwheel: false
        };

        $rootScope.map = new google.maps.Map(document.getElementById('map'), mapOptions);

}]);