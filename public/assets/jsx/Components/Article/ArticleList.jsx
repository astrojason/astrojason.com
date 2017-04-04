import React from 'react';
import { render } from 'react-dom';
import moment from 'moment';
import {
  confirmArticleDelete,
  deleteArticle,
  editArticle,
  markArticleRead,
  postponeArticle,
  fetchArticleCategories,
  saveArticle
} from '../../reducers/article/actions.jsx'

import Article from './Article.jsx';

export default class ArticleList extends React.Component {
  componentDidMount() {
    const { store } = this.context;
    const props = this.props;
    this.unsubscribe = store.subscribe(() =>
      this.forceUpdate()
    );

    if(!store.getState().articles.fetched) {
      store.dispatch(props.articleQuery());
    }
  }

  componentWillUnmount() {
    this.unsubscribe();
  }

  render() {
    const props = this.props;
    const { store } = this.context;

    let articles = store.getState().articles.list;
    let removeRead = props.removeRead;
    let displayArticles = articles;
    if(removeRead) {
      let today = (new moment()).format('YYYY-MM-DD');
      displayArticles = articles.filter((article) => {
        let articlePostponedToday = false;
        article.recommended.forEach((recommended) => {
          if(recommended.date == today && recommended.postponed) {
            articlePostponedToday = true;
          }
        });
        return (!article.read.includes(today) && !articlePostponedToday);
      });
    }
    if(store.getState().articles.categories.length == 0) {
      store.dispatch(fetchArticleCategories())
    }
    return <div className="col-4 daily-articles">
      <div className="bg-inverse text-white p-2">
        { props.title }
      </div>
      {
        store.getState().articles.loading ?
          <div className="article p-2 text-center">Loading Articles</div>
          :
          displayArticles.length > 0 ?
            displayArticles.map((article) => {
              return <Article
                article={article}
                onRead={() =>
                  store.dispatch(markArticleRead(article, removeRead))
                }
                onDelete={() =>
                  store.dispatch(deleteArticle(article))
                }
                onPostpone={() =>
                  store.dispatch(postponeArticle(article))
                }
                onEdit={() =>
                  store.dispatch(editArticle(article))
                }
                onDeleteConfirm={()=>
                  store.dispatch(confirmArticleDelete(article))
                }
                onSave={() =>
                  store.dispatch(saveArticle(article))
                }
                categories={store.getState().articles.categories}
                key={ article.id }/>
            })
          :
            removeRead ?
              <div className="article p-2 text-center text-success">Today's Articles Complete</div>
            :
              ''
        }
    </div>
  }
}

ArticleList.contextTypes = {
  store: React.PropTypes.object
};