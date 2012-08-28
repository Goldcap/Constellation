//createDropdropCover = {($select){
//    $('<div class=""></div>')
//    var source = $("#source");
//    var selected = source.find("option[selected]");  // get selected <option>
//    var options = $("option", source);  // get all <option> elements
    // create <dl> and <dt> with selected value inside it
//    $("body").append('<dl id="target" class="dropdown"></dl>')
//    $("#target").append('<dt><a href="#">' + selected.text() + 
//        '<span class="value">' + selected.val() + 
//        '</span></a></dt>')
//    $("#target").append('<dd><ul></ul></dd>')
    // iterate through all the <option> elements and create UL
//    options.each(function(){
//        $("#target dd ul").append('<li><a href="#">' + 
//            $(this).text() + '<span class="value">' + 
//            $(this).val() + '</span></a></li>');
//    });
//}