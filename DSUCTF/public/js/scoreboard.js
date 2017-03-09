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
        $("#scorebody").append("<tr><td>" + response[i].name + "</td><td style='text-align:left;'>" + commaSeparateNumber(response[i].score) + "</td></tr>")
      }
    }
  });
}

function commaSeparateNumber(val){
    while (/(\d+)(\d{3})/.test(val.toString())){
      val = val.toString().replace(/(\d+)(\d{3})/, '$1'+','+'$2');
    }
    return val;
}
