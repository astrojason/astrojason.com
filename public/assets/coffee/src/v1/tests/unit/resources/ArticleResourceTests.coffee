describe 'ArticleResource test', ->
  ArticleResource = null
  $httpBackend = null
  mockArticleQueryResponse = readJSON 'public/assets/coffee/src/v1/tests/data/articles.json'

  beforeEach ->
    module 'astroApp'
    inject (_$httpBackend_, _ArticleResource_)->
      $httpBackend = _$httpBackend_
      ArticleResource = _ArticleResource_

  it 'should GET to the main endpoint', ->
    $httpBackend.expectGET('/api/article').respond mockArticleQueryResponse
    ArticleResource.query()
    $httpBackend.flush()

  it 'should POST to the specific endpoint', ->
    $httpBackend.expectPOST('/api/article/1').respond 200
    ArticleResource.save(id: 1)
    $httpBackend.flush()

  it 'should DELETE to the specific endpoint', ->
    $httpBackend.expectDELETE('/api/article/1').respond 200
    ArticleResource.remove(id: 1)
    $httpBackend.flush()

  it 'should POST to the import endpoint', ->
    $httpBackend.expectPOST('/api/article/import').respond 200
    ArticleResource.import()
    $httpBackend.flush()

  it 'should POST to the specific endpoint when called as an attribute on a article', ->
    myArticle = new ArticleResource angular.copy(mockArticleQueryResponse.articles[0])
    $httpBackend.expectPOST("/api/article/#{myArticle.id}").respond 200
    myArticle.$save()
    $httpBackend.flush()

  it 'should DELETE to the specific endpoint when called as an attribute on a article', ->
    myArticle = new ArticleResource angular.copy(mockArticleQueryResponse.articles[0])
    $httpBackend.expectDELETE("/api/article/#{myArticle.id}").respond 200
    myArticle.$remove()
    $httpBackend.flush()

  it 'should call the read today endpoint', ->
    $httpBackend.expectGET('/api/article/read-today').respond total_read: 10
    ArticleResource.readToday()
    $httpBackend.flush()

  it 'should call the populate endpoint', ->
    $httpBackend.expectGET('/api/article/populate').respond 200
    ArticleResource.populate()
    $httpBackend.flush()
