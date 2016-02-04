angular.module('astroApp').controller 'LinkController', ['$scope', '$controller', '$filter', '$timeout', '$location',
  'LinkResource', 'Link', 'AlertifyService',
  ($scope, $controller, $filter, $timeout, $location, LinkResource, Link, AlertifyService)->

    $controller 'FormMasterController', $scope: $scope

    $scope.deleting = false
    $scope.errorMessage = false
    $scope.originalLink = angular.copy $scope.link
    $scope.importedCount = 0

    $scope.$on 'linkDeleted', (event, message)->
      $scope.links = $filter('filter')($scope.links, {id: '!' + message})
      $scope.link_results = $filter('filter')($scope.link_results, {id: '!' + message})

    $scope.$watch 'link.link', (oldValue, newValue)->
      if oldValue != newValue
        $scope.errorMessage = false

    $scope.initList = ->

      $scope.newLink = new Link()

      $scope.query()

      $scope.$on 'closeModal', (event, link)->
        $scope.linkModalOpen = false
        if link
          link.new = true
          $scope.links.splice(0, 0, link)
          $timeout ->
            link.new = false
          , 1000

      $scope.$watch 'links_query', ->
        if !$scope.loading_links
          $timeout.cancel $scope.search_timeout
          if !$scope.loading_links
            $scope.search_timeout = $timeout ->
              $scope.query()
            , 500

      $scope.$watch 'page', (newValue, oldValue)->
        if !$scope.loading_links
          if newValue != oldValue
            cur_opts = $location.search()
            cur_opts.page = newValue
            $location.search(cur_opts)
            $scope.query()

      $scope.$watch 'display_category', ->
        if !$scope.loading_links
          $scope.query()

    $scope.query = ->
      $scope.loading_links = true
      data =
        limit: $scope.limit
        page: $scope.page
      if $scope.links_query
        data['q'] = $scope.links_query
      if $scope.display_category
        data['category'] = $scope.display_category
      linkQueryPromise = LinkResource.query(data).$promise

      linkQueryPromise.then (links)->
        $scope.links = links
        $scope.total = links.$total
        $scope.pages = links.$pages
        $scope.generatePages()

      linkQueryPromise.finally ->
        $scope.loading_links = false

    $scope.linkOpened = ->
      $scope.link.times_read += 1
      $scope.save()

    $scope.toggleRead = ->
      $scope.link.is_read = !$scope.link.is_read
      $scope.save()

    $scope.delete = ->
      link_promise = LinkResource.remove(id: $scope.link.id).$promise

      link_promise.then ->
        AlertifyService.success 'Link deleted successfully'
        $scope.deleting = false
        $scope.editing = false
        $scope.$emit 'linkDeleted', $scope.link.id

      link_promise.catch (response)->
        $scope.errorMessage = response?.data?.error || 'There was an error deleting the selected link.'

    $scope.save = ->
      if $scope.link.category == 'New'
        $scope.link.category = $scope.new_category

#        TODO: Figure out how to unit test this as a $scope.link.$save() promise
      link_promise = LinkResource.save($.param($scope.link)).$promise

      link_promise.then (response)->
        AlertifyService.success "Link " + (if $scope.link.id then "updated" else "added") + " successfully."
        if $scope.link.id
          $scope.editing = false
          $scope.$emit 'linkUpdated', $scope.link
          if $scope.originalLink.is_read != $scope.link.is_read
            $scope.$emit 'linkRead', $scope.link.is_read
        else
          $scope.link_form.$setPristine()
          $scope.$emit 'closeModal', response.link

      link_promise.catch (response)->
        $scope.errorMessage = response?.data?.error || 'There was an error saving the selected link.'

    $scope.setCategories = (categories)->
      $scope.categories = categories

    $scope.importLinks = ->
      $scope.importedCount = 0
      submitLinks = []
      errorLinks = []
      links = $scope.splitImports $scope.importList
      angular.forEach links, (link)->
        if link != ''
          exploded = link.split '|'
          if exploded.length >= 2
            thisLink =
              url: ('http' + exploded[0]).trim()
              name: exploded[1].trim()
            submitLinks.push thisLink
          else
            errorLinks.push link
      data =
        importlist: submitLinks

      importPromise = LinkResource.import($.param data).$promise

      importPromise.then (response)->
        $scope.importedCount = response.count

      importPromise.catch ->
        $scope.errorMessage = 'There was a problem with the import'

    $scope.splitImports = (data)->
      data.split 'http'
]