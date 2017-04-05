import {
  CONFIRM_DELETE,
  DELETE,
  EDIT,
  READ,
  LOADING_ARTICLES,
  POSTPONE,
  RECEIVE_ARTICLES,
  RECEIVE_CATEGORIES,
  UPDATE,
  removeArticleFromList,
  toggleArticleDelete,
  toggleArticleEdit,
  updateArticle
} from './actions.jsx'

const initialState = {
  loading: true,
  list: [],
  categories: [],
  fetched: false
};

const articles = (state = initialState, action)=> {
  switch(action.type) {
    case CONFIRM_DELETE:
      let articles = toggleArticleDelete(state.list, action.article);
      return Object.assign({}, state, {
        list: articles
      });
    case DELETE:
      articles = removeArticleFromList(state.list, action.article);
      return Object.assign({}, state, {
        list: articles
      });
    case EDIT:
      articles = toggleArticleEdit(state.list, action.article);
      return Object.assign({}, state, {
        list: articles
      });
    case LOADING_ARTICLES:
      return Object.assign({}, state, {
        list: [],
        loading: true
      });
    case POSTPONE:
      articles = removeArticleFromList(state.list, action.article);
      return Object.assign({}, state, {
        list: articles
      });
    case READ:
      if(action.remove) {
        articles = removeArticleFromList(state.list, action.article);
        return Object.assign({}, state, {
          list: articles
        });
      } else {
        return state;
      }
    case RECEIVE_ARTICLES:
      return Object.assign({}, state, {
        list: action.articles,
        loading: false,
        fetched: true
      });
    case RECEIVE_CATEGORIES:
      return Object.assign({}, state, {
        categories: action.categories
      });
    case UPDATE:
      return Object.assign({}, state, {
        list: updateArticle(state.list, action.article)
      });
    default:
      return state;
  }
};

export default articles;