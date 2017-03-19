describe 'Protractor index tests', ->

  it 'should have a title', ->
    browser.get 'http://localhost:8888/'
    browser.waitForAngular()

    expect(browser.getTitle()).toEqual 'Welcome :: astrojason.com'