window.app.controller 'AdminController', ['$scope', '$http', ($scope, $http)->
  $scope.migrateLinks = ->
    migration_Promise = $http.get 'http://astrojason.herokuapp.com/api/links'
    migration_Promise.success (data)->
      console.log data

]