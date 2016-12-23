<?php
/**
 * User: jasonsylvester
 * Date: 11/18/16
 * Time: 4:11 PM
 */

namespace Api;

use Account, Auth, Carbon\Carbon, Balance, Input;

class CreditController extends AstroBaseController {

  public function report() {
    $accountsQuery = Account::where('user_id', Auth::user()->id)
      ->with('Balances')
      ->get();

    $chart = [];
    $accounts = [];
    foreach ($accountsQuery as $account) {
      $current_balance = null;
      foreach ($account->balances as $balance) {
        if($balance->created_at >= Carbon::parse(date('Y-m-d', strtotime("-1 years")))) {
          if(!$current_balance || $current_balance->created_at < $balance->created_at){
            $current_balance = $balance;
          }
          $chart[] = [
            'Date' => $balance->created_at->toDateString(),
            'Account' => $account->name,
            'Amount' => $balance->amount
          ];
        }
      }
      $accounts[] = [
        'name' => $account->name,
        'limit' => $account->limit,
        'current_balance' => $current_balance->amount,
        'last_update' => $current_balance->created_at->toDateString()
      ];
    }

    return $this->successResponse([
      'chart' => $chart,
      'accounts' => $accounts
    ]);
  }

  public function add() {
    $newAccount = new Account();
    $newAccount->user_id = Auth::user()->id;
    $newAccount->name = Input::get('name');
    $newAccount->limit = Input::get('limit');
    $newAccount->save();
    $newBalance = new Balance();
    $newBalance->account_id = $newAccount->id;
    $newBalance->amount = Input::get('balance', 0);
    $newBalance->save();
    $newAccount->balances = [
      $newBalance
    ];
    return $this->successResponse(['account' => $newAccount]);
  }

  public function save($id) {
    $newBalance = new Balance();
    $newBalance->account_id = $id;
    $newBalance->amount = Input::get('newBalance', 0);
    $newBalance->save();
    return $this->successResponse(['balance' => $newBalance]);
  }

  public function transform($item) {
    // TODO: Implement transform() method.
  }

}