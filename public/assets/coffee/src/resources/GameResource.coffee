angular.module('astroApp').factory 'GameResource', ['$resource', ($resource)->
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
      params:
        id: 'recommendation'

    save:
      method: 'POST'
      params:
        id: '@id'

  return $resource '/api/game/:id', {}, resource_options
]