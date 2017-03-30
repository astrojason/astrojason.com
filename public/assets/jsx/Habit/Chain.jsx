import React from 'react';
import {render} from 'react-dom';

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

class Chain extends React.Component {
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

export default class Chains extends React.Component {
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