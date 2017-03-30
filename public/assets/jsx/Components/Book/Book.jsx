import React from 'react';
import {render} from 'react-dom';

let currentBook = {
  title: 'Fool Moon',
  author: 'Jim Butcher'
};

let nextBook = {
  title: "A People's History of the United States",
  author: 'Howard Zinn'
};

class Book extends React.Component {
  render() {
    return <div className="book p-2">
      {this.props.book.title} - {this.props.book.author}
      {
        this.props.current ?
          <button className="btn btn-sm btn-outline-success float-right">
            <span className="fa fa-check"></span>
          </button>
          :
          <span className="badge badge-info ml-2">Next</span>
      }
    </div>
  }
}

export default class Books extends React.Component {
  render() {
    return <div>
      <div className="bg-inverse text-white p-2">Books</div>
      <Book book={currentBook} current={true}/>
      <Book book={nextBook} current={false}/>
    </div>
  }
}