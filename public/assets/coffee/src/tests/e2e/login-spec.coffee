describe 'Protractor login tests', ->

#  beforeEach ->
#    browser.manage().window().setSize 800, 600
#
#  it 'should not show the login form', ->
#    browser.get('http://127.0.0.1:8000/')
#    browser.waitForAngular()
#
#    expect(browser.isElementPresent(By.id('loginForm'))).toBeFalsy()
#
#  it 'should open the login modal', ->
#    browser.get('http://127.0.0.1:8000/')
#    browser.waitForAngular()
#    element(By.id('loginButton')).click()
#    browser.wait ->
#      browser.isElementPresent(By.name('loginForm'))
#    , 3000
#
#    expect(element(By.name('loginForm')).isDisplayed()).toBeTruthy()
#
#  it 'should take the user to the forgot password page', ->
#    browser.get('http://127.0.0.1:8000/')
#    browser.waitForAngular()
#    element(By.id('loginButton')).click()
#    browser.wait ->
#      browser.isElementPresent(By.name('loginForm'))
#    , 3000
#
#    element(By.css('a[href="/password_reset/"]')).click()