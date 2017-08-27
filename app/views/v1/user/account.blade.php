@extends('v1.layouts.layout')

@section('title')
    My Account
@stop
@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success">
            <h2><% Session::get('success') %></h2>
        </div>
    @endif
    @if ($errors->has())
        <div class="alert alert-danger">
            <ul class="list-unstyled">
                @foreach ($errors->all() as $error)
                    <li><% $error %></li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="row">
        <div class="col-sm-12">
            <h3>User Info</h3>
        </div>
    </div>
    <% Form::model($user) %>
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <td>
                            <% Form::label('username', 'Username') %>
                            <% Form::text('username', null, ['class' => 'form-control', 'disabled' => true]) %>
                        </td>
                        <td class="@if ($errors->has('email'))has-error @endif">
                            <% Form::label('email', 'Email Address') %>
                            <% Form::email('email', null, ['class' => 'form-control']) %>
                        </td>
                    </tr>
                    <tr>
                        <td class="@if ($errors->has('firstname')) has-error @endif">
                            <% Form::label('firstname', 'First Name') %>
                            <% Form::text('firstname', null, ['class' => 'form-control']) %>
                        </td>
                        <td class="@if ($errors->has('lastname')) has-error @endif">
                            <% Form::label('lastname', 'Last Name') %>
                            <% Form::text('lastname', null, ['class' => 'form-control']) %>
                        </td>
                    </tr>
                    <tr>
                        <td class="@if ($errors->has('current_password')) has-error @endif">
                            <% Form::label('current_password', 'Current Password') %>
                            <% Form::password('current_password', ['class' => 'form-control']) %>
                        </td>
                        <td class="@if ($errors->has('password')) has-error @endif">
                            <% Form::label('password', 'New Password') %>
                            <% Form::password('password', ['class' => 'form-control']) %>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2">
                            <input type="submit" class="btn btn-primary pull-right" value="Save"/>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-8">
            <h3>Dashboard Categories</h3>
        </div>
        <div class="col-sm-4">
            <button
                    name="resetToday"
                    id="resetToday"
                    value="true"
                    class="btn btn-primary pull-right">
                Reset Today's Recommendations
            </button>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <hr/>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Number of Articles</th>
                        <th>Include Read</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($articleSettings as $articleSetting)
                        <tr>
                            <td>
                                <input
                                        type="hidden"
                                        name="articleSettingsId[<% $articleSetting->id %>]"
                                        id="articleSettingsId[<% $articleSetting->id %>]"
                                        value="<% $articleSetting->id %>"/>
                                <select
                                        class="form-control"
                                        id="articleSettingsCategory[<% $articleSetting->id %>]"
                                        name="articleSettingsCategory[<% $articleSetting->id %>]">
                                    @if(!$articleSetting->category_id || !(in_array(0, $selectedIds)))
                                        <option value="0">Uncategorized</option>
                                    @endif
                                    @foreach($categories as $category)
                                        @if(!(in_array($category->id, $selectedIds)) || ($category->id == $articleSetting->category_id))
                                            <option
                                                    @if($articleSetting->category_id == $category->id)
                                                    SELECTED
                                                    @endif
                                                    value="<% $category->id %>">
                                                <% $category->name %>
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input
                                        class="form-control"
                                        type="number"
                                        name="articleSettingsNumber[<% $articleSetting->id %>]"
                                        id="articleSettingsNumber[<% $articleSetting->id %>]"
                                        value="<% $articleSetting->number %>"/>
                            </td>
                            <td>
                                <input
                                        name="articleSettingsAllowRead[<% $articleSetting->id %>]"
                                        id="articleSettingsAllowRead[<% $articleSetting->id %>]"
                                        @if($articleSetting->allow_read)
                                        CHECKED
                                        @endif
                                        type="checkbox"/>
                            </td>
                            <td>
                                <div class="btn-group pull-right">
                                    <button
                                            class="btn btn-success"
                                            value="true"
                                            name="update[<% $articleSetting->id %>]"
                                            id="update[<% $articleSetting->id %>]">
                                        <span class="glyphicon glyphicon-ok"></span>
                                    </button>
                                    <button
                                            class="btn btn-danger"
                                            value="true"
                                            name="delete[<% $articleSetting->id %>]"
                                            id="delete[<% $articleSetting->id %>]">
                                        <span class="glyphicon glyphicon-remove-circle"></span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td>
                            <select class="form-control" name="newArticleSetting.category"
                                    id="newArticleSetting.category">
                                @if(!(in_array(0, $selectedIds)))
                                    <option value="0">Uncategorized</option>
                                @endif
                                @foreach($categories as $category)
                                    @if(!(in_array($category->id, $selectedIds)))
                                        <option
                                                value="<% $category->id %>">
                                            <% $category->name %>
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input
                                    class="form-control"
                                    type="number"
                                    name="newArticleSetting.number"
                                    id="newArticleSetting.number"/>
                        </td>
                        <td>
                            <input
                                    type="checkbox"
                                    name="newArticleSetting.include_read"
                                    id="newArticleSetting.include_read"/>
                        </td>
                        <td>
                            <button
                                    class="btn btn-success pull-right"
                                    value="true"
                                    name="newArticleSetting.save"
                                    id="newArticleSetting.save">
                                <span class="glyphicon glyphicon-plus-sign"></span>
                            </button>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <% Form::close() %>
@stop
