// JavaScript Document
var timeplot;
var timeplot_lines;
var colors;
var timeplot_type;
var timeplot_id;

timeplot_lines = new Array();

//Assumtion is that there will never be more than 10 lines per plot
//If this number is low, just add more colors to the "colors" array below
colors = new Array();
colors[1]="#000000";
colors[2]="#000099";
colors[3]="#990099";
colors[4]="#cc0000";
colors[5]="#cc0000";
colors[6]="#cc0000";
colors[7]="#cc0000";
colors[8]="#cc0000";
colors[9]="#cc0000";
colors[10]="#cc0000";
  
function setPlot( line ) {
  timeplot_lines[line]["live"] = (timeplot_lines[line]["live"]) ? false : true ;
  loadPlot();
}

function initPlot() {
  
  for (i=1;i<=timeplot_tracks;i++) {
    timeplot_lines[i] = new Array();
    timeplot_lines[i]["live"]=true;
    timeplot_lines[i]["color"]=colors[i];
  }
}

function loadPlot() {
  
  var plotInfo;
  plotInfo = new Array();
  
  if (timeplot_tracks > 1) {
  for (i=1;i<=timeplot_tracks;i++) {
    dojo.style("timePlot_label_"+i,"color",timeplot_lines[i]["color"]);
  }}

  var eventSource = new Timeplot.DefaultEventSource();
  
  //var eventSource2 = new Timeplot.DefaultEventSource();

  var timeGeometry = new Timeplot.DefaultTimeGeometry({
    gridColor: new Timeplot.Color("#000000"),
    axisLabelsPlacement: "top"
  });

  var valueGeometry = new Timeplot.DefaultValueGeometry({
    gridColor: "#000000",
    min: timeplot_min,
    max: timeplot_max
  });
  
  var j;j=0;
  for (i=1;i<=timeplot_tracks;i++) {
    if (timeplot_lines[i]["live"]) {
      plotInfo[j] = Timeplot.createPlotInfo({
        id: "plot"+i,
        dataSource: new Timeplot.ColumnSource(eventSource,i),
        timeGeometry: timeGeometry,
        valueGeometry: valueGeometry,
        lineColor: timeplot_lines[i]["color"],
        fillColor: timeplot_lines[i]["color"],
        dotColor: "#eeeeee",
        showValues: true
      });
      j++;
    }
  }
  
  timeplot = Timeplot.create(document.getElementById("my-timeplot"), plotInfo);
  timeplot.loadText(timeplot_service + timeplot_type + "/" + timeplot_id, ",", eventSource);
  
  dojo.query(".timeplot-grid-label").forEach(function(node,i){
    value = node.innerHTML;
  });
}

var resizeTimerID = null;
function onResize() {
    if (resizeTimerID == null) {
        resizeTimerID = window.setTimeout(function() {
            resizeTimerID = null;
            timeplot.repaint();
        }, 100);
    }
}
