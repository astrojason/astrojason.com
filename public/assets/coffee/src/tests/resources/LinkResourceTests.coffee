describe 'LinkResource test', ->
  LinkResource = null
  $httpBackend = null

  beforeEach ->
    module 'astroApp'
    inject (_$httpBackend_, _LinkResource_)->
      $httpBackend = _$httpBackend_
      LinkResource = _LinkResource_

  it 'should call the default endpoint', ->
    $httpBackend.expectGET('/api/link').respond 200
    LinkResource.query()
    $httpBackend.flush()