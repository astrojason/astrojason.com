angular.module('astroApp').factory 'UserResource', ['$resource', ($resource)->

  resource_methods =
    add:
      method: 'PUT'

    checkemail:
      method: 'POST'
      params:
        action: 'checkemail'

    checkusername:
      method: 'POST'
      params:
        action: 'checkusername'

    login:
      method: 'POST'
      params:
        action: 'login'
      transformResponse: (response)->
        wrappedResponse = angular.fromJson response
        wrappedResponse.user

    logout:
      method: 'POST'
      params:
        action: 'logout'

    save:
      method: 'POST'
      params:
        action: '@id'

  $resource '/api/user/:action', {}, resource_methods
]