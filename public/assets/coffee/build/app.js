(function() {
  var app, spin_opts, spinner, target;

  spin_opts = {
    lines: 13,
    length: 20,
    width: 10,
    radius: 30,
    corners: 1,
    rotate: 0,
    direction: 1,
    color: '#FFF',
    speed: 1,
    trail: 60,
    shadow: false,
    hwaccel: false,
    className: 'spinner',
    zIndex: 2e9,
    top: '50%',
    left: '50%'
  };

  target = document.getElementById('overlay');

  spinner = new Spinner(spin_opts).spin(target);

  app = angular.module('astroApp', []);

  app.config([
    '$httpProvider', function($httpProvider) {
      $httpProvider.defaults.xsrfCookieName = 'csrftoken';
      $httpProvider.defaults.xsrfHeaderName = 'X-CSRFToken';
      $httpProvider.defaults.withCredentials = true;
      return $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
    }
  ]);

  app.controller('MasterController', [
    '$scope', function($scope) {
      $scope.initItems = 0;
      $scope.$on('initStarted', function() {
        $scope.init = true;
        return $scope.initItems++;
      });
      $scope.$on('initComplete', function() {
        $scope.initItems--;
        if ($scope.initItems === 0) {
          return $scope.init = false;
        }
      });
      return $scope.$on('errorOccurred', function(event, data) {
        $scope.show_error = true;
        return $scope.error_message = data;
      });
    }
  ]);

}).call(this);
