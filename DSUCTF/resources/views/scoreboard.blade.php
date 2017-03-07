@extends('layouts.app')
@section('otherresources')
  <script type="text/javascript" src="js/scoreboard.js"></script>
@stop
@section('content')
<center>
  <table class="table" style="width:100%;max-width:600px !important;">
    <thead>
      <th style="text-align:left;">Name</th><th style="">Score</th>
    </thead>
    <tbody id="scorebody">

    </tbody>
  </table>
</center>
@stop
