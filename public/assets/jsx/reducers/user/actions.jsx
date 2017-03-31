export const RECEIVED_LOGIN = "RECEIVED_LOGIN";
export const LOGIN_ERROR = "LOGIN_ERROR";
export const TOGGLE_DROPDOWN = "TOGGLE_DROPDOWN";

const defaultOpts = {
  method: 'POST',
  credentials: "same-origin",
  headers: new Headers({
    'Content-Type': 'application/json'
  })
};

const loginError = ()=> {
  return {
    type: LOGIN_ERROR
  }
};

export const login = (username, password) => {
  return (dispatch) => {
    let opts = Object.assign({}, defaultOpts, {
      body: JSON.stringify({
        username: username,
        password: password
      })
    });
    return fetch('/api/user/login', opts).then((response) => {
      if(response.status != 200) { // Magic numbers are BAD
        dispatch(loginError())
      } else {
        response.json().then((json) => {
          dispatch(receivedLogin(json.user));
        });
      }
    });
  };
};

export const logout = () => {
  return (dispatch) => {
    return fetch('/api/user/logout', defaultOpts).then(() => {
      dispatch(receivedLogin({}));
    });
  }
};

export const receivedLogin = (user) => {
  return {
    type: RECEIVED_LOGIN,
    user
  }
};

export const toggleUserDropdown = () => {
  return {
    type: TOGGLE_DROPDOWN
  }
};