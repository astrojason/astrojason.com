window.app.controller 'AdminController', ['$scope', '$http', ($scope, $http)->
  $scope.migrateLinks = ->
    migration_Promise = $http.get 'http://astrojason.herokuapp.com/api/links'
    migration_Promise.success (response)->
      sql = ''
      angular.forEach response.links, (link, i)->
        sql += 'Link::create(array(\'name\' => "' + $scope.stripChars(link.name) + '", \'link\' => "' + link.link + '", \'is_read\' => ' + (link.read ? 1 : 0) + ', \'category\' => "' + link.category + '", \'user_id\' => 1'
        if link.instapaper_id
          sql += ', \'instapaper_id\' => ' + link.instapaper_id
        sql += "));\n"
      $scope.importSql = sql

  $scope.stripChars = (value)->
    invalid_chars = [['"', ''], [';', ':']]
    angular.forEach invalid_chars, (char)->
      while(value.indexOf(char[0]) >= 0)
        value = value.replace(char[0], char[1])
    value
]