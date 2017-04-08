import React, { Component } from 'react';
import {render} from 'react-dom';

import Song from './Song.jsx'

let songsList = [
  {
    id: 1,
    category: 'Ego',
    title: 'Stray Cat Strut',
    artist: 'The Stray Cats'
  },
  {
    id: 2,
    category: 'Ego',
    title: 'Simple Man',
    artist: 'Lynyrd Skynyrd'
  },
  {
    id: 3,
    category: 'Ego',
    title: 'The Jack',
    artist: 'AC/DC'
  },
  {
    id: 4,
    category: 'Project',
    title: 'Seek and Destroy',
    artist: 'Metallica'
  },
];

export default class SongList extends Component {
  render() {
    return <div className="mt-2">
      <div className="bg-inverse text-white p-2">Songs</div>
      {
        songsList.map(function (song) {
          return <Song song={song} key={song.id}/>
        })
      }
    </div>
  }
}