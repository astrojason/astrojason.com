angular.module('astroApp').factory 'SongResource', ['$resource', ($resource)->

  resource_parameter_defaults =
    id: '@id'

  resource_options =
    query:
      method: 'GET'
      isArray: true
      transformResponse: (response)->
        wrappedResponse = angular.fromJson response
        wrappedResponse.songs.$total = wrappedResponse.total
        wrappedResponse.songs.$pages = wrappedResponse.pages
        wrappedResponse.songs

      interceptor:
        response: (response)->
          response.resource.$pages = response.data.$pages
          response.resource.$total = response.data.$total
          response.resource

    recommend:
      method: 'GET'
      params:
        id: 'recommendation'

  return $resource '/api/song/:id', resource_parameter_defaults, resource_options
]