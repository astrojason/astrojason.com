angular.module('astroApp').factory 'ArticleResource', [
  '$resource'
  ($resource)->

    resource_parameter_defaults =
      id: '@id'

    resource_options =
      import:
        method: 'POST'
        params:
          id: 'import'

    ArticleResource = $resource '/api/article/:id', resource_parameter_defaults, resource_options

    ArticleResource
]