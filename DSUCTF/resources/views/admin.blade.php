@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Control Panel</div>

                <div class="panel-body">
                    <i>You may make changes to control the competition here.</i>
                    <div class="panel panel-primary">
                      <div class="panel-heading">Manage Categories</div>
                      <div class="panel-body">
                        <table class="table">
                          <tr>
                            <th>Categories</th>
                            <th></th>
                          </tr>
                          <tr>
                            <td>Reverse Engineering</td>
                            <td><span class="glyphicon glyphicon-pencil"></span><span class="glyphicon glyphicon-remove"></span></td>
                          </tr>
                          <tr>
                            <td>Networking</td>
                            <td><span class="glyphicon glyphicon-pencil"></span><span class="glyphicon glyphicon-remove"></span></td>
                          </tr>
                          <tr>
                            <td>SQL Injection</td>
                            <td><span class="glyphicon glyphicon-pencil"></span><span class="glyphicon glyphicon-remove"></span></td>
                          </tr>
                        </table>
                        <label for="catname">Name:</label>
                        <input id="catname" type="text" class="text">
                        <button type="button" class="btn btn-primary btn-sm">Create category</button>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
