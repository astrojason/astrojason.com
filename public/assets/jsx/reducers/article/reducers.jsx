import { DELETE, READ, LOADING_ARTICLES, POSTPONE, RECEIVE_ARTICLES } from './actions.jsx'

const initialState = [
  {
    id: 1,
    title: 'This is my article',
    url: 'http://www.google.com',
    read: false
  },
  {
    id: 2,
    title: 'This is my second article',
    url: 'http://www.yahoo.com',
    read: false
  },
  {
    id: 3,
    title: 'This is my third article',
    url: 'http://www.msn.com',
    read: false
  },
  {
    id: 4,
    title: 'This is my fourth article',
    url: 'http://www.cnn.com',
    read: false
  }
];

const removeArticleFromList = (current_list, article_to_remove) => {
  return current_list.filter(function (state_article) {
    return state_article.id != article_to_remove.id;
  });
};

const articles = (state = initialState, action)=> {
  switch(action.type) {
    case DELETE:
      console.log(`Deleting ${action.article.id}`);
      return removeArticleFromList(state, action.article);
    case LOADING_ARTICLES:
      console.log(`Received ${LOADING_ARTICLES}`);
      return state;
    case POSTPONE:
      console.log(`Marking ${action.article.id} as postponed`);
      return removeArticleFromList(state, action.article);
    case READ:
      console.log(`Marking ${action.article.id} as read`);
      return removeArticleFromList(state, action.article);
    case RECEIVE_ARTICLES:
      return Object.assign({}, state, action.articles);
    default:
      return state;
  }
};

export default articles;