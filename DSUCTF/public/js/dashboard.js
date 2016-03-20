var miscCreated = 0;
var currentChallenge;
var itemList = [];
var totalCat = 0;
var hiddenCat = 0;
$(document).ready(function() {
    $("#modalnotice").hide();
    popCategory();
});

function popCategory() {
    $.ajax({
        url: '/ajax/category',
        dataType: 'json',
        success: function(response) {
            $("#catcontainer").html("");
            console.log(response);
            if (response.length === 0)
                $("#catcontainer").html("No categories currently exist.");
            else {
                var i;
                for (i = 0; i < response.length; i++) {
                    totalCat++;
                    $("#catcontainer").append("<ul data-catid='" + response[i].category_id + "' class='list-group col-md-4'><li class='list-group-item disabled'>" + response[i].name + "</li></ul>");
                    itemList[response[i].category_id] = 0;
                }
            }
	popChallenge();
        }
    });
}

function popChallenge() {
    $.ajax({
        url: '/ajax/challenge/listall',
        dataType: 'json',
        success: function(response) {
            console.log(response);
            var i;
            // populate table
            for (i = 0; i < response.length; i++) {
              itemList[response[i].category_id]++;
                if (response[i].category_id == 0 && miscCreated == 0) {
                  miscCreated = 1;
                    $("#catcontainer").append("<ul data-catid='" + response[i].category_id + "' class='list-group col-md-4'><li class='list-group-item disabled'>Misc</li></ul>");
                }
                $("ul[data-catid='" + response[i].category_id + "']").append("<button type='button' onclick='loadChallenge("+response[i].challenge_id+")' data-chalid=" + response[i].challenge_id + " data-catid=" + response[i].category_id + " class='list-group-item'><span class='badge'>" + response[i].value + "</span>" + response[i].challenge_name + "</button>");

            }
            finalizePop();
        }
    })
    //finalizePop(); // Hide categories that do not have any challenges
}

function finalizePop() {
  /*
    This was the original solution for hiding unused categories, however it proved buggy.
    $(".list-group").each(function() {
        if ($(this).children().length <= 1) {
            $(this).hide();
        }
    });
  */
  var i;
  for(i=0; i < itemList.length; i++)
  {
    if(itemList[i] == 0)
    {
      $("*[data-catid='"+i+"']").hide();
      hiddenCat++;
    }

  }
}

function loadChallenge(chalid)
{
  currentChallenge = chalid;
  $("#chalAnswer").val("");
  loadFiles(chalid);
  $("#modalnotice").hide();
  $.ajax({
    url: '/ajax/challenge/info',
    dataType: 'json',
    type: 'GET',
    data: {challenge_id:chalid},
    success: function(response) {
      $("#challengeName").html(response.challenge_name);
      $("#category").html(response.category_name);
      $("#description").html(response.description);
      $("#challengeValue").attr("value",response.value);
      if(response.completed == 1)
      {
        $("#modalnotice").attr("class", "alert alert-success");
        $("#modalnotice").html("You have already completed this challenge!");
        $("#modalnotice").show();
      }
      $("#modalChallenge").modal("toggle");

    }
  });
}


function loadFiles(challenge_id)
{
  // /ajax/challenge/info_files
  $("#filelist").html("");
  $.ajax({
    url: '/ajax/challenge/info_files',
    dataType: 'json',
    type: 'GET',
    data: {chalid:challenge_id},
    success: function(response) {
      var i;
      for(i=0; i < response.length; i++)
      {
        $("#filelist").append("<a class='label label-info' href='/ajax/challenge/file_get/" + challenge_id + "/" + response[i] + "'>"+response[i]+"</a>&nbsp;");
      }
      if(response.length == 0)
        $("#filelist").append("No files exist for this challenge.");
    }
  });
}
function submitChallenge()
{
  $.ajax({
    url: '/ajax/challenge/attempt',
    type: 'POST',
    data: {challenge_id:currentChallenge, answer: $("#chalAnswer").val()},
    success: function(response) {
      console.log(response);
      $("#modalnotice").html(response);
      if(response == "You have successfully completed this challenge!")
      {
        $("#modalnotice").attr("class", "alert alert-success");
      }
      else {
        $("#modalnotice").attr("class", "alert alert-danger");
      }
      $("#modalnotice").show("slow");
    }
  });
}
