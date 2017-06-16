angular.module('astroApp').factory 'TaskResource', [
  '$resource'
  ($resource) ->
    resourceMethods =
      daily:
        method: 'GET'
        params:
          id: 'daily'

    $resource '/api/task/:id', {}, resourceMethods
]