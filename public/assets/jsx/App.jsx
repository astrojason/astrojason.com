import React from 'react';
import { render } from 'react-dom';
import { createStore, applyMiddleware } from 'redux';
import thunkMiddleware from 'redux-thunk'

import ArticleList from './Components/Article/ArticleList.jsx'
import User from './Components/User/User.jsx'
import Chains from './Components/Habit/Chain.jsx'
import Tasks from './Components/Task/Tasks.jsx'
import Practice from './Components/Guitar/Practice.jsx'
import Songs from './Components/Guitar/Song.jsx'
import Books from './Components/Book/Book.jsx'

import articles from './reducers/articles.jsx'
import userApp from './reducers/user/reducers.jsx'

import { receivedLogin } from './reducers/user/actions.jsx'

let articlesApp = createStore(articles);
let userStore = createStore(
  userApp,
  applyMiddleware(thunkMiddleware)
);

let renderArticles = () => {
  render(<ArticleList title="Today's Links" articles={articlesApp}/>, document.getElementById('articles-root'));
};

let renderChains = () => {
  render(<Chains />, document.getElementById('chain-root'));
};

let renderTasks = () => {
  render(<Tasks />, document.getElementById('tasks-root'));
};

let renderPractice = () => {
  render(<Practice />, document.getElementById('practice-root'));
};

let renderSongs = () => {
  render(<Songs />, document.getElementById('songs-root'));
};

let renderBooks = () => {
  render(<Books />, document.getElementById('books-root'));
};

articlesApp.subscribe(renderArticles);

let renderApp = () => {
  renderArticles();
  renderChains();
  renderTasks();
  renderPractice();
  renderSongs();
  renderBooks();
};

let renderUser = () => {
  let user = userStore;
  render(<User user={user}/>, document.getElementById('user-root'));
  if (user.getState().id) {
    renderApp();
  }
};

userStore.subscribe(renderUser);

window.onload = () => {
  let userRoot = document.getElementById('user-root');
  let userData = userRoot.dataset;
  let user = {};
  if (userData.user) {
    user = JSON.parse(userData.user);
  }
  userStore.dispatch(receivedLogin(user));
};