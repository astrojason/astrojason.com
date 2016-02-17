angular.module('astroApp').factory 'SongResource', ['$resource', ($resource)->

  resource_options =
    query:
      method: 'GET'
      isArray: true
      transformResponse: (response)->
        wrappedResponse = angular.fromJson response
        wrappedResponse.songs.$total = response.total
        wrappedResponse.songs.$pages = response.pages
        wrappedResponse.songs

      interceptor:
        response: (response)->
          response.resource.$pages = response.data.$pages
          response.resource.$total = response.data.$total
          response.resource

    recommend:
      method: 'GET'
      url: '/api/song/recommendation'

  return $resource '/api/song', {}, resource_options
]