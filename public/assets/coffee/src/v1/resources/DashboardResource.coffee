angular.module('astroApp').factory 'DashboardResource', ['$resource', ($resource)->
  resource_options =
    get:
      method: 'GET'
      isArray: false

  return $resource '/api/dashboard', {}, resource_options
]