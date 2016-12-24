angular.module('astroApp').controller 'CreditController', [
  '$scope'
  '$log'
  '$timeout'
  'CreditResource'
  ($scope,
    $log,
    $timeout,
    CreditResource)->

    $scope.basicAccount =
      name: null
      limit: null
      balance: null

    $scope.newAccount = angular.copy $scope.basicAccount

    $scope.initList = ->
      report_promise = CreditResource.report().$promise

      report_promise.then (response)->
        $scope.accounts = response.accounts
        svg = dimple.newSvg '#chart_container', 1020, 600
        myChart = new dimple.chart svg, response.chart
        myChart.setBounds 60, 30, 960, 510
        myChart.addLegend 65, 10, 960, 20, "right"
        x = myChart.addCategoryAxis "x", 'Date'
        x.addOrderRule 'Date'
        y = myChart.addMeasureAxis "y", 'Percentage'
        myChart.addSeries 'Account', dimple.plot.line
        myChart.draw()

    $scope.updateBalance = (account)->
      save_promise = CreditResource.save(account).$promise
      save_promise.then ->
        account.current_balance = account.newBalance
        account.last_update = new Date()
        account.newBalance = ''
        account.editing = false

    $scope.addAccount = ->
      delete $scope.newAccount.error
      $scope.newAccount.saving = true

      account_promise = CreditResource.add($scope.newAccount).$promise

      account_promise.then (account)->
        account.isNew = true
        $scope.accounts.unshift account
        $timeout ->
          delete account.isNew
        $scope.resetAccount()

      account_promise.catch ->
        $scope.newAccount.error = true

      account_promise.finally ->
        delete $scope.newAccount.saving

    $scope.disableAccount = (account, index)->
      promise = CreditResource.disable(account).$promise
      promise.then ->
        $scope.accounts.splice index, 1

    $scope.resetAccount = ->
      $scope.newAccount = angular.copy $scope.basicAccount
      $scope.credit_account_form.$setPristine()

]