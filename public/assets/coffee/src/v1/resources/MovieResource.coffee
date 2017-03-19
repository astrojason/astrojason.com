angular.module('astroApp').factory 'MovieResource', ['$resource', ($resource)->

  resource_parameter_defaults =
    id: '@id'

  resource_options =

    populate:
      method: 'GET'
      params:
        id: 'populate'
      isArray: true
      transformResponse: (response)->
        wrappedResponse = angular.fromJson response
        wrappedResponse.movies

    query:
      method: 'GET'
      isArray: true
      transformResponse: (response)->
        wrappedResponse = angular.fromJson response
        wrappedResponse.movies.$total = wrappedResponse.total
        wrappedResponse.movies.$pages = wrappedResponse.pages
        wrappedResponse.movies
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