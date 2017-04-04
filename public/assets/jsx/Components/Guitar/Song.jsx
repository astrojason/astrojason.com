import React from 'react';
import {render} from 'react-dom';

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

class Song extends React.Component {
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

export default class Songs extends React.Component {
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