import React, { Component } from 'react';
import { render } from 'react-dom';

export default class PracticeItem extends Component {
  render() {
    return <div className="task p-2">
      {this.props.task.exercise}
      <span className="badge badge-info ml-2">{ this.props.task.category }</span>
      <button className="btn btn-sm btn-outline-success float-right">
        <span className="fa fa-check"></span>
      </button>
    </div>
  }
}