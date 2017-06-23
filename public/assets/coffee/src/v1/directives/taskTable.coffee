angular.module('astroApp').directive 'taskTable', ->
  templateUrl: 'templates/task-table'
  restrict: 'E'
  controller: 'BookController'
  scope:
    tasks: '='
