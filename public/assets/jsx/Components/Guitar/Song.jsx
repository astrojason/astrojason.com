import React, { Component } from 'react';
import { render } from 'react-dom';

export default class Song extends Component {
  render() {
    return <div className="song p-2">
      {this.props.song.title} - {this.props.song.artist}
      {
        this.props.song.category == 'Project' ?
          <span className="badge badge-info ml-2">{this.props.song.category}</span>
          :
          ''
      }
      <button className="btn btn-sm btn-outline-success float-right">
        <span className="fa fa-check"></span>
      </button>
    </div>
  }
}