<link rel="stylesheet" href="/css/form.css" />
<link rel="stylesheet" href="/css/upload.css" />
<div class="inner_container clearfix">
    <h3 class="uppercase host_show_header">Report for Donner Pass</h3>
	<div class="block" style="margin-bottom:40px">
        <div class="form-fieldset">
            <div class="form-row clearfix">
                <label for="film_name ">Time Frame</label>
                <div class="input">
                    <input class="text-input span2 post-data" id="report_start_date" name="film_name" value="01/15/2011"  />
                    <input class="text-input span2 post-data" id="report_end_date" name="film_name" value="01/26/2011"  />
                    <span class="button button_blue button-medium">Apply</span>
                </div>
            </div>
        </div>
	    <div id="placeholderViews" style="width:900px;height:400px; margin-bottom: 20px"></div>

	</div>
</div>


<script type="text/javascript">
$(function () {
    var sin = [], cos = [], credit =[];
    var d = moment().subtract('days', 14);
    for (var i = 0; i < 40; i ++) {
        d.add('days', 1);

        var val = d.valueOf();

        sin.push([val, Math.sin(i) + 4]);
        cos.push([val, Math.cos(i)+ 4]);
        credit.push([val, (Math.cos(i) + 4 )/ 2]);
    }

    var plot = $.plot($("#placeholderViews"),
           [ { data: sin, label: "Page Views"}, { data: cos, label: "Film Views" } ], {
               series: {
                   lines: { show: true },
                   points: { show: true }
               },
               grid: { hoverable: true, clickable: true },
               yaxis: { min:0 },
               xaxis: { mode: "time",  timeformat: "%y/%m/%d", minTickSize: [1, "day"]}
             });


    function showTooltip(x, y, contents) {
        $('<div id="tooltip">' + contents + '</div>').css( {
            position: 'absolute',
            display: 'none',
            top: y + 5,
            left: x + 5,
            border: '1px solid #fdd',
            padding: '2px',
            'background-color': '#fee',
            opacity: 0.80
        }).appendTo("body").fadeIn(200);
    }

    var previousPoint = null;
    $("#placeholderViews").bind("plothover", function (event, pos, item) {
        $("#x").text(pos.x.toFixed(2));
        $("#y").text(pos.y.toFixed(2));

        if (item) {
            if (previousPoint != item.dataIndex) {
                previousPoint = item.dataIndex;
                
                $("#tooltip").remove();
                var x = item.datapoint[0].toFixed(2),
                    y = item.datapoint[1].toFixed(2);
                
                showTooltip(item.pageX, item.pageY, item.series.label + " of " + x + " = " + y);
            }
        }
    });

    $("#placeholderViews").bind("plotclick", function (event, pos, item) {
        if (item) {
            // $("#clickdata").text("You clicked point " + item.dataIndex + " in " + item.series.label + ".");
            plot.highlight(item.series, item.datapoint);
        }
    });

    var d = new Date();
    $('#report_start_date').datepicker({
        numberOfMonths: 1,
        maxDate: '01/26/2012'
    });

    $('#report_end_date').datepicker({
        numberOfMonths: 1,
        maxDate: '01/26/2012',
        onSelect: function(dateText, inst) { 
            $('#report_start_date').datepicker( "option" , {maxDate: new Date(dateText) } )
        }
    })

});
</script>
