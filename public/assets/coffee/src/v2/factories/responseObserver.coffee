angular.module('astroApp').factory 'responseObserver', [
  '$q'
  '$log'
  '$window'
  ($q, $log, $window)->
    'responseError': (errorResponse)->
      switch errorResponse.status
        when 403 then $window.location = '/'
        else $log.debug errorResponse
      $q.reject(errorResponse);
]