angular.module 'astroApp', [
  'ngMessages'
  'ngResource'
  'ngAnimate'
  'ngSanitize'
  '720kb.fx'
  'ui.bootstrap'
  'siyfion.sfTypeahead'
  'angular-chosen'
]

param = (data)->
  query = ''

  for own name of data
    value = data[name]

    if value instanceof Array
      for i in [0...value.length]
        subValue = value[i]
        fullSubName = name + '[' + i + ']'
        innerObj = {}
        innerObj[fullSubName] = subValue
        query += param(innerObj) + '&'
    else if value instanceof Object
      for own subName of value
        subValue = value[subName]
        fullSubName = name + '[' + subName + ']'
        innerObj = {}
        innerObj[fullSubName] = subValue
        query += param(innerObj) + '&'
    else if value?
      query += encodeURIComponent(name) + '=' + encodeURIComponent(value) + '&'

  if query.length then query.substr(0, query.length - 1) else query

angular.module('astroApp').config [
  '$httpProvider'
  '$locationProvider'
  '$qProvider'
  ($httpProvider, $locationProvider, $qProvider)->
    $httpProvider.defaults.xsrfCookieName = 'csrftoken'
    $httpProvider.defaults.xsrfHeaderName = 'X-CSRFToken'
    $httpProvider.defaults.withCredentials = true
    $httpProvider.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest"
    $httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded'
    $httpProvider.defaults.headers.put['Content-Type'] = 'application/x-www-form-urlencoded'
    $httpProvider.defaults.transformRequest = [
      (data)->
        if angular.isObject(data) && String(data) != '[object File]' then param(data) else data
    ]
    $httpProvider.interceptors.push 'responseObserver'

    $locationProvider.html5Mode true
    $qProvider.errorOnUnhandledRejections false
]