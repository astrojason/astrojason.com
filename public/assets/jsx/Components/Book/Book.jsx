import React from 'react';
import { render } from 'react-dom';

const Book = ({
  title,
  author,
  current
}) => (
  <div className="book p-2">
  {title} - {author}
    {
  current ?
    <button className="btn btn-sm btn-outline-success float-right">
      <span className="fa fa-check"></span>
    </button>
    :
    <span className="badge badge-info ml-2">Next</span>
  }
</div>
);

export default Book