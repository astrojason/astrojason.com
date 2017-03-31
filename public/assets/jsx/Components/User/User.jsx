import React from 'react';
import { render } from 'react-dom';

import UserNav from "./UserNav.jsx"
import UserLoginForm from './UserLoginForm.jsx'

import { login, logout, toggleUserDropdown } from '../../reducers/user/actions.jsx'

export default class User extends React.Component {
  constructor(props) {
    super(props);

    this.handleUsernameChange = this.handleUsernameChange.bind(this);
    this.handlePasswordChange = this.handlePasswordChange.bind(this);
    this.state = {
      username: '',
      password: ''
    };
  }

  handleUsernameChange(e) {
    this.setState({
      username: e.target.value
    });
  }

  handlePasswordChange(e) {
    this.setState({
      password: e.target.value
    });
  }

  render() {
    let userStore = this.props.user;
    let user = userStore.getState().user;
    if (user.id) {
      return <UserNav
        user={user}
        dropdownOpen={user.dropdownOpen}
        onToggle={() =>
            userStore.dispatch(toggleUserDropdown())
        }
        onLogout={() =>
          userStore.dispatch(logout())
        }
        />
    } else {
      return <UserLoginForm
        loginError={user.loginError}

        username={this.state.username}
        password={this.state.password}
        usernameChange={this.handleUsernameChange}
        passwordChange={this.handlePasswordChange}

        onLogin={() =>
          userStore.dispatch(login(this.state.username, this.state.password))
        }
      />
    }
  }
}