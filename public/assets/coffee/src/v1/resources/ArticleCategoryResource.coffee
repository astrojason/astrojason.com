angular.module('astroApp').factory 'ArticleCategoryResource', [
  '$resource'
  '$rootScope'
  '$log'
  '$http'
  '$uibModal'
  ($resource)->

    resource_parameter_defaults =
      id: '@id'

    resource_options =
      add:
        method: 'PUT'

    $resource '/api/article/category/:id', resource_parameter_defaults, resource_options
  ]