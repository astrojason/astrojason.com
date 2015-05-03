window.app.factory 'Link', ['$resource', ($resource)->
  resource_options =
    query:
      method: 'GET'
      isArray: false
  return $resource '/api/link', {}, resource_options
]