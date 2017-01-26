angular.module('astroApp').factory 'BookResource', ['$resource', ($resource)->
  resource_options =

    populate:
      method: 'GET'
      params:
        id: 'populate'

    query:
      method: 'GET'
      isArray: false

    recommend:
      method: 'GET'
      url: '/api/book/recommendation/:category'

    save:
      method: 'POST'
      params:
        id: '@id'

  return $resource '/api/book/:id', {}, resource_options
]