angular.module('astroApp').controller 'GameController', ['$scope', '$filter', '$controller', '$timeout', '$location',
  '$log', 'GameResource', 'Game', 'AlertifyService', ($scope, $filter, $controller, $timeout, $location, $log,
  GameResource, Game, AleritfyService)->

    $controller 'FormMasterController', $scope: $scope

    $scope.loading_games = false
    $scope.saving_game = false
    $scope.include_completed = false

    $scope.$on 'gameDeleted', (event, message)->
      $scope.games = $filter('filter')($scope.games, {id: '!' + message})
      $scope.game_results = $filter('filter')($scope.game_results, {id: '!' + message})

    $scope.triggerRecommender = ->
      $scope.$watch 'recommendingGame', (newValue)->
        if newValue
          $scope.getRecommendation()

    $scope.initList = ->

      $scope.newGame = new Game()

      $scope.$watch 'game_query', ->
        $timeout.cancel $scope.search_timeout
        if !$scope.loading_games
          $scope.search_timeout = $timeout ->
            $scope.query 'game_query'
          , 500

      $scope.$watch 'page', (newValue, oldValue)->
        if !$scope.loading_games
          if newValue != oldValue
            cur_opts = $location.search()
            cur_opts.page = newValue
            $location.search(cur_opts)
            $scope.query 'page'

      $scope.$watch 'include_completed', ->
        if !$scope.loading_games
          $scope.query 'include_completed'

      $scope.$watch 'filter_platform', ->
        if !$scope.loading_games
          $scope.query 'filter_platform'

      $scope.$on 'closeModal', (event, game)->
        $scope.gameModalOpen = false
        if game
          game.new = true
          $scope.games.splice(0, 0, game)
          $timeout ->
            game.new = false
          , 1000

    $scope.query = (caller)->
      $log.info "Game query triggered by #{caller}"
      $timeout.cancel $scope.search_timeout
      $scope.loading_games = true

      data =
        limit: $scope.limit
        page: $scope.page
        include_completed: $scope.include_completed

      if $scope.game_query
        data['q'] = $scope.game_query

      if $scope.include_completed
        data['include_completed'] = $scope.include_completed

      if $scope.filter_platform
        data['platform'] = $scope.filter_platform

      gamePromise = GameResource.query(data).$promise

      gamePromise.then (response)->
        $scope.games = response.games
        $scope.total = response.total
        $scope.pages = response.pages
        $scope.generatePages()

      gamePromise.catch ->
        $scope.$emit 'errorOccurred', 'Could not load games'

      gamePromise.finally ->
        $scope.loading_games = false

    $scope.save = ->
      $scope.saving_game = true
      if $scope.game.category == 'New'
        $scope.game.category = $scope.new_category

      game_promise = GameResource.save($.param $scope.game).$promise

      game_promise.then (response)->
        AleritfyService.success "Game " + (if $scope.game.id then "updated" else "added") + " successfully"
        if $scope.game.id
          $scope.editing = false
        else
          $scope.$emit 'closeModal', response.game

      game_promise.catch (response)->
        if response?.data?.error
          $scope.errorMessage = response.data.error
        else
          $scope.errorMessage = 'Something went wrong'

      game_promise.finally ->
        $scope.saving_game = false

    $scope.togglePlayed = ->
      $scope.game.completed = !$scope.game.completed
      $scope.save()

    $scope.delete = ->
      success = ->
        AleritfyService.success 'Game deleted successfully'
        $scope.deleting = false
        $scope.editing = false
        $scope.$emit 'gameDeleted', $scope.game.id

      error = (response)->
        if response?.data?.error
          $scope.errorMessage = response.data.error
        else
          $scope.errorMessage = 'Something went wrong'

      game_promise = GameResource.remove id: $scope.game.id
      game_promise.$promise.then success, error

    $scope.setPlatforms = (platforms)->
      $scope.platforms = platforms

    $scope.checkEditing = ->
      return Boolean($scope.game?.id)

    $scope.getRecommendation = ->
      success = (response)->
        $scope.game = response.game
      error = ->
        $scope.$emit 'errorOccurred', 'Could not get game recommendation'

      game_promise = GameResource.recommend()
      game_promise.$promise.then success, error

    $scope.setPlatforms = (platforms)->
      $scope.platforms = platforms
]
