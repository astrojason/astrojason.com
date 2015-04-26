window.app.factory 'Song', ['$resource', ($resource)->
  return $resource('/api/song', {}, {'query': {method: 'GET', isArray: false }});
]