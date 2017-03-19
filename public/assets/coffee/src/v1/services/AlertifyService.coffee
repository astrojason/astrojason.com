angular.module('astroApp').service 'AlertifyService', ->
  success: (message)->
    alertify.success message

  error: (message)->
    alertify.error message
