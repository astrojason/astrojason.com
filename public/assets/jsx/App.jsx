import React from 'react';
import {render} from 'react-dom';
import {createStore} from 'redux';

import ArticleList from './Components/Article/ArticleList.jsx'
import User from './Components/User/User.jsx'
import Chains from './Components/Habit/Chain.jsx'
import Tasks from './Components/Task/Tasks.jsx'
import Practice from './Components/Guitar/Practice.jsx'
import Songs from './Components/Guitar/Song.jsx'
import Books from './Components/Book/Book.jsx'

import articles from './reducers/articles.jsx'
import user from './reducers/user.jsx'

let articlesApp = createStore(articles);
let userApp = createStore(user);

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
  let user = userApp;
  render(<User user={user}/>, document.getElementById('user-root'));
  if (user.getState().id) {
    renderApp();
  }
};

userApp.subscribe(renderUser);

window.onload = () => {
  let userRoot = document.getElementById('user-root');
  let userData = userRoot.dataset;
  let user = {};
  if (userData.user) {
    user = JSON.parse(userData.user);
  }
  userApp.dispatch({
    type: "RETURN",
    user: user
  });
};