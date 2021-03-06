

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
               <br>
               Files:
               <div id="filelist">
               </div>
            </div>
            <div class="modal-footer">
               <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
               <button type="button" onclick="modifyChallenge();" class="btn btn-primary">Save changes</button>
            </div>
         </div>
      </div>
   </div>
   <div class="modal fade" id="modalHash" tabindex="-1" role="dialog" aria-labelledby="modalHash">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title"><span id="hashName"></span> &middot; Modify Challenge Password</h4>
            </div>
            <div class="modal-body">
               <label for="hash_key">Key/Flag:</label>
               &nbsp;&nbsp;&nbsp;
               <input id="hash_key" type="text" class="text">
               <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <button type="button" onclick="modifyHash();" class="btn btn-primary">Save changes</button>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div class="modal fade" id="modalUpload" tabindex="-1" role="dialog" aria-labelledby="modalUpload">
      <div class="modal-dialog" role="document">
         <div class="modal-content">
           <form id="fileForm" action="/ajax/challenge/file_put" method="post" enctype="multipart/form-data">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
               <h4 class="modal-title"><span id="fileName"></span> &middot; Add file to challenge</h4>
            </div>
            <div class="modal-body">
               <input type="file" style="color:rgb(129, 129, 129);" id="fileUpload" name="fileUpload">
               <input type="hidden" name="chalid_file" id="chalid_file">
               <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                  <input type="submit" onclick="" class="btn btn-primary" name="submit" id="submit" value="Upload">
               </div>

            </div>
          </form>
         </div>
      </div>
   </div>
   <div class="row">
      <div class="col-md-10 col-md-offset-1">
         <div class="panel panel-default">
            <div class="panel-heading">Control Panel</div>
            <div class="panel-body">
               <i>You may make changes to control the competition here. All changes are updated by ajax.</i>

               <div class="panel panel-primary">
                  <div class="panel-heading">Manage Competition</div>
                  <div class="panel-body">
                     <p>With no start time set, users will be able to access challenges at any time before the end time. <br>If an end time is set, users will not be able to complete challenges after the set date.</p>
                     <p>If you set a start time past the end time, the end time will be set to null.</p>
                     <label for="competition_start" type="text" class="text">Start Time:</label>
                     <input id="competition_start" style="margin-left:3px;" type="text" class="text">
                     <br>
                     <label for="competition_end" type="text" class="text">End Time:</label>
                     <input id="competition_end" style="margin-left:10px;" type="text" class="text">
                     <br>
                     <button type="button" style="margin-left:185px;" onclick="updateTime();" class="btn btn-info btn-md">Update</button>
                  </div>
               </div>

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
                     <div>Click on a challenge to modify its name.<br></div>
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
<script type="text/javascript" src="/js/jquery.datetimepicker.full.min.js"></script>
<script type="text/javascript" src="/js/admin.js"></script>

@endsection
