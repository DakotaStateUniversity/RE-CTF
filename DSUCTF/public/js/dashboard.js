var miscCreated = 0;
var currentChallenge;

$(document).ready(function() {
    popCategory();
    popChallenge();

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
                    $("#catcontainer").append("<ul data-catid='" + response[i].category_id + "' class='list-group col-md-4'><li class='list-group-item disabled'>" + response[i].name + "</li></ul>");
                }
            }
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
                if (response[i].category_id == 0 && miscCreated == 0) {
                  miscCreated = 1;
                    $("#catcontainer").append("<ul data-catid='" + response[i].category_id + "' class='list-group col-md-4'><li class='list-group-item disabled'>Misc</li></ul>");
                }
                $("ul[data-catid='" + response[i].category_id + "']").append("<button type='button' onclick='loadChallenge("+response[i].challenge_id+")' data-chalid=" + response[i].challenge_id + " data-catid=" + response[i].category_id + " class='list-group-item'><span class='badge'>" + response[i].value + "</span>" + response[i].challenge_name + "</button>");

            }
        }
    }).done(finalizePop);
    //finalizePop(); // Hide categories that do not have any challenges
}

function finalizePop() {
    $(".list-group").each(function() {
        if ($(this).children().length <= 1) {
            $(this).hide();
        }
    });
}

function loadChallenge(chalid)
{
  currentChallenge = chalid;
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
      $("#modalChallenge").modal("toggle");
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

    }
  });
}
