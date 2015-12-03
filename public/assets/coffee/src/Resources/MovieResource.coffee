angular.module('astroApp').factory 'MovieResource', ['$resource', ($resource)->
  resource_options =
    query:
      method: 'GET'
      isArray: false
    widget:
      method: 'GET'
      url: '/api/movie/widget'
  return $resource '/api/movie', {}, resource_options
]