angular.module('astroApp').controller 'ArticleController', [
  '$scope'
  '$controller'
  '$filter'
  '$timeout'
  '$location'
  '$log'
  'AlertifyService'
  'ArticleResource'
  ($scope,
    $controller,
    $filter,
    $timeout,
    $location,
    $log,
    AlertifyService,
    ArticleResource)->

    $controller 'FormMasterController', $scope: $scope

    query_data = $location.search()

    $scope.page_size = query_data.page_size || 20
    $scope.page = parseInt(query_data.page) || 1
    $scope.article_query = query_data.q || ''
    $scope.deleting = false
    $scope.errorMessage = false
    $scope.originalArticle = angular.copy $scope.article
    $scope.importedCount = 0
    $scope.loading_articles = false

    $scope.initList = ->
      $scope.query()

      categories_promise = ArticleResource.categories().$promise

      categories_promise.then (response)->
        $scope.categories = response.categories

      categories_promise.catch ->
        $log.debug 'something went wrong'

      $scope.$watch 'article_query', ->
        $timeout.cancel $scope.search_timeout
        if !$scope.loading_articles
          $scope.search_timeout = $timeout ->
            $scope.query()
          , 500

      $scope.$watch 'include_read', ->
        if !$scope.loading_articles
          $scope.query()

      $scope.$watch 'page', (newValue, oldValue)->
        if !$scope.loading_articles
          if newValue != oldValue
            cur_opts = $location.search()
            cur_opts.page = newValue
            $location.search(cur_opts)
            $scope.query()

      $scope.$watch 'display_category', ->
        if !$scope.loading_articles
          $scope.query()

      $scope.$watch 'sort', ->
        if !$scope.loading_articles
          if $scope.page > 1
            $scope.page = 1
          else
            $scope.query()

      $scope.$watch 'descending', ->
        if !$scope.loading_articles
          $scope.query()

    $scope.query = ->
      $scope.articles = []
      $scope.loading_articles = true
      data =
        page_size: $scope.page_size
        page: $scope.page
      if $scope.article_query
        data['q'] = $scope.article_query
      if $scope.display_category
        data['category'] = $scope.display_category.id
      if $scope.include_read
        data['include_read'] = $scope.include_read
      if $scope.sort
        data['sort'] = $scope.sort
      if $scope.descending
        data['descending'] = true
      articles_promise = ArticleResource.query(data).$promise

      articles_promise.then (articles)->
        $location.search data
        $scope.articles = articles
        $scope.total = articles.$total
        $scope.pages = articles.$page_count
        $scope.generatePages()

      articles_promise.finally ->
        $scope.loading_articles = false

    $scope.importLinks = ->
      $scope.alerts = []
      submitLinks = []
      errorLinks = []
      links = $scope.splitImports $scope.articles_to_import
      angular.forEach links, (link)->
        if link.trim != ''
          exploded = link.split '|'
          if exploded.length >= 2
            thisLink =
              url: ('http' + exploded[0]).trim()
              name: exploded[1].trim()
            if(thisLink.url != '' && thisLink.name != '')
              submitLinks.push thisLink
          else
            errorLinks.push link
      data =
        importlist: submitLinks

      importPromise = ArticleResource.import($.param data).$promise

      importPromise.then (articles)->
        imported = articles.filter (article)->
          article.justAdded == true
        skipped = articles.filter (article)->
          article.justAdded == false
        if imported.length > 0
          $scope.alerts.push
            type: 'success'
            msg: "Imported #{imported.length} article(s)."
        if skipped.length > 0
          message = """
            <p>Skipped #{skipped.length} link(s).</p>
            <table class='table table-striped'>
          """
          angular.forEach skipped, (article)->
            message += """
              <tr><td><a href="#{article.url}" target='_blank'>#{article.title}</a></td></tr>
            """
          message += """
            </table>
          """

          $scope.alerts.push
            type: 'danger'
            msg: message
        $scope.articles_to_import = ''

      importPromise.catch ->
        $scope.errorMessage = 'There was a problem with the import'

    $scope.splitImports = (data)->
      if data? != ''
        data.split 'http'

    $scope.checkEditing = ->
      $scope.article?.id?

    $scope.closeAlert = (index)->
      $scope.alerts.splice index, 1

    $scope.$watch 'articles', (newValue)->
      if newValue?
        deleted_articles = newValue.filter (article)->
          article.deleted
        if deleted_articles.length > 0
          $scope.articles = newValue.filter (article)->
            !article.deleted
    , true
]
