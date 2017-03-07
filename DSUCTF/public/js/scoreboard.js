$( document ).ready(function(){
  popScoreboard();
});

function popScoreboard()
{
  var i;
  $.ajax({
    url: '/ajax/score/board',
    dataType:'json',
    type: 'GET',
    success: function(response){
      for(i=0; i < response.length; i++)
      {
        console.log(response[i].name + ":" + response[i].score );
        $("#scorebody").append("<tr><td>" + response[i].name + "</td><td style='text-align:left;'>" + response[i].score + "</td></tr>")
      }
    }
  });
}
