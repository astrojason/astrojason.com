import React from 'react';

const UserLoginForm = ({
  loginError,
  username,
  password,
  usernameChange,
  passwordChange,
  onLogin
}) => (
    <div className="d-flex justify-content-center align-items-center login">
        <div className="card">
          <div className="card-header text-center">
            Log In
          </div>
          <div className="card-block">
            {
              loginError ?
                <div className="alert alert-danger">Unable to log you in</div>
                :
                ''
            }
            <div className="form-group">
              <label htmlFor="email">Username:</label>
              <input
                type="username"
                id="username"
                className="form-control"
                value={ username }
                onChange={ usernameChange } />
            </div>
            <div className="form-group">
              <label htmlFor="password">Password:</label>
              <input
                type="password"
                id="password"
                className="form-control"
                value={ password }
                onChange={ passwordChange } />
            </div>
            <button
              className="btn btn-block btn-primary"
              onClick={ onLogin }>
              Login
            </button>
            <button className="btn btn-block btn-link">Register</button>
          </div>
        </div>
      </div>
);

export default UserLoginForm;