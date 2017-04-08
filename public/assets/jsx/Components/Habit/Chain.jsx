import React, { Component } from 'react';
import { render } from 'react-dom';

export default class Chain extends Component {
  render() {
    return <div className="chain p-2">
      {this.props.chain.name}: ({this.props.chain.current_streak})
      {
        this.props.chain.completed_today ?
          <span className="badge badge-success float-right">Completed Today</span>
          :
          ''
      }
    </div>
  }
}