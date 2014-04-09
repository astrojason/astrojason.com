/**
 * Created by jasonsylvester on 4/5/14.
 */
astroApp.controller('loginController', ['$scope', '$http', function($scope, $http){
  //TODO: Find out why this ran twice
  $scope.login = function() {
    var postData = {
      username: $scope.username,
      password: $scope.password
    }
    $http({
        url: '/api/login',
        method: "POST",
        data: postData
      })
    .success(function (data, status, headers, config) {
      if(data.success) {
        location.href = '/';
      } else {
        //TODO: Handle login error
      }
    })
    .error(function (data, status, headers, config) {
      //TODO: Handle error
    });
  }
}]);
