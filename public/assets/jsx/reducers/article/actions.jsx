export const READ = "READ";
export const DELETE = "DELETE";
export const POSTPONE = "POSTPONE";
export const LOADING_ARTICLES = "LOADING_ARTICLES";
export const RECEIVE_ARTICLES = "RECEIVE_ARTICLES";
export const ARTICLES_ERROR = "ARTICLES_ERROR";

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

export const receivedArticles = (articles) => {
  return {
    action: RECEIVE_ARTICLES,
    articles
  }
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
