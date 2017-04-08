import React, { Component, PropTypes } from 'react';
import { render } from 'react-dom';

import UserNav from "./UserNav.jsx"
import UserLoginForm from './UserLoginForm.jsx'

import { login, logout, toggleUserDropdown } from '../../reducers/user/actions.jsx'

export default class User extends Component {
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

  componentDidMount() {
    const { store } = this.context;
    this.unsubscribe = store.subscribe(() =>
      this.forceUpdate()
    );
  }

  componentWillUnmount() {
    this.unsubscribe();
  }

  render() {
    const props = this.props;
    const { store } = this.context;
    const state = store.getState();
    const user = state.user;

    return (
      user.id ?
      <UserNav
        user={ user }
        dropdownOpen={ user.dropdownOpen }
        onToggle={() =>
          store.dispatch(toggleUserDropdown())
        }
        onLogout={() =>
          store.dispatch(logout())
        }
      />
      :
      <UserLoginForm
        loginError={ user.loginError }

        username={ this.state.username }
        password={ this.state.password }
        usernameChange={ this.handleUsernameChange }
        passwordChange={ this.handlePasswordChange }

        onLogin={() =>
          store.dispatch(login(this.state.username, this.state.password))
        }
      />
    )
  }
}
User.contextTypes = {
  store: PropTypes.object
};