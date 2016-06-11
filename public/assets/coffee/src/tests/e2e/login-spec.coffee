http = require 'http'

usernameInput = null
passwordInput = null
loginSubmit = null
userNavItem = null
logoutButton = null

describe 'Protractor login tests', ->

  beforeEach ->
    browser.get 'http://localhost:8888/'
    browser.waitForAngular()

    usernameInput = element By.id 'login_username'
    passwordInput = element By.id 'login_password'
    loginSubmit = element By.id 'login_submit'
    userNavItem = element By.id 'user_nav'
    logoutButton = element By.id 'logout_button'

  afterEach ->
    browser.manage().deleteAllCookies()

  it 'should show the login form', ->
    expect(usernameInput.isDisplayed()).toBeTruthy()

  it 'should not show the user nav', ->
    expect(userNavItem.isDisplayed()).toBeFalsy()
    
  it 'should have a disabled submit button when the form is invalid', ->
    expect(loginSubmit.isEnabled()).toEqual false

  it 'should have a enabled submit button when the form is valid', ->
    usernameInput.sendKeys 'test'
    passwordInput.sendKeys 'test'
    expect(loginSubmit.isEnabled()).toEqual true

  it 'should log the user in', ->
    usernameInput.sendKeys 'testuser'
    passwordInput.sendKeys 'a'
    loginSubmit.click()
    browser.waitForAngular()
    expect(userNavItem.isDisplayed()).toBeTruthy()

#    TODO: Test what happens when the login fails
  it 'should log the user in', ->
    erroMessage = element By.className 'alert-danger'
    usernameInput.sendKeys 'testuser'
    passwordInput.sendKeys 'wrong'
    loginSubmit.click()
    browser.waitForAngular()
    expect(erroMessage.getText()).toEqual 'Could not log you in.'