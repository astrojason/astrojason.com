import React from 'react';
import { render } from 'react-dom';
import { confirmArticleDelete, deleteArticle, editArticle, postponeArticle, readArticle } from '../../reducers/article/actions.jsx'

import Article from './Article.jsx';

export default class ArticleList extends React.Component {
  render() {
    let articlesStore = this.props.articles;
    let articles = articlesStore.getState().articles;
    return <div>
      <div className="bg-inverse text-white p-2">{ this.props.title }</div>
      {
        articles.map(function (article) {
          return <Article
            article={article}
            onRead={() =>
              articlesStore.dispatch(readArticle(article))
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
            key={ article.id }/>
        })
      }
    </div>
  }
}