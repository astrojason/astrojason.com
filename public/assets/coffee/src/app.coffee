angular.module 'astroApp', ['ngMessages', 'ngResource', 'ngAnimate']

angular.module('astroApp').config(['$httpProvider', '$locationProvider', ($httpProvider, $locationProvider)->
  $httpProvider.defaults.xsrfCookieName = 'csrftoken'
  $httpProvider.defaults.xsrfHeaderName = 'X-CSRFToken'
  $httpProvider.defaults.withCredentials = true
  $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded'
  $locationProvider.html5Mode true
])