@extends('layouts.user')

@section('content')
  <style>
  .disabled {
    background-color:#333333 !important;
  }
  #challengeValue {
    color:#3babb3;
    background-color:#282828 !important;
    font-weight:bold;
  }
  #cat {
    font-style:italic;
  }
  #description {
    font-size:15px;
  }
  </style>
  <div class="modal fade" id="modalChallenge" tabindex="-1" role="dialog" aria-labelledby="modalChallenge">
  <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel"><span id="challengeName"></span></h4>
        </div>
        <div class="modal-body">
          <div id="modalnotice" class="alert alert-success" role="alert">...</div>
          <span id="cat">The category for this challenge is <span id="category"></span></span>
          <br>
          <br>
          <p id="description"></p>
          <br>
          <div class="input-group" style="width:52%;">
            <div class="input-group-addon">This challenge is worth </div>
            <input type="text" class="form-control" disabled id="challengeValue">
            <div class="input-group-addon"> points.</div>

          </div>
          <br>
          <input type="text" class="form-control" id="chalAnswer" placeholder="Enter key/flag">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="button" onclick="submitChallenge();" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div id="catcontainer" class="col-md-12 col-md-offset-1">


        </div>
        <div class="col-md-12">
          Challenges not loading? Try refreshing the page.
        </div>

    </div>
</div>
@endsection
