import React from 'react';
import {render} from 'react-dom';

let practiceList = [
  {
    id: 1,
    category: 'Core',
    exercise: 'Two-finger legato'
  },
  {
    id: 2,
    category: 'Core',
    exercise: 'Down picking'
  },
  {
    id: 3,
    category: 'Awareness',
    exercise: 'Notes on the fretboard'
  },
  {
    id: 4,
    category: 'Creative',
    exercise: 'Improvisation'
  }
];

class Task extends React.Component {
  render() {
    return <div className="task p-2">
      {this.props.task.exercise}
      <span className="badge badge-info ml-2">{this.props.task.category}</span>
      <button className="btn btn-sm btn-outline-success float-right">
        <span className="fa fa-check"></span>
      </button>
    </div>
  }
}

export default class Practice extends React.Component {
  render() {
    return <div>
      <div className="bg-inverse text-white p-2">Practice</div>
      {
        practiceList.map(function (task) {
          return <Task task={task} key={task.id}/>
        })
      }
    </div>
  }
}