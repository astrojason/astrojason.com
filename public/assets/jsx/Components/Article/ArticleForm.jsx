import React, { Component } from 'react';
import { render } from 'react-dom';
import { Button, Modal, ModalHeader, ModalBody, ModalFooter } from 'reactstrap';
import Select from 'react-select';
import moment from 'moment';

import { saveArticle } from '../../reducers/article/actions.jsx'

export default class ArticleForm extends Component {
  constructor(props) {
    super(props);

    this.handleTitleChange = this.handleTitleChange.bind(this);
    this.handleUrlChange = this.handleUrlChange.bind(this);
    this.handleCategoriesChange = this.handleCategoriesChange.bind(this);
    this.handleReadChange = this.handleReadChange.bind(this);
    this.handleArticleSave = this.handleArticleSave.bind(this);
    this.state = Object.assign({}, this.props.article, {
      savedTitle: this.props.article.title,
      articleCategories: [],
      isRead: this.props.article.read.length > 0
    });
  }

  handleTitleChange(e) {
    this.setState({
      title: e.target.value
    });
  }

  handleUrlChange(e) {
    this.setState({
      url: e.target.value
    });
  }

  handleReadChange(e) {
    if(!this.state.isRead) {
      this.setState({
        isRead: true,
        read: [(new moment()).format('YYYY-MM-DD')]
      });
    } else {
      this.setState({
        isRead: false,
        read: []
      });
    }
  }

  handleCategoriesChange(values) {
    let newCategories = values.map((value) => {
      return {
        id: value.value,
        name: value.label
      }
    });
    this.setState({
      categories: newCategories
    });
  }

  handleArticleSave() {
    const { store } = this.context;
    store.dispatch(saveArticle(this.state));
  }

  render() {
    let article = this.state;
    article.articleCategories = article.categories.map((category) => {
      return {
        value: category.id,
        label: category.name
      }
    });
    return <Modal isOpen={ this.props.modal } toggle={ this.props.toggle } className={ this.props.className }>
      <ModalHeader toggle={ this.toggle }>
        { article.id ? `Editing ${article.savedTitle}` : 'Add Article' }
      </ModalHeader>
      <ModalBody>
        <form>
          <div className="form-group">
            <label htmlFor="title">Title</label>
            <input
              type="text"
              className="form-control"
              value={ article.title }
              onChange={ this.handleTitleChange } />
          </div>
          <div className="form-group">
            <label htmlFor="url">URL</label>
            <input
              type="text"
              className="form-control"
              value={ article.url }
              onChange={ this.handleUrlChange } />
          </div>
          <div className="form-group">
            <label htmlFor="categories">Categories</label>
            <Select
              multi={ true }
              options={ this.props.categories }
              onChange={ this.handleCategoriesChange }
              value={ article.articleCategories }>
            </Select>
          </div>
          <div className="form-check form-check-inline float-right">
            <label className="form-check-label">
              <input
                className="form-check-input"
                type="checkbox"
                onClick={ this.handleReadChange }
                value={ article.isRead } /> Read
            </label>
          </div>
        </form>
      </ModalBody>
      <ModalFooter>
        <Button color="primary" onClick={ this.handleArticleSave }>Save</Button>{' '}
        <Button color="secondary" onClick={ this.props.toggle }>Cancel</Button>
      </ModalFooter>
    </Modal>
  }
}

ArticleForm.contextTypes = {
  store: React.PropTypes.object
};