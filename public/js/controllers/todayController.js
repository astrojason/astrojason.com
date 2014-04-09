/**
 * Created by jasonsylvester on 4/2/14.
 */
astroApp.controller('todayController', ['$scope', '$http', function($scope, $http){
  $http.get('/api/links/today').success(function(data) {
    $scope.guitar = data.guitar;
    $scope.photography = data.photography;
    $scope.programming = data.programming;
    $scope.links = data.links;
    $scope.categories = data.categories;
  });
  $scope.postpone = function(link) {
    $scope.removeLink(link);
  }
  $scope.removeLink = function(link) {
    switch(link.category) {
      case('Guitar'):
        $scope.guitar.splice(link, 1);
        break;
      case('Photography'):
        $scope.photography.splice(link, 1);
        break;
      case('Programming'):
        $scope.programming.splice(link, 1);
        break;
      default:
        $scope.links.splice(link, 1);
        break;
    }
  }
}]);