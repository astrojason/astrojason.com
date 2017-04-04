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
  render() {
    let articlesStore = this.props.articles;
    let articles = articlesStore.getState().articles.list;
    let removeRead = this.props.removeRead;
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
    if(articlesStore.getState().articles.categories.length == 0) {
      articlesStore.dispatch(fetchArticleCategories())
    }
    return <div>
      <div className="bg-inverse text-white p-2">
        { this.props.title }
      </div>
      {
        articlesStore.getState().articles.loading ?
          <div className="article p-2 text-center">Loading Articles</div>
          :
          displayArticles.length > 0 ?
            displayArticles.map((article) => {
              return <Article
                article={article}
                onRead={() =>
                  articlesStore.dispatch(markArticleRead(article, removeRead))
                }
                onDelete={() =>
                  articlesStore.dispatch(deleteArticle(article))
                }
                onPostpone={() =>
                  articlesStore.dispatch(postponeArticle(article))
                }
                onEdit={() =>
                  articlesStore.dispatch(editArticle(article))
                }
                onDeleteConfirm={()=>
                  articlesStore.dispatch(confirmArticleDelete(article))
                }
                onSave={() =>
                  articlesStore.dispatch(saveArticle(article))
                }
                categories={articlesStore.getState().articles.categories}
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