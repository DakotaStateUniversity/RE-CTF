var currentChallenge;
$(document).ready(function() {
    // populate category table
    popCategory();
    popChallenge();
    /*
      https://github.com/LPology/Simple-Ajax-Uploader
    */
    var sizeBox = document.getElementById('sizeBox'), // container for file size info
        progress = document.getElementById('progressBar'); // the element we're using for a progress bar

    var uploader = new ss.SimpleUpload({
          button: 'uploadButton', // file upload button
          url: 'uploadHandler.php', // server side handler
          name: 'uploadfile', // upload parameter name
          progressUrl: 'uploadProgress.php', // enables cross-browser progress support (more info below)
          responseType: 'json',
          allowedExtensions: ['jpg', 'jpeg', 'png', 'gif'],
          maxSize: 1024, // kilobytes
          hoverClass: 'ui-state-hover',
          focusClass: 'ui-state-focus',
          disabledClass: 'ui-state-disabled',
          onSubmit: function(filename, extension) {
              this.setFileSizeBox(sizeBox); // designate this element as file size container
              this.setProgressBar(progress); // designate as progress bar
            },
          onComplete: function(filename, response) {
              if (!response) {
                  alert(filename + 'upload failed');
                  return false;
              }
              // do something with response...
            }
    });

});

//Helper function for calculation of progress
function formatFileSize(bytes) {
    if (typeof bytes !== 'number') {
        return '';
    }

    if (bytes >= 1000000000) {
        return (bytes / 1000000000).toFixed(2) + ' GB';
    }

    if (bytes >= 1000000) {
        return (bytes / 1000000).toFixed(2) + ' MB';
    }
    return (bytes / 1000).toFixed(2) + ' KB';
}

////// Category //////
function delCategory(catid) {
    $.ajax({
        url: '/ajax/category/destroy',
        type: 'GET',
        data: "&catid=" + catid,
        success: function(response) {
            console.log(response);
            popCategory();
        }
    });

}

function addCategory() {
    var catname = $("#catname").val();
    $.ajax({
        url: '/ajax/category/store',
        type: 'GET',
        data: "name=" + catname,
        success: function(response) {
            console.log(response);
            popCategory();
        }
    });

}

function popCategory() {
    $('#sortable').html("<tr><td>Loading</td></tr>");
    $.ajax({
            url: '/ajax/category',
            dataType: 'json',
            success: function(response) {
                $("#sortable").html("");
                console.log(response);
                if (response.length === 0)
                    $("#sortable").html("<tr><td>No categories exist.</td><td></td></tr>");
                else {
                    var i;
                    // populate table
                    for (i = 0; i < response.length; i++) {
                        $("#sortable").append("<tr data-catid='" + response[i].category_id + "' data-start_pos='" + response[i].level + "'><td><div id='" + response[i].category_id + "'>" + response[i].name + "</div></td><td style='text-align:right;'><span onclick='delCategory(" + response[i].category_id + ")' class='glyphicon glyphicon-remove'></span></td>");
                        $('#' + response[i].category_id).editable("/ajax/category/modify", {
                            id: 'catid',
                            name: 'name',
                            tooltip: 'Click to change name...'
                        });
                    }
                    // populate combo boxes
                    $("#modal_category_id, #category_id").html("<option value='0'>Misc</option>");
                    for(i=0; i < response.length; i++)
                    {
                      $("#modal_category_id, #category_id").append("<option value='"+response[i].category_id+"'>"+response[i].name+"</option>");
                    }
                }
                $("#sortable").sortable({
                    start: function(event, ui) {
                        ui.item.data('start_pos', ui.item.index());
                    },
                    stop: function(event, ui) {
                        var start_pos = ui.item.data('start_pos');
                        if (start_pos != ui.item.index()) {
                            // the item position has been changed
                            var neworder = [];
                            var i = 0;
                            $("#sortable > tr").each(function() {
                                i++;
                                neworder.push({
                                    catid: $(this).data('catid'),
                                    level: i
                                });
                            });
                            //var category_id = ui.item.data('catid');
                            //var level = ui.item.index() + 1;
                            console.log(JSON.stringify(neworder));
                            $.ajax({
                                url: "/ajax/category/update",
                                type: "GET",
                                data: {
                                    data: JSON.stringify(neworder)
                                },
                                success: function(response) {
                                    console.log(response);
                                }
                            });

                        } else {
                            // the item was returned to the same position
                        }
                    },
                    update: function(event, ui) {
                        //$.post('/reorder', $("#sortable").sortable('serialize'))
                        //    .done(function() {
                        //        alert('Updated')
                        //    });
                        console.log($("#sortable").sortable('serialize'));
                    }
                });

                $("#sortable").disableSelection();
      }
    });

}

////// ^ Category //////
////// Challenge //////

function popChallenge()
{
  $("#chalsortable").html("");
  $.ajax({
    url: '/ajax/challenge/listall',
    type: 'GET',
    dataType: 'json',
    success: function (response) {
      if(response.length == 0)
        $("#chalsortable").html("<tr><td>No challenges exist.</td><td></td></tr>");
      else {

        for (i = 0; i < response.length; i++) {
            $("#chalsortable").append("<tr data-chalid='" + response[i].challenge_id + "'><td><div id='" + response[i].challenge_id + "'>" + response[i].challenge_name + "</div></td><td style='text-align:right;'><span onclick='delChallenge(" + response[i].challenge_id + ")' class='glyphicon glyphicon-remove'></span><span onclick='loadChallenge(" + response[i].challenge_id + ")' class='glyphicon glyphicon-cog'></span>&nbsp;<span class='glyphicon glyphicon-lock' onClick='loadHash(" + response[i].challenge_id + ")'></span>&nbsp;<span class='glyphicon glyphicon-cloud-upload' onClick='loadFileModal("+response[i].challenge_id+")'></span></td>");
            $('#' + response[i].challenge_id).editable("/ajax/challenge/modify_name", {
                id: 'chalid',
                name: 'name',
                tooltip: 'Click to change name...'
            });
        }

      }
    }
  });
}

function storeChallenge()
{
  $.ajax({
    url: '/ajax/challenge/store',
    type: 'POST',
    data: {challenge_name: $('#challenge_name').val(), value: $("#value").val(), key: $("#key").val(), description: $("#description").val(), category_id: $("#category_id").val()},
    success: function(response)
    {
      console.log(response);
      popChallenge();
      $('#challenge_name').val("");
      $("#value").val("");
      $("#key").val("");
      $("#description").val("");
      $("#category_id").val("");
    }

  });
}
  var name;
function loadChallenge(challenge_id, flag)
{

  if(flag != 1)
    $("#modalChallenge").modal('toggle');
  $.ajax({
    url: '/ajax/challenge/data',
    type: 'GET',
    dataType: 'json',
    data: 'challenge_id=' + challenge_id,
    success: function(response) {
      if(response.length == 0)
        return 'No response';
      response[0].challenge_id;
      $("#modal_challenge_name").val(response[0].challenge_name);
      //$("#modal_key").val(response[0].key);
      $("#modal_category_id").val(response[0].category_id);
      $("#modal_description").val(response[0].description);
      $("#modal_value").val(response[0].value);
      name = response[0].challenge_name;

      //console.log(response[0]);
      //var objResp = response[0];
      //return response[0].challenge_name;
    }
  });
  currentChallenge = challenge_id;

}

function loadHash(challenge_id)
{
  loadChallenge(challenge_id, 1);
  //console.log(name);
  $("#hashName").html(name);
  $("#modalHash").modal('toggle');

}

function modifyHash()
{
  $.ajax({
    type: 'GET',
    data: {pass: $("#hash_key").val(), chalid: currentChallenge},
    url: '/ajax/challenge/modify_hash',
    success: function(response) {
      $("#modalHash").modal("toggle");
    }
  });
}

function modifyChallenge()
{
  $.ajax({
    url: '/ajax/challenge/modify',
    type: 'POST',
    data: {challenge_id: currentChallenge, challenge_name: $('#modal_challenge_name').val(), value: $("#modal_value").val(), description: $("#modal_description").val(), category_id: $("#modal_category_id").val()},
    success: function(response)
    {
      console.log(response);
      $("#modalChallenge").modal("toggle");
      popChallenge();

    }

  });
}

function loadFileModal(chalid)
{
  $("#modalUpload").modal("toggle");
}

function delChallenge(challenge_id) {
    $.ajax({
        url: '/ajax/challenge/destroy',
        type: 'GET',
        data: "&challenge_id=" + challenge_id,
        success: function(response) {
            console.log(response);
            popChallenge();
        }
    });

}

////// ^Challenge //////
