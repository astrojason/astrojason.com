import { LOGIN_ERROR, RECEIVED_LOGIN, TOGGLE_DROPDOWN } from './actions.jsx'

const initialState = {
  dropdownOpen: false,
  loginError: false
};

const user = (state = initialState, action)=> {
  switch(action.type) {
    case LOGIN_ERROR:
      return Object.assign({}, state, {
        loginError: true
      });
    case RECEIVED_LOGIN:
      state = action.user;
      return Object.assign({}, state, action.user);
    case TOGGLE_DROPDOWN:
      return Object.assign({}, state, {
        dropdownOpen: !state.dropdownOpen
      });
    default:
      return state;
  }
};

export default user;