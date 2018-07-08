<?php
  $flag = "statistic";
  require_once __SITE_PATH . '/view/_headerCRO.php';

  $_SESSION['page'] = 'statistics';
  $_SESSION['lang'] = 'CRO';

?>

  <div class="row">
    <div class="col-md-2 pull-right card"  style="margin: 20px; border-radius: 25px;">
     <div class="card-body">
      <div class="radio">
        Tip transakcije:
        <br>
        <input type="radio" name="transaction" value="expense" checked style="margin-top: 7px;"> troškovi
        <input type="radio" name="transaction" value="income" style="margin-left:15px;"> prihodi
      </div>
     </div>
    </div>
    <div class="col-md-2 pull-left card"  style="margin: 20px; border-radius: 25px;">
      <div class="card-body">
        <div class="radio">
          Vremenski period:
          <br>
          <input type="radio" name="period" value="month" checked style="margin-top: 7px;"> mjesec
          <input type="radio" name="period" value="year" style="margin-left:15px;"> godina
        </div>
      </div>
    </div>

    <div class="col-md-2 pull-right card"  style="margin-top: 40px; margin-left: 35px; border:none;">
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
        <tr><th>Ukupno: </th> <td id="total"></td></tr>
        <tr><th>Prosječni iznos: </th> <td id="average"></td></tr>
        <tr><th>Prosječni iznos po danu: </th> <td id="apd"></td></tr>
        <tr><th>Najveći iznos: </th> <td id="biggest"></td></tr>
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




var colorArray = ['#4ea8a8', 'rgb(209, 162, 191)','rgb(124, 215, 113)', 'rgba(244, 114, 7, 0.85)', 'Darkcyan',
                'Darkseagreen', 'Darkseagreen', 'Peru', '#6680B3', '#66991A',
                '#E6B333', '#3366E6', '#999966', '#99FF99', '#B34D4D',
                '#FF99E6', '#CCFF1A', '#FF1A66', '#E6331A', '#33FFCC',
                '#66994D', '#B366CC', '#4D8000', '#B33300', '#CC80CC',
                '#66664D', '#991AFF', '#E666FF', '#4DB3FF', '#1AB399',
                '#E666B3', '#33991A', '#CC9999', '#B3B31A', '#00E680',
                '#4D8066', '#809980', '#E6FF80', '#1AFF33', '#999933',
                '#FF3380', '#CCCC00', '#66E64D', '#4D80CC', '#00B3E6',
                '#E64D66', '#4DB380', '#FF4D4D', '#99E6E6', '#6666FF'];

       var Data = [];
       for( var i = 0; i < data[0].length ; i++){
         Data[i] = [];
       }

       for( var i = 0; i < data[0].length ; i++){
          Data[i]['label'] = data[0][i];
          Data[i]['value'] = Number(data[1][i]);
            Data[i]['color'] = colorArray[i];
       }

// ZApocinje

if ( Data.length == 0 ){
  var canvas = document.getElementById('myCanvas');
  var ctx = canvas.getContext('2d');
  ctx.clearRect(0, 0, canvas.width, canvas.height);
  ctx.fillStyle = "black";
  ctx.font = "20px Arial";
  ctx.fillText("U odabranom periodu ne postoji transakcija.", 50 , canvas.height/2 - 100);
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


  ctx.beginPath();
  ctx.lineWidth="1";
  ctx.strokeStyle="##bdc2bb";
  ctx.rect( (canvas.width/2), 20, 200, canvas.height-40 );
  ctx.stroke();
  ctx.closePath();
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

// Zavrsava
     }
   });
}



function line_chart_year()
{
  var line = [];
  $.ajax(
     {
       url: window.location.pathname+"?rt=statistics/lineChartYear",
       data:{
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
         line = data.line;
         console.log(line);
       }
     });

     //document wait
  var colors = [];
  var shape = [];
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
  			{ x: new Date(y, 00, 1), y: 40, markerType: "circle",  markerColor: "rgba(0, 153, 77, 0.8)" },
  			{ x: new Date(y, 01, 1), y: 20, indexLabel: "loos", markerType: "cross",  markerColor: "rgba(179, 0, 0, 0.7)" },
  			{ x: new Date(y, 02, 1) , y: 55, indexLabel: "loss", markerType: "cross", markerColor: "rgba(0, 153, 77, 0.8)" },
  			{ x: new Date(y, 03, 1) , y: 50, indexLabel: "loss", markerType: "cross", markerColor: "tomato" },
  			{ x: new Date(y, 04, 1) , y: 65, indexLabel: "gain", markerType: "triangle", markerColor: "#6B8E23" },
  			{ x: new Date(y, 05, 1) , y: 85, indexLabel: "gain", markerType: "triangle", markerColor: "#6B8E23" },
  			{ x: new Date(y, 06, 1) , y: 68, indexLabel: "loss", markerType: "cross", markerColor: "tomato" },
  			{ x: new Date(y, 07, 1) , y: 28, indexLabel: "loss", markerType: "cross", markerColor: "tomato" },
  			{ x: new Date(y, 08, 1) , y: 34, indexLabel: "gain", markerType: "triangle", markerColor: "#6B8E23" },
  			{ x: new Date(y, 09, 1) , y: 24, indexLabel: "loss", markerType: "cross", markerColor: "tomato" },
  			{ x: new Date(y, 10, 1) , y: 44, indexLabel: "gain", markerType: "triangle", markerColor: "#6B8E23" },
  			{ x: new Date(y, 11, 1) , y: 34, indexLabel: "loss", markerType: "cross", markerColor: "tomato" }
  		]
  	}]
  });
  chart.render();

}


function line_chart_month()
{
  /*var chart = new CanvasJS.Chart("chartContainer", {
  	theme: "light2",
  	animationEnabled: true,
  	axisX: {
  		interval: 1,
  		intervalType: "month",
  		valueFormatString: "MMM"
  	},
  	axisY:{
  		valueFormatString: " # kn"
  	},
  	data: [{
  		type: "line",
  		markerSize: 12,
  		xValueFormatString: "MMM, YYYY",
  		yValueFormatString: "###.# kn",
  		dataPoints: [
  			{ x: 1, y: 61, markerType: "circle",  markerColor: "#6B8E23" },
  			{ x: 2, y: 71, indexLabel: "gain", markerType: "triangle",  markerColor: "#6B8E23" },
  			{ x: 3 , y: 55, indexLabel: "loss", markerType: "cross", markerColor: "tomato" },
  			{ x: 4 , y: 50, indexLabel: "loss", markerType: "cross", markerColor: "tomato" },
  			{ x: new Date(2016, 04, 1) , y: 65, indexLabel: "gain", markerType: "triangle", markerColor: "#6B8E23" },
  			{ x: new Date(2016, 05, 1) , y: 85, indexLabel: "gain", markerType: "triangle", markerColor: "#6B8E23" },
  			{ x: new Date(2016, 06, 1) , y: 68, indexLabel: "loss", markerType: "cross", markerColor: "tomato" },
  			{ x: new Date(2016, 07, 1) , y: 28, indexLabel: "loss", markerType: "cross", markerColor: "tomato" },
  			{ x: new Date(2016, 08, 1) , y: 34, indexLabel: "gain", markerType: "triangle", markerColor: "#6B8E23" },
  			{ x: new Date(2016, 09, 1) , y: 24, indexLabel: "loss", markerType: "cross", markerColor: "tomato" },
  			{ x: new Date(2016, 10, 1) , y: 44, indexLabel: "gain", markerType: "triangle", markerColor: "#6B8E23" },
  			{ x: new Date(2016, 11, 1) , y: 34, indexLabel: "loss", markerType: "cross", markerColor: "tomato" }
  		]
  	}]
  });
  chart.render();*/

}
</script>


<?php
$_SESSION['page'] = 'statistics';
$_SESSION['lang'] = 'CRO';
?>


<?php require_once __SITE_PATH . '/view/_footerCRO.php'; ?>
