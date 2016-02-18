var currentChallenge;
$(document).ready(function() {
    // populate category table
    popCategory();
    popChallenge();
});
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
                            // the item got moved
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
    url: '/ajax/challenge',
    type: 'GET',
    dataType: 'json',
    success: function (response) {
      if(response.length == 0)
        $("#chalsortable").html("<tr><td>No categories exist.</td><td></td></tr>");
      else {

        for (i = 0; i < response.length; i++) {
            $("#chalsortable").append("<tr data-chalid='" + response[i].challenge_id + "'><td><div id='" + response[i].challenge_id + "'>" + response[i].challenge_name + "</div></td><td style='text-align:right;'><span onclick='delChallenge(" + response[i].challenge_id + ")' class='glyphicon glyphicon-remove'></span><span onclick='loadChallenge(" + response[i].challenge_id + ")' class='glyphicon glyphicon-cog'></span></td>");
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

function loadChallenge(challenge_id)
{
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
      $("#modal_key").val(response[0].key);
      $("#modal_category_id").val(response[0].category_id);
      $("#modal_description").val(response[0].description);
      $("#modal_value").val(response[0].value);
    }
  });
  currentChallenge = challenge_id;
}

function modifyChallenge()
{
  $.ajax({
    url: '/ajax/challenge/modify',
    type: 'POST',
    data: {challenge_id: currentChallenge, challenge_name: $('#modal_challenge_name').val(), value: $("#modal_value").val(), key: $("#modal_key").val(), description: $("#modal_description").val(), category_id: $("#modal_category_id").val()},
    success: function(response)
    {
      console.log(response);
      popChallenge();

    }

  });
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
