angular.module('astroApp').factory 'CreditResource', ['$resource', ($resource)->
  resource_options =

    add:
      method: 'PUT'
      transformResponse: (response)->
        wrappedResponse = angular.fromJson response
        if wrappedResponse.account
          wrappedResponse = wrappedResponse.account
        wrappedResponse

    disable:
      method: 'DELETE'

    query:
      method: 'GET'
      isArray: false

    report:
      method: 'GET'
      params:
        id: 'report'

    save:
      method: 'POST'
      params:
        id: '@id'

  return $resource '/api/credit/:id', {}, resource_options
]