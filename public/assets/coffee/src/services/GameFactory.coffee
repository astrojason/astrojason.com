window.app.factory 'Game', ['$resource', ($resource)->
  resource_options =
    query:
      method: 'GET'
      isArray: false
    recommend:
      method: 'GET'
      url: '/api/game/recommendation'
  return $resource '/api/games', {}, resource_options
]