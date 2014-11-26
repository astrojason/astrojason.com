{{ Form::open(array('action' => 'LinksController@add')) }}

{{ Form::label('name', 'Link Name:') }}
{{ Form::input('text', 'name') }}

{{ Form::label('link', 'Link Url:') }}
{{ Form::input('text', 'link') }}

{{ Form::select('category') }}

{{ Form::close() }}