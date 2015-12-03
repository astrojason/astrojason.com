describe 'FormMasterController tests', ->
  $scope = null
  FormMasterController = null

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, $controller)->
      $scope = $rootScope.$new()

      mockInjections =
        $scope: $scope

      FormMasterController = $controller 'FormMasterController', mockInjections

  it 'should set the default variables', ->
    expect($scope.searching).toEqual false
    expect($scope.updating).toEqual false
    expect($scope.search_timeout).toEqual null
    expect($scope.limit).toEqual 20
    expect($scope.page).toEqual 1

  it 'should set $scope.editing to false if $scope.updating is true', ->
    $scope.updating = true
    $scope.editing = true
    $scope.cancelEdit()
    expect($scope.editing).toEqual false

  it 'should emit the closeModal function when $scope.updating is false', ->
    spyOn($scope, '$emit').and.callThrough()
    $scope.cancelEdit()
    expect($scope.$emit).toHaveBeenCalledWith 'closeModal'

  it 'should generate the correct number of pages', ->
    $scope.pages = 1
    $scope.page = 1
    $scope.generatePages()
    expect($scope.nav_pages).toEqual []
    $scope.pages = 5
    $scope.generatePages()
    expect($scope.nav_pages).toEqual [1, 2, 3, 4, 5]
    $scope.pages = 20
    $scope.generatePages()
    expect($scope.nav_pages).toEqual [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
    $scope.page = 5
    $scope.generatePages()
    expect($scope.nav_pages).toEqual [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
    $scope.page = 6
    $scope.generatePages()
    expect($scope.nav_pages).toEqual [2, 3, 4, 5, 6, 7, 8, 9, 10, 11]
    $scope.page = 11
    $scope.generatePages()
    expect($scope.nav_pages).toEqual [7, 8, 9, 10, 11, 12, 13, 14, 15, 16]
    $scope.page = 18
    $scope.generatePages()
    expect($scope.nav_pages).toEqual [11, 12, 13, 14, 15, 16, 17, 18, 19, 20]
    $scope.page = 20
    $scope.generatePages()
    expect($scope.nav_pages).toEqual [11, 12, 13, 14, 15, 16, 17, 18, 19, 20]

