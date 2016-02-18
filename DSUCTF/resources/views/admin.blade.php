@extends('layouts.controlpanel')

@section('content')

    <div class="container">
      <div class="modal fade" id="modalChallenge" tabindex="-1" role="dialog" aria-labelledby="modalChallenge">
      <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title" id="myModalLabel">Modify Challenge</h4>
            </div>
            <div class="modal-body">
              <label for="modal_challenge_name">Name:</label>
              &nbsp;&nbsp;&nbsp;
              <input id="modal_challenge_name" type="text" class="text">
              &nbsp;&nbsp;&nbsp;
              <label for="modal_key">Key/Flag:</label>
              &nbsp;&nbsp;&nbsp;
              <input id="modal_key" type="text" class="text">
              &nbsp;&nbsp;&nbsp;
              <label for="modal_category_id">Category:</label>
              &nbsp;&nbsp;&nbsp;
              <select id="modal_category_id">

              </select>
              <br>
              <label for="description">Description:</label>
              <br>
              <textarea class="form-control" id="modal_description"></textarea>
              <div class="input-group" style="width:52%;">
                <div class="input-group-addon">This challenge is worth </div>
                <input type="text" class="form-control" id="modal_value" placeholder="ex: 500">
                <div class="input-group-addon"> points.</div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" onclick="modifyChallenge();" class="btn btn-primary">Save changes</button>
            </div>
          </div>
        </div>
    </div>

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

                          </tr>
                          <tbody id="sortable">

                          </tbody>
                        </table>
                        <hr>
                        <label for="catname">Name:</label>
                        &nbsp;&nbsp;&nbsp;
                        <input id="catname" type="text" class="text">
                        &nbsp;&nbsp;&nbsp;&nbsp;
                        <button type="button" onclick="addCategory();" class="btn btn-primary btn-sm">Create category</button>
                        <br>

                      </div>
                    </div>

                    <div class="panel panel-primary">
                      <div class="panel-heading">Manage Challenges</div>
                      <div class="panel-body">
                        <div>Click on a challenge to modify its name.<br>Challenges are sorted in category by .</div>
                        <table class="table" >
                          <tr>
                            <th>Challenges</th>

                          </tr>
                          <tbody id="chalsortable">

                          </tbody>
                        </table>
                        <hr>
                        <label for="challenge_name">Name:</label>
                        &nbsp;&nbsp;&nbsp;
                        <input id="challenge_name" type="text" class="text">
                        &nbsp;&nbsp;&nbsp;
                        <label for="key">Key/Flag:</label>
                        &nbsp;&nbsp;&nbsp;
                        <input id="key" type="text" class="text">
                        &nbsp;&nbsp;&nbsp;
                        <label for="category_id">Category:</label>
                        &nbsp;&nbsp;&nbsp;
                        <select id="category_id">

                        </select>
                        <br>
                        <label for="description">Description:</label>
                        <br>
                        <textarea class="form-control" id="description"></textarea>
                        <div class="input-group" style="width:50%;">
                          <div class="input-group-addon">This challenge is worth </div>
                          <input type="text" class="form-control" id="value" placeholder="ex: 500">
                          <div class="input-group-addon"> points.</div>
                        </div>
                        <button style="right: 0;bottom: 0;position: absolute;margin-bottom:65px;margin-right:45px;" type="button" onclick="storeChallenge();" class="btn btn-primary btn-md">Create challenge</button>
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
