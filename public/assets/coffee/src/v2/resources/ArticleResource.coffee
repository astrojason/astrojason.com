angular.module('astroApp').factory 'ArticleResource', [
  '$resource'
  '$log'
  '$http'
  ($resource,
    $log,
    $http)->

    arrayResponder =
      isArray: true
      transformResponse: (response)->
        wrappedResponse = angular.fromJson response
        if wrappedResponse.articles
          wrappedResponse.articles.$page_count = wrappedResponse.pageCount

          wrappedResponse.articles
        else
          wrappedResponse
      interceptor:
        response: (response)->
          response.resource.$page_count = response.data.$page_count
          response.resource

    resource_parameter_defaults =
      id: '@id'

    resource_options =
      add:
        method: 'PUT'

      daily: angular.merge {}, arrayResponder,
        method: 'GET'
        params:
          id: 'daily'

      import: angular.merge {}, arrayResponder,
        method: 'POST'
        params:
          id: 'import'

      query: angular.merge {}, arrayResponder, method: 'GET'

    ArticleResource = $resource '/api/article/:id', resource_parameter_defaults, resource_options

    ArticleResource.prototype.markRead = ->
      article = @
      readPromise = $http.get "/api/article/#{@id}/read"

      readPromise.then ->
        article.read.push (new moment()).format('YYYY-MM-DD')

    ArticleResource.prototype.postpone = ->
      today = (new moment()).format('YYYY-MM-DD')
      article = @
      readPromise = $http.get "/api/article/#{@id}/postpone"

      readPromise.then ->
        angular.forEach article.recommended, (recommended)->
          if recommended.date == today
            recommended.postponed = true
        $log.debug article

    ArticleResource.prototype.delete = ->
      @deleting = true
      $log.debug "Gonna delete #{@title} with id: #{@id}"

    ArticleResource.prototype.readToday = ->
      today = (new moment()).format('YYYY-MM-DD')
      read = @read.filter (date)->
        date == today
      read.length > 0

    ArticleResource.prototype.postponedToday = ->
      today = (new moment()).format('YYYY-MM-DD')
      read = @recommended.filter (recommended)->
        recommended.date == today && recommended.postponed == true
      read.length > 0

    ArticleResource
]