// JavaScript Document
function postPromo() {
  console.log("Posting Promo");
  var args = {  "film_id": $("#film_id").val(),
                "promo_id": $("#promotion_id").val(),
                "priority": $("#promotion_priority").val()};
  
  $.ajax({url: '/services/FilmAdmin/promo', 
          data: $.param(args), 
          type: "POST", 
          cache: false, 
          dataType: "text", 
          success: function(response) {
              $("#film_promotions").html(response);
          }, 
          error: function(response) {
              console.log("ERROR:", response)
          }
  });
}

function addFilter( element ) {
  switch (element) {
    case "ip":
      if ($("#film_geoblocking_IP").val() != '') {
        html = '<div id="'+$("#film_geoblocking_IP").val().replace(/\./g,"")+'"><span class="gbfilter">'+$("#film_geoblocking_IP").val()+'</span><span><img src="/images/Neu/16x16/actions/edit-undo.png" onclick="removeFilter(\''+$("#film_geoblocking_IP").val().replace(/\./g,"")+'\')" /></span></div>';
      } else {
        return false;
      }
      break;
    case "country":
      if ($("#film_geoblocking_country").val() != 0) {
        html = '<div id="'+$("#film_geoblocking_country").val()+'"><span class="gbfilter">'+$("#film_geoblocking_country").val()+'</span><span><img src="/images/Neu/16x16/actions/edit-undo.png" onclick="removeFilter(\''+$("#film_geoblocking_country").val()+'\')" /></span></div>';
      } else {
        return false;
      }
      break;
     case "region":
      if ($("#film_geoblocking_region").val() != 0) {
        html = '<div id="'+$("#film_geoblocking_region").val()+'"><span class="gbfilter">'+$("#film_geoblocking_region").val()+'</span><span><img src="/images/Neu/16x16/actions/edit-undo.png" onclick="removeFilter(\''+$("#film_geoblocking_region").val()+'\')" /></span></div>';
      } else {
        return false;
      }
      break;
  }
  $("#film_geoblocking_filters").append(html);

}

function removeFilter( element ) {
  $("#"+element).remove();

}

function setCast( type, ctz, name, url ) {
  if (ctz > 0) {
    addCast(type);
  }
  els = document.getElementsByName('film_'+type+'_name[]');
  urs = document.getElementsByName('film_'+type+'_url[]');
  els[ctz].value=name;
  urs[ctz].value=url;
}

function addCast( element ) {
  html = '<div style="" class="row" id=""><span class="col1c"><input type="text" maxlength="" title="" value="" id="film_'+element+'_name[]" class="input" name="film_'+element+'_name[]"><span class="reqs" id="vtipfilm_'+element+'_name[]"></span><span class="reqs" id="vreqfilm_'+element+'_name[]">FALSE</span><span class="reqs" id="vskipfilm_'+element+'_name[]">false</span><span class="errorhidden" id="errorfilm_'+element+'_name[]">false</span></span><span class="col2a"><input type="text" maxlength="" title="" value="" id="film_'+element+'_url[]" class="input" name="film_'+element+'_url[]"><span><img src="/images/Neu/16x16/actions/edit-undo.png" onclick="removeCast(this)" /></span><span class="reqs" id="vtipfilm_'+element+'_url[]"></span><span class="reqs" id="vreqfilm_'+element+'_url[]">FALSE</span><span class="reqs" id="vskipfilm_'+element+'_url[]">false</span><span class="errorhidden" id="errorfilm_'+element+'_url[]">false</span></span></div>';
  $('.'+element+'_input').append(html);

}

function removeCast( element ) {
  
  $(element).parent().parent().parent().remove();

}

function submitFilm() {
  if (checkForm(thisValidator,"film")) {
    var values = '';
    $(".gbfilter").each(function(i){
      values += $(this).html()+',';
    });
    $("#film_geoblocking_type").val(values);
    return true;
  }
  return false;
}

$(document).ready(function(){

  $(".reveal").toggle(
    function(){ $(this).attr("src", "/images/Neu/16x16/actions/down.png"); },
    function(){ $(this).attr("src", "/images/Neu/16x16/actions/forward.png"); }
  );
  $(".reveal").click(function(){
    $("."+$(this).attr("id")+"_input").toggle();
  });
  if ($("#film_candc").val() != '') {
  var cast = (eval("(" + $("#film_candc").val() + ")"));
  if (cast != undefined) {
    if (cast.director != undefined) {
    console.log("Adding Director");
    for (var i = 0; i < cast.director.length; i++) {
      setCast( 'director', i, cast.director[i][0], cast.director[i][1] );
    }}
    if (cast.producer != undefined) {
    console.log("Adding Producer");
    for (var i = 0; i < cast.producer.length; i++) {
      setCast( 'producer', i, cast.producer[i][0], cast.producer[i][1] );
    }}
    if (cast.actor != undefined) {
    console.log("Adding Actor");
    for (var i = 0; i < cast.actor.length; i++) {
      setCast( 'actor', i, cast.actor[i][0], cast.actor[i][1] );
    }}
    if (cast.writer != undefined) {
    console.log("Adding Writer");
    for (var i = 0; i < cast.writer.length; i++) {
      setCast( 'writer', i, cast.writer[i][0], cast.writer[i][1] );
    }}
    if (cast.executive_producer != undefined) {
    console.log("Adding Executive Producer");
    for (var i = 0; i < cast.executive_producer.length; i++) {
      setCast( 'executive_producers', i, cast.executive_producer[i][0], cast.executive_producer[i][1] );
    }}
    if (cast.director_of_photography != undefined) {
    console.log("Adding Director of Photography");
    for (var i = 0; i < cast.director_of_photography.length; i++) {
      setCast( 'director_of_photography', i, cast.director_of_photography[i][0], cast.director_of_photography[i][1] );
    }}
    if (cast.music != undefined) {
    console.log("Adding Music");
    for (var i = 0; i < cast.music.length; i++) {
      setCast( 'music', i, cast.music[i][0], cast.music[i][1] );
    }}
    if (cast.co_producer != undefined) {
    console.log("Adding Co-Producer");
    for (var i = 0; i < cast.co_producer.length; i++) {
      setCast( 'co-producers', i, cast.co_producer[i][0], cast.co_producer[i][1] );
    }}
    if (cast.co_executive_producer != undefined) {
    console.log("Adding Co-Executive Producer");
    for (var i = 0; i < cast.co_executive_producer.length; i++) {
      setCast( 'co-executive_producers', i, cast.co_executive_producer[i][0], cast.co_executive_producer[i][1] );
    }}
    if (cast.associate_producers != undefined) {
    console.log("Adding Associate Producer");
    for (var i = 0; i < cast.associate_producer.length; i++) {
      setCast( 'associate_producers', i, cast.associate_producer[i][0], cast.associate_producer[i][1] );
    }}
    if (cast.supported != undefined) {
    console.log("Adding Supported");
    for (var i = 0; i < cast.supported.length; i++) {
      setCast( 'supported', i, cast.supported[i][0], cast.supported[i][1] );
    }}
    if (cast.link != undefined) {
    console.log("Adding Link");
    for (var i = 0; i < cast.link.length; i++) {
      setCast( 'link', i, cast.link[i][0], cast.link[i][1] );
    }}
  }}
});
