export const ARTICLES_ERROR = "ARTICLES_ERROR";
export const CONFIRM_DELETE = "CONFIRM_DELETE";
export const DELETE = "DELETE";
export const EDIT = "EDIT";
export const LOADING_ARTICLES = "LOADING_ARTICLES";
export const POSTPONE = "POSTPONE";
export const READ = "READ";
export const RECEIVE_ARTICLES = "RECEIVE_ARTICLES";

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

export const readArticle = (article) => {
  return {
    type: READ,
    article
  }
};

export const receivedArticles = (articles) => {
  return {
    type: RECEIVE_ARTICLES,
    articles
  }
};

export const confirmArticleDelete = (article) => {
  return {
    type: CONFIRM_DELETE,
    article
  }
};

export const deleteArticle = (article) => {
  return {
    type: DELETE,
    article
  }
};

export const editArticle = (article) => {
  return {
    type: EDIT,
    article
  }
};

export const postponeArticle = (article) => {
  return {
    type: POSTPONE,
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
  console.log('About to fetch the articles');
  return (dispatch) => {
    dispatch(loadingArticles());
    return fetch('/api/article/daily', defaultOpts).then((response) => {
      if(response.status != 200) { // Magic numbers are BAD
        dispatch(articlesError())
      } else {
        response.json().then((json) => {
          dispatch(receivedArticles(json.articles));
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
