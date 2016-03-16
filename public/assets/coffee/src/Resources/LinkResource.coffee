angular.module('astroApp').factory 'LinkResource', ['$resource', ($resource)->

  resource_parameter_defaults =
    id: '@id'

  resource_options =

    query:
      isArray: true
      method: 'GET'
      transformResponse: (response)->
        wrappedResponse = angular.fromJson response
        wrappedResponse.links.$pages = wrappedResponse.pages
        wrappedResponse.links.$total = wrappedResponse.total
        wrappedResponse.links
      interceptor:
        response: (response)->
          response.resource.$pages = response.data.$pages
          response.resource.$total = response.data.$total
          response.resource

    import:
      method: 'POST'
      params:
        id: 'import'

  return $resource '/api/link/:id', resource_parameter_defaults, resource_options
]