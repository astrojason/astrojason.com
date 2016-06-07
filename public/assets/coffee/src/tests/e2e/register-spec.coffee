firstNameInput = null
lastNameInput = null
emailInput = null
usernameInput = null
passwordInput = null
confirmPasswordInput = null
registerButton = null


describe 'Protractor registration tests', ->

  beforeEach ->
    browser.get 'http://localhost:8888/register'
    browser.waitForAngular()

    firstNameInput = element By.id('first_name')
    lastNameInput = element By.id('last_name')
    emailInput = element By.id('email')
    usernameInput = element By.id('username')
    passwordInput = element By.id('password')
    confirmPasswordInput = element By.id('confirm_password')
    registerButton = element By.id('registerSubmit')

  enderValidData = ->
    random = Math.random()
    firstNameInput.sendKeys 'Test'
    lastNameInput.sendKeys 'User'
    emailInput.sendKeys "test#{random}@user.com"
    usernameInput.sendKeys "testuser#{random}"
    passwordInput.sendKeys 'a'
    confirmPasswordInput.sendKeys 'a'

  it 'should got to the registration form', ->
    expect(browser.getTitle()).toEqual 'Registration :: astrojason.com'

  it 'should have a disabled save button when the form is empty', ->
    expect(registerButton.isEnabled()).toEqual false

  it 'should have an enabled save button when the form is valid', ->
    enderValidData()
    browser.waitForAngular()

    expect(registerButton.isEnabled()).toEqual true

  it 'should have a disabled save button when the passwords do not match', ->
    enderValidData()
    confirmPasswordInput.sendKeys 's'
    browser.waitForAngular()

    expect(registerButton.isEnabled()).toEqual false
    
  it 'should show an error message for the email input if the email address already exists', ->
    emailInput.sendKeys 'jason@astrojason.com'
    firstNameInput.click()
    browser.waitForAngular()

    expect(element(By.id('emailInUse')).isDisplayed()).toBeTruthy()

  it 'should not show an error message for the email input if the email address does not exist', ->
    emailInput.sendKeys 'doesnotexist@astrojason.com'
    firstNameInput.click()
    browser.waitForAngular()

    expect(element(By.id('emailInUse')).isDisplayed()).toBeFalsy()

  it 'should show an error message for the username input if the username already exists', ->
    usernameInput.sendKeys 'astrojason'
    firstNameInput.click()
    browser.waitForAngular()

    expect(element(By.id('usernameInUse')).isDisplayed()).toBeTruthy()

  it 'should not show an error message for the username input if the username does not exist', ->
    usernameInput.sendKeys 'notastrojason'
    firstNameInput.click()
    browser.waitForAngular()

    expect(element(By.id('usernameInUse')).isDisplayed()).toBeFalsy()

  it 'should submit the form and return successfully', ->
    enderValidData()
    browser.waitForAngular()
    registerButton.click()
    browser.waitForAngular()
    expect(element(By.className('alert-success')).isDisplayed()).toBeTruthy()