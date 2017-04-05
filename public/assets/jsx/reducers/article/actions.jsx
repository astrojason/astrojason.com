export const ARTICLES_ERROR = "ARTICLES_ERROR";
export const CONFIRM_DELETE = "CONFIRM_DELETE";
export const DELETE = "DELETE";
export const EDIT = "EDIT";
export const LOADING_ARTICLES = "LOADING_ARTICLES";
export const POSTPONE = "POSTPONE";
export const READ = "READ";
export const RECEIVE_ARTICLES = "RECEIVE_ARTICLES";
export const RECEIVE_CATEGORIES = "RECEIVE_CATEGORIES";
export const UPDATE = "UPDATE";

const HTTP_OK = 200;

const defaultOpts = {
  method: 'GET',
  credentials: "same-origin",
  headers: new Headers({
    'Content-Type': 'application/json'
  })
};

const articlesError = () => {
  return {
    type: ARTICLES_ERROR
  }
};

export const loadingArticles = () => {
  return {
    type: LOADING_ARTICLES
  }
};

export const readArticle = (article, remove) => {
  return {
    type: READ,
    article,
    remove
  }
};

export const receivedArticles = (articles) => {
  return {
    type: RECEIVE_ARTICLES,
    articles
  }
};

export const receivedCategories = (categories) => {
  return {
    type: RECEIVE_CATEGORIES,
    categories
  }
};

export const confirmArticleDelete = (article) => {
  return {
    type: CONFIRM_DELETE,
    article
  }
};

export const editArticle = (article) => {
  return {
    type: EDIT,
    article
  }
};

export const toggleArticleEdit = (current_list, article_to_toggle) => {
  return current_list.map((article)=>{
    if(article.id == article_to_toggle.id){
      article.editingMode = !article.editingMode;
    }
    return article;
  });
};

export const toggleArticleDelete = (current_list, article_to_toggle) => {
  return current_list.map((article)=>{
    if(article.id == article_to_toggle.id){
      article.confirmDelete = !article.confirmDelete;
    }
    return article;
  });
};

export const fetchDailyArticles = () => {
  return (dispatch) => {
    return fetch('/api/article/daily', defaultOpts).then((response) => {
      if(response.status != HTTP_OK) {
        dispatch(articlesError())
      } else {
        response.json().then((json) => {
          dispatch(receivedArticles(json.articles));
        });
      }
    });
  }
};

export const fetchArticleCategories = () => {
  return (dispatch) => {
    return fetch('/api/article/category', defaultOpts).then((response) => {
      if(response.status != HTTP_OK) {
        dispatch(articlesError())
      } else {
        response.json().then((json) => {
          let categories = json.categories.map((category) => {
            return {
              value: category.id,
              label: category.name
            }
          });
          dispatch(receivedCategories(categories));
        });
      }
    });
  }
};

export const markArticleRead = (article, remove) => {
  return (dispatch) => {
    return fetch(`/api/article/${article.id}/read`, defaultOpts).then((response) => {
      if(response.status != HTTP_OK) {
        dispatch(articlesError())
      } else {
        dispatch(readArticle(article, remove));
      }
    });
  }
};

export const postponeArticle = (article) => {
  return (dispatch) => {
    return fetch(`/api/article/${article.id}/postpone`, defaultOpts).then((response) => {
      if(response.status != HTTP_OK) {
        dispatch(articlesError())
      } else {
        dispatch({
          type: POSTPONE,
          article
        });
      }
    });
  }
};

export const saveArticle = (article) => {
  return (dispatch) => {
    let opts = Object.assign({}, defaultOpts, {
      body: JSON.stringify(article),
      method: 'POST'
    });
    return fetch(`/api/article/${article.id}`, opts).then((response) => {
      if(response.status != HTTP_OK) {
        dispatch(articlesError())
      } else {
        dispatch({
          type: UPDATE,
          article
        });
      }
    });
  }
};

export const deleteArticle = (article) => {
  let opts = Object.assign({}, defaultOpts, {method: 'DELETE'});
  return (dispatch) => {
    return fetch(`/api/article/${article.id}`, opts).then((response) => {
      if(response.status != HTTP_OK) {
        dispatch(articlesError())
      } else {
        dispatch({
          type: DELETE,
          article
        });
      }
    });
  }
};

export const removeArticleFromList = (current_list, article_to_remove) => {
  return current_list.filter(function (state_article) {
    return state_article.id != article_to_remove.id;
  });
};

export const updateArticle = (current_list, article_to_update) => {
  article_to_update.editingMode = false;
  return current_list.map(function (state_article) {
    if(state_article.id != article_to_update.id) {
      return state_article;
    } else {
      return article_to_update;
    }
  });
};