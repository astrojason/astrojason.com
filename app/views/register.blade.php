@extends('layouts/layout')

@section('title')
  Registration
@stop

@section('content')
  <h1>User Registration</h1>
  <form role="form" name="registrationForm" class="form-inline" ng-submit="registerUser()" ng-controller="UserController">
    <div class="form-group">
      <label class="sr-only" for="first_name">First Name</label>
      <input type="text" class="form-control" placeholder="First Name" ng-model="first_name" required />
      <div>&nbsp;</div>
    </div>
    <div class="form-group">
      <label class="sr-only" for="last_name">Last Name</label>
      <input type="text" class="form-control" placeholder="Last Name" ng-model="last_name" required />
      <div>&nbsp;</div>
    </div>
    <div class="form-group">
      <label class="sr-only" for="email">Email Address</label>
      <input type="email" name="email" class="form-control" placeholder="Email address" ng-model="email" required check-availability />
      <div><span ng-show="registrationForm.email.$error.unique" class="error">Email address already in use</span>&nbsp;</div>
    </div>
    <div class="form-group">
      <label class="sr-only" for="username">Username</label>
      <input type="text" name="username" class="form-control" placeholder="Username" ng-model="username" required check-availability />
      <div><span ng-show="registrationForm.username.$error.unique" class="error">Username already in use</span>&nbsp;</div>
    </div>
    <div class="form-group">
      <label class="sr-only" for="password">Password</label>
      <input type="password" class="form-control" placeholder="Password" ng-model="password" required />
      <div>&nbsp;</div>
    </div>
    <div class="form-group">
      <label class="sr-only" for="confirm_password">Confirm Password</label>
      <input type="password" class="form-control" placeholder="Confirm Password" ng-model="confirm_password" required compare-to="password" />
      <div>&nbsp;</div>
    </div>
    <div class="form-group">
      <input type="submit" class="btn" value="Register" ng-disabled="!registrationForm.$valid" ng-class="(!registrationForm.$valid) ? 'btn-disabled' : 'btn-success'">
    </div>
  </form>
@stop
