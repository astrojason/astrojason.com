import React from 'react';
import { render } from 'react-dom';
import { Button, Popover, PopoverTitle, PopoverContent } from 'reactstrap';
import ArticleForm from './ArticleForm.jsx'

const Article = ({
  article,
  onRead,
  onPostpone,
  onEdit,
  onDelete,
  onDeleteConfirm
}) => (
  <div className="article p-2">
    <a href={ article.url } target="_blank">
      { article.title }
    </a>
    <div className="btn-group float-right">
      <button className="btn btn-sm btn-outline-success" onClick={ onRead }>
        <span className="fa fa-check"></span>
      </button>
      <button className="btn btn-sm btn-outline-info postpone-button" onClick={ onPostpone }>
        <span className="fa fa-calendar-plus-o"></span>
      </button>
      <button className="btn btn-sm btn-outline-primary" onClick={ onEdit }>
        <span className="fa fa-edit"></span>
      </button>
      <Button
        id={`popover${ article.id }`}
        className="btn btn-sm btn-outline-danger"
        onClick={ onDeleteConfirm }>
        <span className="fa fa-trash"></span>
      </Button>
    </div>
    <Popover isOpen={ article.confirmDelete } target={`popover${ article.id }`} toggle={ onDeleteConfirm }>
      <PopoverTitle>Delete article?</PopoverTitle>
      <PopoverContent>
        Are you sure you want to delete<br />
        <i>{ article.title }</i>?
        <button className="btn btn-danger btn-block" onClick={ onDelete }>Yes, delete it!</button>
        <button className="btn btn-default btn-block" onClick={ onDeleteConfirm }>No!</button>
      </PopoverContent>
    </Popover>
    <ArticleForm article={ article } toggle={ onEdit } modal={ article.editingMode }/>
  </div>
);

export default Article;