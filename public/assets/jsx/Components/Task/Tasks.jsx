import React, { Component } from 'react';
import { render } from 'react-dom';

export default class Task extends Component {
  render() {
    return <div className="task p-2">
      {this.props.task.name}
      {
        this.props.task.time_remaining != '' ?
          <small> ({this.props.task.time_remaining} remaining)</small>
          :
          ''
      }
      {
        this.props.task.tasks.length > 0 ?
          <div className="ml-2">
            {
              this.props.task.tasks.map(function (sub_task) {
                return <Task task={sub_task} key={sub_task.id}/>
              })
            }
          </div>
          :
          <div className="btn-group float-right">
            <button className="btn btn-sm btn-outline-success">
              <span className="fa fa-check"></span>
            </button>
            <button className="btn btn-sm btn-outline-info postpone-button">
              <span className="fa fa-calendar-plus-o"></span>
            </button>
            <button className="btn btn-sm btn-outline-primary">
              <span className="fa fa-edit"></span>
            </button>
            <button className="btn btn-sm btn-outline-danger">
              <span className="fa fa-trash"></span>
            </button>
          </div>
      }
    </div>
  }
}