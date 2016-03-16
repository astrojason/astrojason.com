@extends('layouts/layout')

@section('title')
  Registration
@stop

@section('content')
  <h1>User Registration</h1>
    <div ng-controller="UserController">
    <form
      role="form"
      name="registrationForm"
      ng-submit="registerUser()"
      ng-show="!registrationSuccess">
      <div class="row">
        <div class="form-group col-md-6">
          <label class="sr-only" for="first_name">First Name</label>
          <input type="text" class="form-control" placeholder="First Name" ng-model="user.first_name" required />
        </div>
        <div class="form-group col-md-6">
          <label class="sr-only" for="last_name">Last Name</label>
          <input type="text" class="form-control" placeholder="Last Name" ng-model="user.last_name" required />
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-6">
          <label class="sr-only" for="email">Email Address</label>
          <input type="email" name="email" class="form-control" placeholder="Email address" ng-model="user.email" required check-availability />
          <div ng-show="registrationForm.email.$error.unique"><span class="error">Email address already in use</span>&nbsp;</div>
        </div>
        <div class="form-group col-md-6">
          <label class="sr-only" for="username">Username</label>
          <input type="text" name="username" class="form-control" placeholder="Username" ng-model="user.username" required check-availability />
          <div ng-show="registrationForm.username.$error.unique"><span class="error">Username already in use</span>&nbsp;</div>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-6">
          <label class="sr-only" for="password">Password</label>
          <input type="password" class="form-control" placeholder="Password" ng-model="user.password" required />
        </div>
        <div class="form-group col-md-6">
          <label class="sr-only" for="confirm_password">Confirm Password</label>
          <input type="password" class="form-control" placeholder="Confirm Password" ng-model="confirm_password" required compare-to="user.password" />
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-12">
          <input type="submit" class="btn" value="Register" ng-disabled="!registrationForm.$valid" ng-class="(!registrationForm.$valid) ? 'btn-disabled' : 'btn-success'">
        </div>
      </div>
    </form>
  </div>
@stop
