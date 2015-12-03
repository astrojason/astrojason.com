angular.module('astroApp').service 'UserService', ->
  this_user = {}

  setUser = (user)->
    this_user = user

  getUser = ->
    this_user

  return { setUser: setUser, getUser: getUser }