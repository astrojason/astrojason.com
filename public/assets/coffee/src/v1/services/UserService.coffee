angular.module('astroApp').service 'UserService', [->
  UserService =
    this_user: {}

    set: (user)->
      @.this_user = user

    get: ->
      @.this_user

  return UserService
]