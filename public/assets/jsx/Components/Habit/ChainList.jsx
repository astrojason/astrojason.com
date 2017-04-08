import React, { Component } from 'react';
import {render} from 'react-dom';

import Chain from './Chain.jsx'

let chainedHabits = [
  {
    id: 1,
    name: 'Read',
    current_streak: '266 days',
    completed_today: false
  },
  {
    id: 2,
    name: 'Practice Guitar',
    current_streak: '180 days',
    completed_today: true
  },
  {
    id: 3,
    name: 'Mobility WOD',
    current_streak: '30 days',
    completed_today: false
  }
];

export default class ChainList extends Component {
  render() {
    return <div>
      <div className="bg-inverse text-white p-2">Habit Chain</div>
      {
        chainedHabits.map(function (chainedHabit) {
          return <Chain chain={chainedHabit} key={chainedHabit.id}/>
        })
      }
    </div>
  }

}