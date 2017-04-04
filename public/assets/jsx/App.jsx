import React from 'react';
import { render } from 'react-dom';
import { createStore, applyMiddleware, combineReducers } from 'redux';
import thunkMiddleware from 'redux-thunk'
import { Provider } from 'react-redux'

import ArticleList from './Components/Article/ArticleList.jsx'
import User from './Components/User/User.jsx'
import Chains from './Components/Habit/Chain.jsx'
import Tasks from './Components/Task/Tasks.jsx'
import Practice from './Components/Guitar/Practice.jsx'
import Songs from './Components/Guitar/Song.jsx'
import Books from './Components/Book/Book.jsx'

import articles from './reducers/article/reducers.jsx'
import user from './reducers/user/reducers.jsx'

import { fetchDailyArticles } from './reducers/article/actions.jsx'

let astroApp = combineReducers({
  user,
  articles
});

const createAstroStore = (user) => (
  createStore(
    astroApp,
    {
      user: user
    },
    applyMiddleware(thunkMiddleware)
  )
);

const AstroApp = () => (
  <div>
    <User />
    <div className="container-fluid">
      <div className="row">
        <ArticleList
          title="Today's Links"
          articleQuery={()=>
            fetchDailyArticles()
          }
          removeRead={ true } />
        <div className="col-4">
          <Chains />
          <Tasks />
        </div>
        <div className="col-4">
          <Practice />
          <Songs />
          <Books />
        </div>
      </div>
    </div>
  </div>
);

window.onload = () => {
  let userRoot = document.getElementById('react-root');
  let userData = userRoot.dataset;
  let user = {};
  if (userData.user) {
    user = JSON.parse(userData.user);
  }
  render(
    <Provider store={createAstroStore(user)}>
      <AstroApp />
    </Provider>,
    document.getElementById('react-root')
  );
};