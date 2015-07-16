@extends('layouts.layout')

@section('title')
  Books :: GoodReads
@stop

@section('content')
  <ul>
    @foreach($books['titles'] as $book)
      <li>
        @if($book['id'] > 0)
          <i>
        @endif
        <% $book['title'] %>
        @if($book['id'] > 0)
          </i>
        @endif
      </li>
    @endforeach
  </ul>
  @if($books['page'] > 0)
    <a href="/goodreads?page=<% $books['page'] - 1 %>">Previous Page</a>
  @endif
  @if($books['end'] < $books['total'])
    <a href="/goodreads?page=<% $books['page'] + 1 %>">Next Page</a>
  @endif
@stop