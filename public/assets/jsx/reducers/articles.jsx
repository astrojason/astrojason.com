let articlesList = [
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

const removeArticleFromList = (state, article) => {
  return state.filter(function (state_article) {
    return state_article.id != article.id;
  });
};

const articleReducers = (state = articlesList, action) => {
  switch (action.type) {
    case 'READ':
      console.log(`Marking ${action.article.id} as read`);
      return removeArticleFromList(state, action.article);
    case 'DELETE':
      console.log(`Deleting ${action.article.id}`);
      return removeArticleFromList(state, action.article);
    case 'POSTPONE':
      console.log(`Marking ${action.article.id} as postponed`);
      return removeArticleFromList(state, action.article);
    default:
      return state;
  }
};

export default articleReducers;