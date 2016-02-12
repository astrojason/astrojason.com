angular.module('astroApp').factory 'MovieResource', ['$resource', ($resource)->

  resource_parameter_defaults =
    id: '@id'

  resource_options =
    query:
      method: 'GET'
      isArray: true
      transformResponse: (response)->
        wrappedResponse = angular.fromJson response
        wrappedResponse.movies.$total = response.total
        wrappedResponse.movies.$pages = response.pages
      interceptor:
        response: (response)->
          response.resource.$pages = response.data.$pages
          response.resource.$total = response.data.$total
          response.resource
    widget:
      method: 'GET'
      params:
        id: 'widget'

  return $resource '/api/movie/:id', resource_parameter_defaults, resource_options
]