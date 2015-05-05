window.app.directive 'astroModal', ['$filter', ($filter)->
  restrict: 'A',
  scope: {
    modalVisible: '='
  }
  link: (scope, element, attributes)->
    scope.$watch 'modalVisible', ->
      if scope.modalVisible
        element.modal 'show'
      else
        element.modal 'hide'

    element.on 'hidden.bs.modal', ->
      scope.$apply ->
        scope.modalVisible = false
]