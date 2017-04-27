angular.module('astroApp').factory 'ArticleResource', [
  '$resource'
  '$log'
  '$http'
  '$uibModal'
  ($resource,
    $log,
    $http,
    $uibModal)->

    arrayResponder =
      isArray: true
      transformResponse: (response)->
        wrappedResponse = angular.fromJson response
        if wrappedResponse.articles
          wrappedResponse.articles.$page_count = wrappedResponse.page_count
          wrappedResponse.articles.$total = wrappedResponse.$total

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

      categories:
        method: 'GET'
        params:
          id: 'category'

      daily: angular.merge {}, arrayResponder,
        method: 'GET'
        params:
          id: 'daily'

      import: angular.merge {}, arrayResponder,
        method: 'POST'
        params:
          id: 'import'

      populate:
        method: 'GET'
        params:
          id: 'populate'

      query: angular.merge {}, arrayResponder, method: 'GET'

      readToday:
        method: 'GET'
        params:
          id: 'read-today'

    ArticleResource = $resource '/api/article/:id', resource_parameter_defaults, resource_options

    ArticleResource.prototype.edit = ->
      article = @
      $uibModal.open
        templateUrl: "modals/article-form"
        controller: "ArticleFormController"
        resolve:
          article: ->
            article

    ArticleResource.prototype.markRead = ->
      article = @
      readPromise = $http.get "/api/article/#{article.id}/read"

      readPromise.then ->
        article.read.push (new moment()).format('YYYY-MM-DD')

      readPromise

    ArticleResource.prototype.postpone = ->
      today = (new moment()).format('YYYY-MM-DD')
      article = @
      postponePromise = $http.get "/api/article/#{article.id}/postpone"

      postponePromise.then ->
        angular.forEach article.recommended, (recommended)->
          if recommended.date == today
            recommended.postponed = true

      postponePromise

    ArticleResource.prototype.warnDelete = ->
      article = @
      $uibModal.open
        template: """
          <div class="modal-header">
              <h3 class="modal-title" id="modal-title">Deleting {{ article.title }}</h3>
          </div>
          <div class="modal-body bg-danger" id="modal-body">
            <p class="text-white">You are about to permanently deleted the selected article. This action cannot be undone.</p>
          </div>
          <div class="modal-footer">
              <button class="btn btn-danger" type="button" ng-click="article.delete(); cancel()">Delete</button>
              <button class="btn btn-default" type="button" ng-click="cancel()">Cancel</button>
          </div>
        """
        controller: [
          '$scope'
          '$uibModalInstance'
          'article'
          ($scope, $uibModalInstance, article)->
            $scope.article = article
            $scope.cancel = ->
              $uibModalInstance.dismiss()
        ]
        resolve:
          article: ->
            article

    ArticleResource.prototype.delete = ->
      article = @
      article.deleting = true
      deletePromise = $http.delete "/api/article/#{article.id}"

      deletePromise.then ->
        article.deleted = true

      deletePromise.finally ->
        article.deleting = false

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