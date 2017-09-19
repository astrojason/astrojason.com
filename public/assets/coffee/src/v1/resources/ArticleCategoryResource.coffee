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
      query:
        method: 'GET'
        isArray: true
        transformResponse: (response)->
          wrappedResponse = angular.fromJson response
          if wrappedResponse.categories
            wrappedResponse.categories
          else
            wrappedResponse

    $resource '/api/article/category/:id', resource_parameter_defaults, resource_options
  ]