@extends('layouts/layout')

@section('title')
  Registration
@stop

@section('content')
  <div ng-controller="UserController">
    <h1>User Registration</h1>
    <div ng-show="registrationSuccess" class="alert alert-success">
      You have successfully created your account, go ahead and log in now!
    </div>
    <form
      role="form"
      name="registrationForm"
      ng-submit="registerUser()"
      ng-show="!registrationSuccess">
      <div class="row">
        <div class="form-group col-md-6">
          <label class="sr-only" for="first_name">First Name</label>
          <input
            type="text"
            name="first_name"
            id="first_name"
            class="form-control"
            placeholder="First Name"
            ng-model="user.first_name"
            required />
        </div>
        <div class="form-group col-md-6">
          <label class="sr-only" for="last_name">Last Name</label>
          <input
            type="text"
            name="last_name"
            id="last_name"
            class="form-control"
            placeholder="Last Name"
            ng-model="user.last_name"
            required />
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-6">
          <label class="sr-only" for="email">Email Address</label>
          <input
            type="email"
            name="email"
            id="email"
            class="form-control"
            placeholder="Email address"
            ng-model="user.email"
            required
            check-availability />
          <div id="emailInUse" ng-show="registrationForm.email.$error.unique">
            <span class="error">Email address already in use</span>
          </div>
        </div>
        <div class="form-group col-md-6">
          <label class="sr-only" for="username">Username</label>
          <input
            type="text"
            id="username"
            name="username"
            class="form-control"
            placeholder="Username"
            ng-model="user.username"
            required
            check-availability />
          <div
            id="usernameInUse"
            ng-show="registrationForm.username.$error.unique">
            <span class="error">Username already in use</span>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-6">
          <label class="sr-only" for="password">Password</label>
          <input
            type="password"
            name="password"
            id="password"
            class="form-control"
            placeholder="Password"
            ng-model="user.password"
            required />
        </div>
        <div class="form-group col-md-6">
          <label class="sr-only" for="confirm_password">Confirm Password</label>
          <input
            type="password"
            name="confirm_password"
            id="confirm_password"
            class="form-control"
            placeholder="Confirm Password"
            ng-model="confirm_password"
            required
            compare-to="user.password" />
        </div>
      </div>
      <div class="row">
        <div class="form-group col-md-12">
          <input
            id="registerSubmit"
            name="registerSubmit"
            type="submit"
            class="btn"
            value="Register"
            ng-disabled="!registrationForm.$valid"
            ng-class="(!registrationForm.$valid) ? 'btn-disabled' : 'btn-success'">
        </div>
      </div>
    </form>
  </div>
@stop
