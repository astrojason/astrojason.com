describe 'ArticleResource test', ->
  ArticleResource = null
  $httpBackend = null
  mockArticleQueryResponse = readJSON 'public/assets/coffee/src/v1/tests/data/links.json'

  beforeEach ->
    module 'astroApp'
    inject (_$httpBackend_, _ArticleResource_)->
      $httpBackend = _$httpBackend_
      ArticleResource = _ArticleResource_

  it 'should GET to the main endpoint', ->
    $httpBackend.expectGET('/api/link').respond mockArticleQueryResponse
    ArticleResource.query()
    $httpBackend.flush()

  it 'should POST to the specific endpoint', ->
    $httpBackend.expectPOST('/api/link/1').respond 200
    ArticleResource.save(id: 1)
    $httpBackend.flush()

  it 'should DELETE to the specific endpoint', ->
    $httpBackend.expectDELETE('/api/link/1').respond 200
    ArticleResource.remove(id: 1)
    $httpBackend.flush()

  it 'should POST to the import endpoint', ->
    $httpBackend.expectPOST('/api/link/import').respond 200
    ArticleResource.import()
    $httpBackend.flush()

  it 'should POST to the specific endpoint when called as an attribute on a link', ->
    myArticle = new ArticleResource angular.copy(mockArticleQueryResponse.links[0])
    $httpBackend.expectPOST("/api/link/#{myArticle.id}").respond 200
    myArticle.$save()
    $httpBackend.flush()

  it 'should DELETE to the specific endpoint when called as an attribute on a link', ->
    myArticle = new ArticleResource angular.copy(mockArticleQueryResponse.links[0])
    $httpBackend.expectDELETE("/api/link/#{myArticle.id}").respond 200
    myArticle.$remove()
    $httpBackend.flush()

  it 'should return bg-danger when the times_loaded is greater than 20', ->
    myArticle = new ArticleResource()
    myArticle.times_loaded = 21
    expect(myArticle.cssClass()).toEqual 'bg-danger'

  it 'should return bg-warning when the times_loaded is greater than 10 but less than 20', ->
    myArticle = new ArticleResource()
    myArticle.times_loaded = 15
    expect(myArticle.cssClass()).toEqual 'bg-warning'

  it 'should return read when is_read is true', ->
    myArticle = new ArticleResource()
    myArticle.is_read = true
    expect(myArticle.cssClass()).toEqual 'read'

  it 'should return undefined when the times_loaded is 10 or less', ->
    myArticle = new ArticleResource()
    expect(myArticle.cssClass()).toEqual ''

  it 'should call the read today endpoint', ->
    $httpBackend.expectGET('/api/link/readtoday').respond total_read: 10
    ArticleResource.readToday()
    $httpBackend.flush()

  it 'should call the populate endpoint', ->
    $httpBackend.expectGET('/api/link/populate').respond 200
    ArticleResource.populate()
    $httpBackend.flush()
