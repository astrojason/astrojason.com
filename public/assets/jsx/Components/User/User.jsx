import React from 'react';
import {render} from 'react-dom';
import {Dropdown, DropdownMenu, DropdownItem} from 'reactstrap';

export default class User extends React.Component {
  constructor(props) {
    super(props);

    this.toggle = this.toggle.bind(this);
    this.handleUsernameChange = this.handleUsernameChange.bind(this);
    this.handlePasswordChange = this.handlePasswordChange.bind(this);
    this.state = {
      dropdownOpen: false,
      username: '',
      password: ''
    };
  }

  toggle() {
    this.setState({
      dropdownOpen: !this.state.dropdownOpen
    });
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
    let user = userStore.getState();
    if (user.id) {
      return <div className="d-flex flex-row-reverse">
        <div className="p-2">
          <Dropdown
            isOpen={this.state.dropdownOpen}
            toggle={this.toggle}>
            <a
              className="dropdown-toggle"
              data-toggle="dropdown"
              onClick={this.toggle}
              aria-expanded="false">
              {user.firstname}
            </a>
            <DropdownMenu right>
              <DropdownItem>Settings</DropdownItem>
              <DropdownItem onClick={() =>
                userStore.dispatch({
                  type: "LOGOUT",
                })
              }>Log Out</DropdownItem>
            </DropdownMenu>
          </Dropdown>
        </div>
      </div>
    } else {
      return <div className="d-flex justify-content-center align-items-center login">
        <div className="card">
          <div className="card-header text-center">
            Log In
          </div>
          <div className="card-block">
            <div className="form-group">
              <label htmlFor="email">Username:</label>
              <input
                type="username"
                id="username"
                className="form-control"
                value={this.state.username}
                onChange={this.handleUsernameChange}/>
            </div>
            <div className="form-group">
              <label htmlFor="password">Password:</label>
              <input
                type="password"
                id="password"
                className="form-control"
                value={this.state.password}
                onChange={this.handlePasswordChange}/>
            </div>
            <button
              className="btn btn-block btn-primary"
              onClick={() =>
                userStore.dispatch({
                  type: "LOGIN",
                  username: this.state.username,
                  password: this.state.password
                })
              }>Login
            </button>
            <button className="btn btn-block btn-link">Register</button>
          </div>
        </div>
      </div>
    }
  }
}