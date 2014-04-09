/**
 * Created by jasonsylvester on 4/1/14.
 */

define(['modules/astroApp'], function (astroApp) {
  astroApp.controller('linksController', ['$scope', '$http', function ($scope, $http) {
      $http.get('/api/links').success(function(data) {
        $scope.links = data.links;
      });
    }]);
});