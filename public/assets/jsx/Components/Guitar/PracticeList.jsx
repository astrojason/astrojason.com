import React, { Component } from 'react';
import {render} from 'react-dom';

import PracticeItem from './PracticeItem.jsx'

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

export default class PracticeList extends Component {
  render() {
    return <div>
      <div className="bg-inverse text-white p-2">Practice</div>
      {
        practiceList.map(function (task) {
          return <PracticeItem task={task} key={task.id}/>
        })
      }
    </div>
  }
}