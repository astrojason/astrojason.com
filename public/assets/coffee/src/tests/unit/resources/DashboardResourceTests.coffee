describe 'DashboardResource test', ->
  DashboardResource = null
  $httpBackend = null

  beforeEach ->
    module 'astroApp'
    inject (_$httpBackend_, _DashboardResource_)->
      $httpBackend = _$httpBackend_
      DashboardResource = _DashboardResource_

  it 'should call the default endpoint', ->
    $httpBackend.expectGET('/api/dashboard').respond 200
    DashboardResource.get()
    $httpBackend.flush()