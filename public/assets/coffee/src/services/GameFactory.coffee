window.app.factory 'Game', ['$resource', ($resource)->
  return $resource('/api/game', {}, {'query': {method: 'GET', isArray: false }});
]