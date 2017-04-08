import React, { Component } from 'react';
import { render } from 'react-dom';

import Book from './Book.jsx'

let currentBook = {
  title: 'Fool Moon',
  author: 'Jim Butcher'
};

let nextBook = {
  title: "A People's History of the United States",
  author: 'Howard Zinn'
};

export default class BookList extends Component {
  render() {
    return <div className="mt-2">
      <div className="bg-inverse text-white p-2">Books</div>
      <Book
        title={ currentBook.title }
        author={ currentBook.author }
        current={ true } />
      <Book
        title={ nextBook.title }
        author={ nextBook.author }
        current={ false } />
    </div>
  }
}