angular.module('astroApp').factory 'SongResource', ['$resource', ($resource)->
  resource_options =
    query:
      method: 'GET'
      isArray: false
    recommend:
      method: 'GET'
      url: '/api/song/recommendation'
  return $resource '/api/song', {}, resource_options
]