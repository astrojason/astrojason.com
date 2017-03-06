@extends('layouts.layout')

@section('title')
  Credit
@stop

@section('content')
  <div ng-controller="CreditController" ng-init="initList()">
    <div class="row" ng-hide="accounts.length == 0">
      <div id="chart_container" class="col-md-12"></div>
    </div>
    <div class="row">
      <div class="col-md-12">
        <table class="table table-condensed table-striped table-hover">
          <tbody>
            <tr ng-repeat="account in accounts">
              <td>
                <span
                  class="glyphicon glyphicon-edit"
                  style="margin-left: 10px"
                  ng-click="disableAccount(account)">
                </span>
              </td>
              <td>{{ account.name }}</td>
              <td>{{ account.limit | currency }}</td>
              <td>
                <div ng-click="account.editing = true" ng-hide="account.editing">
                  {{ account.current_balance | currency }}
                  (<small ng-hide="!account.current_balance">
                    {{ (account.current_balance / account.limit) * 100 | number:0 }}%
                  </small>)
                </div>
                <div class="form-inline" ng-show="account.editing">
                  <input class="form-control" ng-model="account.newBalance" placeholder="{{ account.current_balance }}" />
                  <button class="btn btn-primary" ng-click="updateBalance(account)">Save</button>
                  <button class="btn btn-default" ng-click="account.editing = false">Cancel</button>
                </div>
              </td>
              <td>
                <span
                  class="glyphicon glyphicon-ban-circle text-danger pull-right"
                  style="margin-left: 10px"
                  ng-click="disableAccount(account)">
                </span>
                <div class="pull-right">
                  {{ account.last_update | date:'shortDate' }}
                </div>
              </td>
            </tr>
          </tbody>
          <tfoot>
            <form name="credit_account_form" novalidate>
              <tr>
                <td><input type="text" ng-model="newAccount.name" class="form-control" required /></td>
                <td>
                  <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="number" ng-model="newAccount.limit" class="form-control" aria-label="Limit" required />
                  </div>
                </td>
                <td>
                  <div class="input-group">
                    <span class="input-group-addon">$</span>
                    <input type="number" ng-model="newAccount.balance" class="form-control" aria-label="Current Balance" />
                  </div>
                </td>
                <td class="pull-right">
                  <button
                    class="btn btn-primary"
                    ng-click="addAccount()">
                    Add
                  </button>
                  <button class="btn btn-default" ng-click="resetAccount()">Cancel</button>
                </td>
              </tr>
            </form>
          </tfoot>
        </table>
      </div>
  </div>
@stop