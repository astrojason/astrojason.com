angular.module('astroApp').factory 'LinkResource', ['$resource', ($resource)->

  resource_parameter_defaults =
    id: '@id'

  resource_options =

    query:
      method: 'GET'
      isArray: true
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

  LinkResource = $resource '/api/link/:id', resource_parameter_defaults, resource_options

  LinkResource.prototype.cssClass = ()->
    cssClass = ''
    if @.times_loaded > 20
      cssClass = ' text-danger'
    else if @.times_loaded > 10
      cssClass = 'text-warning'
    if @.is_read
      cssClass += ' read'

    cssClass.trim()

  LinkResource
]