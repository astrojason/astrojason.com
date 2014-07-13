/**
 * Created by jasonsylvester on 4/5/14.
 */
app.controller('userController', ['$scope', '$http', function($scope, $http){
  var user = this;
  //TODO: Find out why this ran twice
  user.login = function() {
    var postData = {
      username: user.username,
      password: user.password
    }
    $http({
        url: '/api/login',
        method: "POST",
        data: postData
      })
    .success(function (data, status, headers, config) {
      console.log(data);
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
