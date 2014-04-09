/**
 * Created by jasonsylvester on 4/2/14.
 */
astroApp.controller('nextBookController', ['$scope', '$http', function($scope, $http){
  $http.get('/api/book/next').success(function(data) {
    $scope.book = data.book;
  });
  $scope.read = function(book) {
    $http.get('/api/book/' + book.id + '/read').success(function(data){
      $scope.book = data.book;
    });
  }
}]);