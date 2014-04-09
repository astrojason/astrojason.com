/**
 * Created by jasonsylvester on 4/1/14.
 */
astroApp.config(['$routeProvider', function ($routeProvider) {
    $routeProvider.when('/', {controller: 'todayController'});
  }]);