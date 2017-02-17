describe 'CreditController unit tests', ->
  $scope = null
  CreditController = null
  mockCreditResource = null
  mockCreditReportDeferred = null
  mockCreditSaveDeferred = null
  mockResponse =
    accounts: [1,2,3,4]
    chart: [1,2,3,4]

  beforeEach ->
    module 'astroApp'
    inject ($rootScope, $controller, $q)->
      $scope = $rootScope.$new()

      mockCreditResource =
        report: ->
          mockCreditReportDeferred = $q.defer()
          $promise: mockCreditReportDeferred.promise

        save: ->
          mockCreditSaveDeferred = $q.defer()
          $promise: mockCreditSaveDeferred.promise

      mockInjections =
        $scope: $scope
        CreditResource: mockCreditResource

      CreditController = $controller 'CreditController', mockInjections

    spyOn $scope, 'drawChart'

  it 'should call CreditResource.report', ->
    spyOn(mockCreditResource, 'report').and.callThrough()
    $scope.initList()
    expect(mockCreditResource.report).toHaveBeenCalled()

  it 'should set $scope.accounts to the accounts returned by the promise', ->
    $scope.initList()
    mockCreditReportDeferred.resolve mockResponse
    $scope.$digest()
    expect($scope.accounts).toEqual [1,2,3,4]

  it 'should call CreditResource.save when updateBalance is called', ->
    spyOn(mockCreditResource, 'save').and.callThrough()
    $scope.updateBalance {}
    expect(mockCreditResource.save).toHaveBeenCalled()
