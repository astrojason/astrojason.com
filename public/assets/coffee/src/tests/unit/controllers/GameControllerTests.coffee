describe 'GameController unit tests', ->
  $scope = null
  GameController = null
  mockGameResource = null
  mockAlertifyService = null
  mockGameResourceQueryDeferred = null
  mockGameRecommendDeferred = null
  mockGameSaveDeferred = null
  mockGameRemoveDeferred = null
  mockGameQueryResponse = readJSON 'public/assets/coffee/src/tests/data/games.json'
  game = null
  GameModel = null
  mockForm = """
    <form name="game_form">
      <input name="title" ng-model="game.title" />
    </form>
  """

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, $controller, $q, _Game_, $compile)->
      $scope = $rootScope.$new()
      GameModel = _Game_

      mockAlertifyService =
        success: ->
        error: ->

      mockGameResource =
        query: ->
          mockGameResourceQueryDeferred = $q.defer()
          $promise: mockGameResourceQueryDeferred.promise

        recommend: ->
          mockGameRecommendDeferred = $q.defer()
          $promise: mockGameRecommendDeferred.promise

        save: ->
          mockGameSaveDeferred = $q.defer()
          $promise: mockGameSaveDeferred.promise

        remove: ->
          mockGameRemoveDeferred = $q.defer()
          $promise: mockGameRemoveDeferred.promise

      mockInjections =
        $scope: $scope
        GameResource: mockGameResource
        AlertifyService: mockAlertifyService

      game = mockGameQueryResponse.games[0]

      element = angular.element mockForm
      linker = $compile element
      element = linker $scope

      GameController = $controller 'GameController', mockInjections

  it 'should set the default variables', ->
    expect($scope.loading_games).toEqual false
    expect($scope.saving_game).toEqual false

  it 'should not generate a game recommendation when recommendingBook is set to true when the recommender has not been turned on', ->
    spyOn($scope, 'getRecommendation').and.callThrough()
    $scope.recommendingGame = true
    $scope.$digest()
    expect($scope.getRecommendation).not.toHaveBeenCalled()

  it 'should generate the game recommendation when recommendingBook is set to true when the recommender has been turned on', ->
    $scope.triggerRecommender()
    spyOn($scope, 'getRecommendation').and.callThrough()
    $scope.recommendingGame = true
    $scope.$digest()
    expect($scope.getRecommendation).toHaveBeenCalled()

  it 'should set newGame to an new game instance', ->
    $scope.initList()
    expect($scope.newGame).toEqual new GameModel()

  it 'should not try to update the list when game_query changes if initList has not been called', ->
    spyOn($scope, 'query').and.callThrough()
    $scope.game_query = 'test'
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should try to update the list when game_query changes if initList has been called', ->
    spyOn($scope, 'query').and.callThrough()
    $scope.initList()
    $scope.game_query = 'test'
    $scope.$digest()
    expect($scope.query).toHaveBeenCalled()

  it 'should not try to update the list when game_query changes if initList has been called and loading_games is true', ->
    spyOn($scope, 'query').and.callThrough()
    $scope.initList()
    $scope.loading_games = true
    $scope.game_query = 'test'
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should not try to update the list when page changes if initList has not been called', ->
    spyOn($scope, 'query').and.callThrough()
    $scope.page = 3
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should try to update the list when page changes if initList has been called', ->
    spyOn($scope, 'query').and.callThrough()
    $scope.initList()
    $scope.page = 3
    $scope.$digest()
    expect($scope.query).toHaveBeenCalled()

  it 'should not try to update the list when page changes if initList has been called and loading_games is true', ->
    spyOn($scope, 'query').and.callThrough()
    $scope.initList()
    $scope.loading_games = true
    $scope.page = 3
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should not try to update the list when include_completed changes if initList has not been called', ->
    spyOn($scope, 'query').and.callThrough()
    $scope.include_completed = true
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should try to update the list when include_completed changes if initList has been called', ->
    spyOn($scope, 'query').and.callThrough()
    $scope.initList()
    $scope.include_completed = true
    $scope.$digest()
    expect($scope.query).toHaveBeenCalled()

  it 'should not try to update the list when include_completed changes if initList has been called and loading_games is true', ->
    spyOn($scope, 'query').and.callThrough()
    $scope.initList()
    $scope.loading_games = true
    $scope.include_completed = true
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should not try to update the list when filter_platform changes if initList has not been called', ->
    spyOn($scope, 'query').and.callThrough()
    $scope.filter_platform = true
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should try to update the list when filter_platform changes if initList has been called', ->
    spyOn($scope, 'query').and.callThrough()
    $scope.initList()
    $scope.filter_platform = true
    $scope.$digest()
    expect($scope.query).toHaveBeenCalled()

  it 'should not try to update the list when filter_platform changes if initList has been called and loading_games is true', ->
    spyOn($scope, 'query').and.callThrough()
    $scope.initList()
    $scope.loading_games = true
    $scope.filter_platform = true
    $scope.$digest()
    expect($scope.query).not.toHaveBeenCalled()

  it 'should set loading_games to true when $scope.query is called', ->
    $scope.query()
    expect($scope.loading_games).toEqual true

  it 'should call GameResource.query when $scope.query is called', ->
    spyOn(mockGameResource, 'query').and.callThrough()
    $scope.query()
    expect(mockGameResource.query).toHaveBeenCalled()

  it 'should set $scope.loading_games to false when GameResource.query succeeds', ->
    $scope.query()
    mockGameResourceQueryDeferred.resolve angular.copy(mockGameQueryResponse)
    $scope.$digest()
    expect($scope.loading_games).toEqual false

  it 'should set $scope.loading_games to false when GameResource.query fails', ->
    $scope.query()
    mockGameResourceQueryDeferred.reject()
    $scope.$digest()
    expect($scope.loading_games).toEqual false

  it 'should set the games variables when GameResource.query succeeds', ->
    $scope.query()
    mockGameResourceQueryDeferred.resolve angular.copy(mockGameQueryResponse)
    $scope.$digest()
    expect($scope.games).toEqual mockGameQueryResponse.games
    expect($scope.total).toEqual mockGameQueryResponse.total
    expect($scope.pages).toEqual mockGameQueryResponse.pages

  it 'should $emit errorOccurred when GameResource.query fails', ->
    spyOn($scope, '$emit').and.callThrough()
    $scope.query()
    mockGameResourceQueryDeferred.reject()
    $scope.$digest()
    expect($scope.$emit).toHaveBeenCalledWith 'errorOccurred', 'Could not load games'

  it 'should call GameResource.save when $scope.save is called', ->
    spyOn(mockGameResource, 'save').and.callThrough()
    $scope.game = angular.copy game
    $scope.save()
    expect(mockGameResource.save).toHaveBeenCalled()

  it 'should set $scope.saving_game to true when $scope.save is called', ->
    $scope.game = angular.copy game
    $scope.save()
    expect($scope.saving_game).toEqual true

  it 'should call set $scope.game.category to $scope.new_category when the selected category is New', ->
    $scope.game = angular.copy game
    $scope.game.category = 'New'
    $scope.new_category = 'My Test Category'
    $scope.save()
    expect($scope.game.category).toEqual 'My Test Category'

  it 'should call AlertifyService.success when the GameResource.save succeeds', ->
    spyOn(mockAlertifyService, 'success').and.callThrough()
    $scope.game = angular.copy game
    $scope.save()
    mockGameSaveDeferred.resolve()
    $scope.$digest()
    expect(mockAlertifyService.success).toHaveBeenCalled()

  it 'should set $scope.saving_game to false when GameResource.save succeeds', ->
    $scope.game = angular.copy game
    $scope.save()
    mockGameSaveDeferred.resolve()
    $scope.$digest()
    expect($scope.saving_game).toEqual false

  it 'should set $scope.editing to false when GameResource.save succeeds and the game has an id', ->
    $scope.editing = true
    $scope.game = angular.copy game
    $scope.save()
    mockGameSaveDeferred.resolve()
    $scope.$digest()
    expect($scope.editing).toEqual false

  it 'should close the modal when GameResource.save succeeds and the game does not have an id', ->
    spyOn($scope, '$emit').and.callThrough()
    $scope.game = angular.copy game
    $scope.game.id = null
    $scope.save()
    mockGameSaveDeferred.resolve game: game
    $scope.$digest()
    expect($scope.$emit).toHaveBeenCalledWith 'closeModal', game

  it 'should set $scope.saving_game to false when GameResource.save fails', ->
    $scope.game = angular.copy game
    $scope.save()
    mockGameSaveDeferred.reject()
    $scope.$digest()
    expect($scope.saving_game).toEqual false

  it 'should set $scope.errorMessage to the returned value when GameResource.save fails and an error is passed', ->
    $scope.game = angular.copy game
    $scope.save()
    mockGameSaveDeferred.reject data: 'This is the error'
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'This is the error'

  it 'should set $scope.errorMessage to the returned value when GameResource.save fails and no error is passed', ->
    $scope.game = angular.copy game
    $scope.save()
    mockGameSaveDeferred.reject()
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'Something went wrong'

  it 'should call $scope.save when $scope.togglePlayed is called', ->
    spyOn($scope, 'save').and.callThrough()
    $scope.game = angular.copy game
    $scope.togglePlayed()
    expect($scope.save).toHaveBeenCalled()

  it 'should set the completed flag to true if it is false', ->
    $scope.game = angular.copy game
    $scope.game.completed = false
    $scope.togglePlayed()
    expect($scope.game.completed).toEqual true

  it 'should set the completed flag to false if it is true', ->
    $scope.game = angular.copy game
    $scope.game.completed = true
    $scope.togglePlayed()
    expect($scope.game.completed).toEqual false

  it 'should call GameResource.remove when $scope.delete is called', ->
    spyOn(mockGameResource, 'remove').and.callThrough()
    $scope.game = angular.copy game
    $scope.delete()
    expect(mockGameResource.remove).toHaveBeenCalled()

  it 'should call AlertifyService.success when GameResource.remove succeeds', ->
    spyOn(mockAlertifyService, 'success').and.callThrough()
    $scope.game = angular.copy game
    $scope.delete()
    mockGameRemoveDeferred.resolve()
    $scope.$digest()
    expect(mockAlertifyService.success).toHaveBeenCalledWith 'Game deleted successfully'

  it 'should set $scope.deleting to false when GameResource.remove succeeds', ->
    $scope.deleting = true
    $scope.game = angular.copy game
    $scope.delete()
    mockGameRemoveDeferred.resolve()
    $scope.$digest()
    expect($scope.deleting).toEqual false

  it 'should set $scope.editing to false when GameResource.remove succeeds', ->
    $scope.editing = true
    $scope.game = angular.copy game
    $scope.delete()
    mockGameRemoveDeferred.resolve()
    $scope.$digest()
    expect($scope.editing).toEqual false

  it 'should $emit gameDeleted when GameResource.remove succeeds', ->
    spyOn($scope, '$emit').and.callThrough()
    $scope.game = angular.copy game
    $scope.delete()
    mockGameRemoveDeferred.resolve()
    $scope.$digest()
    expect($scope.$emit).toHaveBeenCalledWith 'gameDeleted', game.id

  it 'should set $scope.errorMessage to the returned error when one exists', ->
    $scope.game = angular.copy game
    $scope.delete()
    mockGameRemoveDeferred.reject data: error: 'This is a passed error.'
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'This is a passed error.'

  it 'should set $scope.errorMessage to the default error when no message is returned', ->
    $scope.game = angular.copy game
    $scope.delete()
    mockGameRemoveDeferred.reject()
    $scope.$digest()
    expect($scope.errorMessage).toEqual 'Something went wrong'

  it 'should set $scope.platforms to the passed value', ->
    $scope.setPlatforms [1,2,3,4]
    expect($scope.platforms).toEqual [1,2,3,4]
#  TODO: Test $scope.checkEditing
  it '$scope.checkEditing should return false if there is no game id', ->
    expect($scope.checkEditing()).toEqual false

  it '$scope.checkEditing should return true if there is a game id', ->
    $scope.game = angular.copy game
    expect($scope.checkEditing()).toEqual true

  it 'should call GameResource.recommend when getRecommendation is called', ->
    spyOn(mockGameResource, 'recommend').and.callThrough()
    $scope.getRecommendation()
    expect(mockGameResource.recommend).toHaveBeenCalled()

  it 'should set $scope.game to the returned game when GameResource.recommend succeeds', ->
    $scope.getRecommendation()
    mockGameRecommendDeferred.resolve game: angular.copy(game)
    $scope.$digest()
    expect($scope.game).toEqual game

  it 'should $emit errorOccurred when GameResource.recommend fails', ->
    spyOn($scope, '$emit').and.callThrough()
    $scope.getRecommendation()
    mockGameRecommendDeferred.reject()
    $scope.$digest()
    expect($scope.$emit).toHaveBeenCalledWith 'errorOccurred', 'Could not get game recommendation'