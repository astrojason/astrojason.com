angular.module('astroApp').factory 'GameResource', ['$resource', ($resource)->
  resource_options =
    query:
      method: 'GET'
      isArray: false
    recommend:
      method: 'GET'
      url: '/api/game/recommendation'
  return $resource '/api/game', {}, resource_options
]