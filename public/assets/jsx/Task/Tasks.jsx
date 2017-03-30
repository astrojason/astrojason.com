import React from 'react';
import {render} from 'react-dom';

let todayTasks = [
  {
    id: 1,
    name: 'Neck Wedge',
    tasks: [],
    time_remaining: ''
  },
  {
    id: 2,
    name: 'Mobility WOD',
    time_remaining: '',
    tasks: [
      {
        id: 3,
        name: 'Plantar surface smash (p.191)',
        tasks: [],
        time_remaining: ''
      },
      {
        id: 4,
        name: 'Glute smash & floss (p.216)',
        tasks: [],
        time_remaining: ''
      },
      {
        id: 5,
        name: 'Low back smash, opt. 1 (p.400)',
        tasks: [],
        time_remaining: ''
      }
    ]
  }
];

let shortTermTasks = [
  {
    id: 6,
    name: 'First draft of "The Magician"',
    tasks: [],
    time_remaining: '80 days'
  },
  {
    id: 7,
    name: 'Downpicking @ 200bpm',
    tasks: [],
    time_remaining: '60 days'
  }
];

let midTermTasks = [
  {
    id: 8,
    name: 'Juggle Three Balls',
    tasks: [],
    time_remaining: '2 months'
  },
  {
    id: 9,
    name: 'Transcribe "Taxidermist Surf"',
    tasks: [],
    time_remaining: '4 months'
  }
];

let longTermTasks = [
  {
    id: 8,
    name: 'Write a Song',
    tasks: [],
    time_remaining: '8 months'
  },
  {
    id: 9,
    name: 'Deadlift 300lbs',
    tasks: [],
    time_remaining: '11 months'
  }
];

class Task extends React.Component {
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

export default class Tasks extends React.Component {
  render() {
    return <div>
      <ul className="nav nav-tabs">
        <li className="nav-item">
          <a className="nav-link active" href="#">Dated</a>
        </li>
        <li className="nav-item">
          <a className="nav-link" href="#">No Due Date</a>
        </li>
      </ul>
      <div className="bg-inverse text-white p-2">Today</div>
      {
        todayTasks.map(function (task) {
          return <Task task={task} key={task.id}/>
        })
      }
      <div className="bg-inverse text-white p-2">Short Term (&lt; 3 months)</div>
      {
        shortTermTasks.map(function (task) {
          return <Task task={task} key={task.id}/>
        })
      }
      <div className="bg-inverse text-white p-2">Mid Term (3-6 months)</div>
      {
        midTermTasks.map(function (task) {
          return <Task task={task} key={task.id}/>
        })
      }
      <div className="bg-inverse text-white p-2">Long Term (6-12 months)</div>
      {
        longTermTasks.map(function (task) {
          return <Task task={task} key={task.id}/>
        })
      }
    </div>
  }

}