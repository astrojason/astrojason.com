import { LOGIN_ERROR, RECEIVED_LOGIN } from './actions.jsx'

const initialState = {};

const userApp = (state = initialState, action)=> {
  switch(action.type) {
    case LOGIN_ERROR:
      return Object.assign({}, state, {
        loginError: true
      });
    case RECEIVED_LOGIN:
      state = action.user;
      return Object.assign({}, state, action.user);
    default:
      return state;
  }
};

export default userApp;