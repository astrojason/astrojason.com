import React from 'react';
import {render} from 'react-dom';

import Article from './Article.jsx';

export default class ArticleList extends React.Component {
  render() {
    let articlesStore = this.props.articles;
    return <div>
      <div className="bg-inverse text-white p-2">{this.props.title}</div>
      {
        articlesStore.getState().map(function (article) {
          return <Article
            article={article}
            onRead={() =>
              articlesStore.dispatch({
                type: 'READ',
                article: article
              })
            }
            onDelete={() =>
              articlesStore.dispatch({
                type: 'DELETE',
                article: article
              })
            }
            onPostpone={() =>
              articlesStore.dispatch({
                type: 'POSTPONE',
                article: article
              })
            }
            onEdit={() =>
              console.log('Edit')
            }
            key={ article.id }/>
        })
      }
    </div>
  }
}