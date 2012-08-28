// JavaScript Document
function doReset(id) {
  $.ajax({url: "/services/UserAdmin/"+id, 
                type: "GET", 
                cache: false, 
                dataType: "json",
                success: resetSuccess,
                error: resetError});
}

function resetSuccess(response) {
  //$("#audience_"+response.id).parent().parent().parent().html("BLAH!");
  var elem = $("#audience_"+response.id).parent().parent().parent();
  if (response.result) {
    $(elem).children(".entry:nth-child(5)").html('<span class="status" style="color:green">Open</span>');
  } else {
    $(elem).children(".entry:nth-child(5)").html('<span class="status" style="color:blue">Used</span>');
  }
  error.showError("error",response.title,response.message,0);
  
}

function resetError(response) {
  error.showError("error","Communication Error","Please Try Again",0);
}

