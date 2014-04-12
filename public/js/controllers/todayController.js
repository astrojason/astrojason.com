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
  $scope.postpone = function(link, index) {
    $scope.removeLink(link, index);
  }
  $scope.edit = function(link) {
    $scope.editing_link = link;
  }
  $scope.removeLink = function(link, index) {
    switch(link.category) {
      case('Guitar'):
        $scope.guitar.splice(index, 1);
        break;
      case('Photography'):
        $scope.photography.splice(index, 1);
        break;
      case('Programming'):
        $scope.programming.splice(index, 1);
        break;
      default:
        $scope.links.splice(index, 1);
        break;
    }
  }
  $scope.markAsRead = function(link, index) {
    $http.get('/api/link/' + link.id + '/read').success(function(data){
      if(data.success) {
        $scope.removeLink(link, index);
      } else {
        alert('There was a problem.')
      }
    });
  }
  $scope.save = function() {
    var save_data = {
      id: $scope.editing_link.id,
      name: $scope.editing_link.name,
      link: $scope.editing_link.link,
      category: $scope.editing_link.category,
      read: $scope.editing_link.read,
      instapaper_id: $scope.editing_link.instapaper_id
    };
    $http({method: 'PUT', url: '/api/link/', data: save_data}).success(function(data){
      //TODO: If category is changed, remove this link
    });
  }
}]);