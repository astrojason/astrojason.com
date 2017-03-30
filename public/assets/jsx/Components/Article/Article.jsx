import React from 'react';
import {render} from 'react-dom';
import {Button, Popover, PopoverTitle, PopoverContent} from 'reactstrap';
import ArticleForm from './ArticleForm.jsx'

export default class Article extends React.Component {
  constructor(props) {
    super(props);

    this.toggleDelete = this.toggleDelete.bind(this);
    this.toggleEdit = this.toggleEdit.bind(this);
    this.state = {
      popoverOpen: false,
      modal: false
    }
  }

  toggleDelete() {
    this.setState({
      popoverOpen: !this.state.popoverOpen
    });
  }

  toggleEdit() {
    this.setState({
      modal: !this.state.modal
    });
  }

  render() {
    return (
      <div className="article p-2">
        <a href={ this.props.article.url } target="_blank">
          { this.props.article.title }
        </a>
        <div className="btn-group float-right">
          <button className="btn btn-sm btn-outline-success" onClick={this.props.onRead}>
            <span className="fa fa-check"></span>
          </button>
          <button className="btn btn-sm btn-outline-info postpone-button" onClick={this.props.onPostpone}>
            <span className="fa fa-calendar-plus-o"></span>
          </button>
          <button className="btn btn-sm btn-outline-primary" onClick={this.toggleEdit}>
            <span className="fa fa-edit"></span>
          </button>
          <Button
            id={`popover${this.props.article.id}`}
            className="btn btn-sm btn-outline-danger"
            onClick={this.toggleDelete}>
            <span className="fa fa-trash"></span>
          </Button>
        </div>
        <Popover isOpen={this.state.popoverOpen} target={`popover${this.props.article.id}`} toggle={this.toggleDelete}>
          <PopoverTitle>Delete article?</PopoverTitle>
          <PopoverContent>
            Are you sure you want to delete<br />
            <i>{this.props.article.title}</i>?
            <button className="btn btn-danger btn-block" onClick={this.props.onDelete}>Yes, delete it!</button>
            <button className="btn btn-default btn-block" onClick={this.toggle}>No!</button>
          </PopoverContent>
        </Popover>
        <ArticleForm article={this.props.article} toggle={this.toggleEdit} modal={this.state.modal}/>
      </div>
    )
  }
}