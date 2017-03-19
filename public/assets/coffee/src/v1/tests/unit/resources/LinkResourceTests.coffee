describe 'LinkResource test', ->
  LinkResource = null
  $httpBackend = null
  mockLinkQueryResponse = readJSON 'public/assets/coffee/src/v1/tests/data/links.json'

  beforeEach ->
    module 'astroApp'
    inject (_$httpBackend_, _LinkResource_)->
      $httpBackend = _$httpBackend_
      LinkResource = _LinkResource_

  it 'should GET to the main endpoint', ->
    $httpBackend.expectGET('/api/link').respond mockLinkQueryResponse
    LinkResource.query()
    $httpBackend.flush()

  it 'should POST to the specific endpoint', ->
    $httpBackend.expectPOST('/api/link/1').respond 200
    LinkResource.save(id: 1)
    $httpBackend.flush()

  it 'should DELETE to the specific endpoint', ->
    $httpBackend.expectDELETE('/api/link/1').respond 200
    LinkResource.remove(id: 1)
    $httpBackend.flush()

  it 'should POST to the import endpoint', ->
    $httpBackend.expectPOST('/api/link/import').respond 200
    LinkResource.import()
    $httpBackend.flush()

  it 'should POST to the specific endpoint when called as an attribute on a link', ->
    myLink = new LinkResource angular.copy(mockLinkQueryResponse.links[0])
    $httpBackend.expectPOST("/api/link/#{myLink.id}").respond 200
    myLink.$save()
    $httpBackend.flush()

  it 'should DELETE to the specific endpoint when called as an attribute on a link', ->
    myLink = new LinkResource angular.copy(mockLinkQueryResponse.links[0])
    $httpBackend.expectDELETE("/api/link/#{myLink.id}").respond 200
    myLink.$remove()
    $httpBackend.flush()

  it 'should return bg-danger when the times_loaded is greater than 20', ->
    myLink = new LinkResource()
    myLink.times_loaded = 21
    expect(myLink.cssClass()).toEqual 'bg-danger'

  it 'should return bg-warning when the times_loaded is greater than 10 but less than 20', ->
    myLink = new LinkResource()
    myLink.times_loaded = 15
    expect(myLink.cssClass()).toEqual 'bg-warning'

  it 'should return read when is_read is true', ->
    myLink = new LinkResource()
    myLink.is_read = true
    expect(myLink.cssClass()).toEqual 'read'

  it 'should return undefined when the times_loaded is 10 or less', ->
    myLink = new LinkResource()
    expect(myLink.cssClass()).toEqual ''

  it 'should call the read today endpoint', ->
    $httpBackend.expectGET('/api/link/readtoday').respond total_read: 10
    LinkResource.readToday()
    $httpBackend.flush()

  it 'should call the populate endpoint', ->
    $httpBackend.expectGET('/api/link/populate').respond 200
    LinkResource.populate()
    $httpBackend.flush()