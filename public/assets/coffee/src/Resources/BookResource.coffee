angular.module('astroApp').factory 'BookResource', ['$resource', ($resource)->
  resource_options =
    query:
      method: 'GET'
      isArray: false
    recommend:
      method: 'GET'
      url: '/api/book/recommendation/:category'

  return $resource '/api/book', {}, resource_options
]