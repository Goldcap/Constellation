function addFilter( element ) {
  switch (element) {
    case "ip":
      if ($("#program_geoblocking_IP").val() != '') {
        html = '<div id="'+$("#program_geoblocking_IP").val().replace(/\./g,"")+'"><span class="gbfilter">'+$("#program_geoblocking_IP").val()+'</span><span><img src="/images/Neu/16x16/actions/edit-undo.png" onclick="removeFilter(\''+$("#program_geoblocking_IP").val().replace(/\./g,"")+'\')" /></span></div>';
      } else {
        return false;
      }
      break;
    case "country":
      if ($("#program_geoblocking_country").val() != 0) {
        html = '<div id="'+$("#program_geoblocking_country").val()+'"><span class="gbfilter">'+$("#program_geoblocking_country").val()+'</span><span><img src="/images/Neu/16x16/actions/edit-undo.png" onclick="removeFilter(\''+$("#program_geoblocking_country").val()+'\')" /></span></div>';
      } else {
        return false;
      }
      break;
     case "region":
      if ($("#program_geoblocking_region").val() != 0) {
        html = '<div id="'+$("#program_geoblocking_region").val()+'"><span class="gbfilter">'+$("#program_geoblocking_region").val()+'</span><span><img src="/images/Neu/16x16/actions/edit-undo.png" onclick="removeFilter(\''+$("#program_geoblocking_region").val()+'\')" /></span></div>';
      } else {
        return false;
      }
      break;
  }
  $("#program_geoblocking_filters").append(html);

}

function removeFilter( element ) {
  $("#"+element).remove();

}

function submitFilm() {
  if (checkForm(thisValidator,"program")) {
    var values = '';
    $(".gbfilter").each(function(i){
      values += $(this).html()+',';
    });
    $("#program_geoblocking_type").val(values);
    return true;
  }
  return false;
}

$(document).ready(function(){

});
