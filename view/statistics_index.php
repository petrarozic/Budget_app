<?php
  $flag ="statistic";
  require_once __SITE_PATH . '/view/_header.php'; ?>

  <div class="row">
    <div class="col-md-3 pull-right card"  style="margin: 20px; border-radius: 25px;">
     <div class="card-body">
      <div class="radio">
        Type of transactions:
        <br>
        <input type="radio" name="transaction" value="expense" checked style="margin-top: 7px;"> expenses
        <input type="radio" name="transaction" value="income" style="margin-left:10px;"> incomes
      </div>
     </div>
    </div>
    <div class="col-md-2 pull-left card"  style="margin: 20px; border-radius: 25px;">
      <div class="card-body">
        <div class="radio">
          Time period:
          <br>
          <input type="radio" name="period" value="month" checked style="margin-top: 7px;"> month
          <input type="radio" name="period" value="year" style="margin-left:10px;"> year
        </div>
      </div>
    </div>

    <div class="col-md-3 pull-right card"  style="margin-top: 40px; margin-left: 35px; border:none;">
  <div id="choose">
    <button class="ChangeButton" id="left"> <i class="fas fa-angle-left"></i></button>
    <span id="time"> </span>
    <button class="ChangeButton" id="right"> <i class="fas fa-angle-right"></i></button>
  </div>
</div>
 </div>

<div class="row">
  <div class="col-md-6 pull-left group" style="padding-top:20px; margin: 40px 0px;">
      <table class="table">
        <tr><th>Total: </th> <td id="total"></td></tr>
        <tr><th>Average amount: </th> <td id="average"></td></tr>
        <tr><th>Average per day: </th> <td id="apd"></td></tr>
        <tr><th>Biggest: </th> <td id="biggest"></td></tr>
      </table>

      <br>
      <div class="text-center">
      <canvas height="300" width="500" id="myCanvas"></canvas>
      </div>
  </div>

  <div class="col-md-6 pull-right group" style="padding-top:20px; margin: 40px 0px;">
      <div class="text-center">
      <div id="chartContainer" style="height: 370px; width: 100%;"></div>
      </div>
  </div>
</div>

<script>
var months = ["January","February","March","April","May","June","July","August","September","October","November"," 	December"];
var d = new Date();
var current_y = d.getFullYear();
var current_m = d.getMonth();
var first_m; //mjesec i godina prve transakcije usera (za ranije nema smisla raditi statistiku)
var first_y;
get_first(); // "puni" first_m i first_y
var y = current_y; //4 "zastave" -> uvijek znamo gleda li se mjesec ili godina, income ili expense, koji se mjesec gleda i koja godina (mjesec po potrebi)
var m = current_m;
var p = 1; //defaultno je period month (0 je year)
var t = 1; //defaultno je transakcija expense (0 je income)
var flag = 1; //jos jedna zastava -> sluzi da ne crtamo line chart ako smo samo promijenili type of transactions ; 0 = ne treba se crtati line chart
$( document ).ready( function()
{
    $("#time").html(months[m] + " " + y);
    inform();

    $("#right").on( "click", next); // sljedeci mjesec/godina, zove inform
    $("#left").on("click", previous);
    $("input[type=radio][name=transaction]").on("change", transaction_changed) //promijeni t, zove inform
    $("input[type=radio][name=period]").on("change", period_changed) //promijeni p, promijeni #time,  zove inform
    //inform uvijek poziva crtanje grafova
});

next = function()
{
  if(p){ //gleda se mjesec
    if(y == current_y && m == current_m)
      return;
    if(m == 11){m = 0; ++y;}
    else ++m;
    $("#time").html(months[m] + " " + y);
  }
  else{ //gleda se godina
    if(y == current_y)
      return;
    ++y;
    $("#time").html(y);
  }
  inform();
}

previous = function()
{
  if(p){ //gleda se mjesec
    if(y == first_y && m == first_m)
       return;
    if(m == 0){m = 11; --y;}
    else --m;
    $("#time").html(months[m] + " " + y);
  }
  else{ //gleda se godina
    if(y == first_y)
      return;
    --y;
    $("#time").html(y);
  }
  inform();
}

transaction_changed = function()
{
  t = 1 - t;
  flag = 0;
  inform();
}

period_changed = function()
{
  p = 1 - p;
  m = current_m;
  y = current_y;
  if(p) $("#time").html(months[m] + " " + y);
  else $("#time").html(y);
  inform();
}

function inform()
{
  $.ajax(
      {
        url: window.location.pathname+"?rt=statistics/getInform",
        data:
        {
          p:p,
          t:t,
          m:m,
          y:y
        },
        dataType: "json",
        error: function( xhr, status )
        {
          if( status !== null )
             console.log( "Greška prilikom Ajax poziva: " + status);
        },
        success: function(data)
        {
          $("#total").html(data.total + "  kn");
          $("#average").html(data.average + "  kn");
          $("#apd").html(data.apd + "  kn");
          $("#biggest").html(data.biggest + "  kn");
          pie_chart();
          if(flag){
            if(p)
              line_chart_month();
            else
              line_chart_year();
          }
          flag = 1;
        }
      });
}

function get_first()
{
   $.ajax(
      {
        url: window.location.pathname+"?rt=statistics/getFirst",
        data:{},
        dataType: "json",
        error: function( xhr, status )
        {
          if( status !== null )
             console.log( "Greška prilikom Ajax poziva: " + status);
        },
        success: function(data)
        {
          first_m = data.month;
          first_y = data.year;
        }
      });
}

  function pie_chart() //da su pravi podaci i legenda
{
  // tip transakcije : t (0 income, 1 expense), perod :  p (0 godina, 1 mjesec ), godina : y, mjesec : m
  $.ajax(
     {
       url: window.location.pathname+"?rt=statistics/getPieData",
       data:{
         type : t,
         period : p,
         month : m,
         year : y
       },
       dataType: "json",
       error: function( xhr, status )
       {
         if( status !== null ) {
            console.log( "Greška prilikom Ajax poziva: " + status);
            }
       },
       success: function(data)
       {

// Color array




var colorArray = ['#4dc9c9', 'rgb(230, 228, 102)','rgb(105, 198, 94)', 'rgba(212, 114, 214, 0.85)', 'rgb(115, 115, 233)',
            		  'Darkseagreen', 'rgb(226, 119, 61)', 'Peru','#FF4D4D',
            		  '#E6B333', '#3366E6', '#999966', '#99FF99', '#B34D4D',
            		  '#FF99E6', '#CCFF1A', '#FF1A66', '#E6331A', '#33FFCC',
            		  '#66994D', '#B366CC', '#4D8000', '#B33300', '#CC80CC',
            		  '#66664D', '#991AFF', '#E666FF', '#4DB3FF', '#1AB399',
            		  '#E666B3', '#33991A', '#CC9999', '#B3B31A', '#00E680',
            		  '#4D8066', '#809980', '#E6FF80', '#1AFF33', '#999933',
            		  '#FF3380', '#CCCC00', '#66E64D', '#4D80CC', '#00B3E6',
                  '#E64D66', '#4DB380', '#99E6E6', '#6666FF', '#66991A'];

         var Data = [];
         for( var i = 0; i < data[0].length ; i++){
           Data[i] = [];
         }

         for( var i = 0; i < data[0].length ; i++){
            Data[i]['label'] = data[0][i];
            Data[i]['value'] = Number(data[1][i]);
              Data[i]['color'] = colorArray[i];
         }


  if ( Data.length == 0 ){
    var canvas = document.getElementById('myCanvas');
    var ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.fillStyle = "black";
    ctx.font = "17px Arial";
    ctx.fillText("There are no transactions in selected period.", 10, canvas.height/2 -100);
  }
  else{

    var total = 0;
    for (obj of Data) {
      total += obj.value;
    }

    var canvas = document.getElementById('myCanvas');
    var ctx = canvas.getContext('2d');
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    var previousRadian = 0;
    var radius = canvas.height / 3;
    var middle = {
      x: canvas.width / 4,
      y: canvas.height / 2,
    };

    var radian;
    var percentage;
    for (obj of Data){
      percentage = Math.round(obj.value/total * 100);

      ctx.beginPath();
      ctx.fillStyle = obj.color;
      radian = (Math.PI * 2) * (obj.value / total);
      ctx.moveTo(middle.x , middle.y);
      ctx.arc(middle.x, middle.y, radius, previousRadian, previousRadian + radian);
      ctx.fill();

      ctx.save();
      ctx.translate(middle.x, middle.y);
      ctx.fillStyle = "black";
      ctx.font = "12px Arial";
      ctx.rotate(previousRadian + radian);
      var labelText = percentage  + "%";
      ctx.fillText(labelText, radius/2 + 10, -2);
      ctx.restore();

      previousRadian += radian;
    }


    ctx.restore();
    var i = 20;
    for (obj of Data){
      ctx.beginPath();
      ctx.fillStyle = obj.color;
      ctx.fillRect((canvas.width/2) + 20, 20  + i, 20, 20);
      ctx.fill();
      ctx.fillStyle = "black";
      ctx.font = "16px Arial";
      ctx.fillText(obj.label , canvas.width/2 + 55, 37 + i);
      i+=30;
      ctx.closePath();
    }

    ctx.stroke();

  }

       }
     });
}


function line_chart_year()
{
  var line = [];
  $.ajax(
     {
       url: window.location.pathname+"?rt=statistics/lineChart",
       data:{
         y:y,
         flag:'year'
       },
       dataType: "json",
       error: function( xhr, status )
       {
         if( status !== null )
            console.log( "Greška prilikom Ajax poziva: " + status);
       },
       success: function(data)
       {
        line = data.line;
        console.log(line);

        var colors = ["#b8b894"];
        var shapes = ["circle"];
        var type = [""];

        var i;
        for (i = 1; i < line.length; ++i) {
          if((line[i] - line[i-1]) > 0){
            colors[i] = "rgba(0, 153, 77, 0.8)";
            shapes[i] = "circle";
            type[i] = "gain";
          }
          else if((line[i] - line[i-1]) === 0){
            colors[i] = "#b8b894";
            shapes[i] = "circle";
            type[i] = "";
          }
          else{
            colors[i] = "rgba(179, 0, 0, 0.7)";
            shapes[i] = "cross";
            type[i] = "loos";
          }
        }
        var chart = new CanvasJS.Chart("chartContainer", {
        	theme: "light2",
        	animationEnabled: true,
        	axisX: {
        		interval: 1,
        		intervalType: "month",
        		valueFormatString: "MMM"
        	},
        	axisY:{
        		valueFormatString: "# kn"
        	},
        	data: [{
        		type: "line",
        		markerSize: 10,
        		xValueFormatString: "MMM, YYYY",
        		yValueFormatString: "# kn",
        		dataPoints: [
        			{ x: new Date(y, 00, 1), y: line[0], indexLabel: type[0], markerType: shapes[0],  markerColor: colors[0] },
        			{ x: new Date(y, 01, 1), y: line[1], indexLabel: type[1], markerType: shapes[1],  markerColor: colors[1] },
        			{ x: new Date(y, 02, 1) , y: line[2], indexLabel: type[2], markerType: shapes[2],  markerColor: colors[2] },
        			{ x: new Date(y, 03, 1) , y: line[3], indexLabel: type[3], markerType: shapes[3],  markerColor: colors[3] },
        			{ x: new Date(y, 04, 1) , y: line[4], indexLabel: type[4], markerType: shapes[4],  markerColor: colors[4] },
        			{ x: new Date(y, 05, 1) , y: line[5], indexLabel: type[5], markerType: shapes[5],  markerColor: colors[5] },
        			{ x: new Date(y, 06, 1) , y: line[6], indexLabel: type[6], markerType: shapes[6],  markerColor: colors[6] },
        			{ x: new Date(y, 07, 1) , y: line[7], indexLabel: type[7], markerType: shapes[7],  markerColor: colors[7] },
        			{ x: new Date(y, 08, 1) , y: line[8], indexLabel: type[8], markerType: shapes[8],  markerColor: colors[8] },
        			{ x: new Date(y, 09, 1) , y: line[9], indexLabel: type[9], markerType: shapes[9],  markerColor: colors[9] },
        			{ x: new Date(y, 10, 1) , y: line[10], indexLabel: type[10], markerType: shapes[10],  markerColor: colors[10] },
        			{ x: new Date(y, 11, 1) , y: line[11], indexLabel: type[11], markerType: shapes[11],  markerColor: colors[11] }
        		]
        	}]
        });
      chart.render();
      }
    });
}

function line_chart_month()
{
  var line = [];
  $.ajax(
     {
       url: window.location.pathname+"?rt=statistics/lineChart",
       data:{
         y:y,
         m:m,
         flag:'month'
       },
       dataType: "json",
       error: function( xhr, status )
       {
         if( status !== null )
            console.log( "Greška prilikom Ajax poziva: " + status);
       },
       success: function(data)
       {
        line = data.line;
        console.log(line);

        var colors = ["#b8b894"];
        var shapes = ["circle"];
        var type = [""];

        var i;
        for (i = 1; i < line.length; ++i) {
          if(line[i] === 'undefined'){
            shapes[i] = "none";
          }
          else if((line[i] - line[i-1]) > 0){
            colors[i] = "rgba(0, 153, 77, 0.8)";
            shapes[i] = "circle";
            type[i] = "gain";
          }
          else if((line[i] - line[i-1]) === 0){
            colors[i] = "#b8b894";
            shapes[i] = "circle";
            type[i] = "";
          }
          else{
            colors[i] = "rgba(179, 0, 0, 0.7)";
            shapes[i] = "cross";
            type[i] = "loos";
          }
        }
        var chart = new CanvasJS.Chart("chartContainer", {
        	theme: "light2",
        	animationEnabled: true,

        	axisY:{
        		valueFormatString: "# kn"
        	},
        	data: [{
        		type: "line",
        		markerSize: 10,
        		yValueFormatString: "# kn",
        		dataPoints: [
        			{ x: 1, y: line[0], indexLabel: type[0], markerType: shapes[0], markerColor: colors[0] },
              { x: 2, y: line[1], indexLabel: type[1], markerType: shapes[1], markerColor: colors[1] },
              { x: 3, y: line[2], indexLabel: type[2], markerType: shapes[2], markerColor: colors[2] },
              { x: 4, y: line[3], indexLabel: type[3], markerType: shapes[3], markerColor: colors[3] },
              { x: 5, y: line[4], indexLabel: type[4], markerType: shapes[4], markerColor: colors[4] },
              { x: 6, y: line[5], indexLabel: type[5], markerType: shapes[5], markerColor: colors[5] },
              { x: 7, y: line[6], indexLabel: type[6], markerType: shapes[6], markerColor: colors[6] },
              { x: 8, y: line[7], indexLabel: type[7], markerType: shapes[7], markerColor: colors[7] },
              { x: 9, y: line[8], indexLabel: type[8], markerType: shapes[8], markerColor: colors[8] },
              { x: 10, y: line[9], indexLabel: type[9], markerType: shapes[9], markerColor: colors[9] },
              { x: 11, y: line[10], indexLabel: type[10], markerType: shapes[10], markerColor: colors[10] },
              { x: 12, y: line[11], indexLabel: type[11], markerType: shapes[11], markerColor: colors[11] },
              { x: 13, y: line[12], indexLabel: type[12], markerType: shapes[12], markerColor: colors[12] },
              { x: 14, y: line[13], indexLabel: type[13], markerType: shapes[13], markerColor: colors[13] },
              { x: 15, y: line[14], indexLabel: type[14], markerType: shapes[14], markerColor: colors[14] },
              { x: 16, y: line[15], indexLabel: type[15], markerType: shapes[15], markerColor: colors[15] },
              { x: 17, y: line[16], indexLabel: type[16], markerType: shapes[16], markerColor: colors[16] },
              { x: 18, y: line[17], indexLabel: type[17], markerType: shapes[17], markerColor: colors[17] },
              { x: 19, y: line[18], indexLabel: type[18], markerType: shapes[18], markerColor: colors[18] },
              { x: 20, y: line[19], indexLabel: type[19], markerType: shapes[19], markerColor: colors[19] },
              { x: 21, y: line[20], indexLabel: type[20], markerType: shapes[20], markerColor: colors[20] },
              { x: 22, y: line[21], indexLabel: type[21], markerType: shapes[21], markerColor: colors[21] },
              { x: 23, y: line[22], indexLabel: type[22], markerType: shapes[22], markerColor: colors[22] },
              { x: 24, y: line[23], indexLabel: type[23], markerType: shapes[23], markerColor: colors[23] },
              { x: 25, y: line[24], indexLabel: type[24], markerType: shapes[24], markerColor: colors[24] },
              { x: 26, y: line[25], indexLabel: type[25], markerType: shapes[25], markerColor: colors[25] },
              { x: 27, y: line[26], indexLabel: type[26], markerType: shapes[26], markerColor: colors[26] },
              { x: 28, y: line[27], indexLabel: type[27], markerType: shapes[27], markerColor: colors[27] },
              { x: 29, y: line[28], indexLabel: type[28], markerType: shapes[28], markerColor: colors[28] },
              { x: 30, y: line[29], indexLabel: type[29], markerType: shapes[29], markerColor: colors[29] },
              { x: 31, y: line[30], indexLabel: type[30], markerType: shapes[30], markerColor: colors[30] },
        		]
        	}]
        });
      chart.render();
      }
    });
}
</script>


<?php
  $flag = "statistics";
?>


<?php require_once __SITE_PATH . '/view/_footer.php'; ?>
