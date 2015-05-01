window.app.factory 'Song', ['$resource', ($resource)->
  resource_options =
    query:
      method: 'GET'
      isArray: false
    recommend:
      method: 'GET'
      url: '/api/song/recommendation'
  return $resource '/api/song', {}, resource_options
]