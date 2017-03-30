const defaultOpts = {
  method: 'POST',
  credentials: "same-origin",
  headers: new Headers({
    'Content-Type': 'application/json'
  })
};

const loginSuccess = (response) => {
  response.json().then((json) => {
    receivedLogin(json.user);
  });
};

const receivedLogin = (user) => {
  console.log(user);
};

const loginFailure = (response) => {
  console.log('Login failure', response);
};

const user = (state = {}, action) => {
  switch (action.type) {
    case "LOGIN":
      let opts = Object.assign({}, defaultOpts, {
        body: JSON.stringify({
          username: action.username,
          password: action.password
        })
      });
      fetch('/api/user/login', opts).then(loginSuccess, loginFailure);
      return state;
    case "LOGOUT":
      return fetch('/api/user/logout', defaultOpts).then(function () {
        state = {};
        return state;
      });
    case "RETURN":
      state = action.user;
      return state;
    default:
      return state;
  }
};

export default user;