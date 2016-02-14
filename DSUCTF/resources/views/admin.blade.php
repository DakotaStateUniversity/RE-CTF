@extends('layouts.controlpanel')

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
                        <div>Click on a category to modify its name.<br>Drag and drop categories to change their display order.</div>
                        <table class="table" >
                          <tr>
                            <th>Categories</th>
                            <th></th>
                          </tr>
                          <tbody id="sortable">

                          </tbody>
                        </table>
                        <label for="catname">Name:</label>
                        <input id="catname" type="text" class="text">
                        <button type="button" onclick="addCategory();" class="btn btn-primary btn-sm">Create category</button>
                        <br>

                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/js/admin.js"></script>
@endsection
