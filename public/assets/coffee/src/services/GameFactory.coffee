window.app.factory 'Game', ['$resource', ($resource)->
  return $resource('/api/game', {}, {});
]