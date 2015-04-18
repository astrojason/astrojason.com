window.app.factory 'Song', ['$resource', ($resource)->
  return $resource('/api/songs', {}, {'query': {method: 'GET', isArray: false }});
]