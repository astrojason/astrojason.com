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