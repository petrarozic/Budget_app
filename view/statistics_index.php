<?php require_once __SITE_PATH . '/view/_header.php'; ?>

<div class="row">
<div class="col-md-6 pull-left group">
<div class="container">
  <div class="row">
  <div class="col-md-5 pull-left card"  style="margin: 20px; border-radius: 25px;">
   <div class="card-body">
    <div class="radio">
      Choose time period:
      <br>
      <select>
        <option value="week">this week</option>
        <option value="month" selected>this month</option>
        <option value="year">this year</option>
        <option value="beginning">since first use of app</option>
      </select>
    </div>
   </div>
  </div>

  <div class="col-md-5 pull-right card"  style="margin: 20px; border-radius: 25px;">
   <div class="card-body">
    <div class="radio">
      Choose type of transactions:
      <br>
      <input type="radio" name="transaction" value="expense" checked> expenses
      <input type="radio" name="transaction" value="income"> incomes
    </div>
   </div>
  </div>
  </div>
</div>


<table class="table">
  <tr><th>Total: </th> <td><?php echo $total; ?> kn</td></tr>
  <tr><th>Average: </th> <td><?php echo $average; ?> kn</td></tr>
  <tr><th>Average per day: </th> <td><?php echo $apd; ?> kn</td></tr>
  <tr><th>Biggest: </th> <td><?php echo $biggest; ?> kn</td></tr>
</table>

<script>
$(document).ready(function() {
    $("input[type=radio][name=transaction]").change(function() {
        if (this.value == 'expense') {
            //header( 'Location: ' . __SITE_URL . '/index.php?rt=home'); ajax! sa get šaljemo dvije vrijednosti i primamo sve što treba
        }
        else if (this.value == 'income') {
            alert("income");
        }
    });
});
</script>

<br>
<div class="text-center">
<canvas height="230" width="230" id="myCanvas"></canvas>
<script> //bit će neki bolji
var data = [{
  label: "food",
  value: 100,
  color: 'lightgreen'
}, {
  label: "bills",
  value: 120,
  color: 'yellow'
}, {
  label: "shoes",
  value: 80,
  color: 'lightpink'
}];

var total = 0;
for (obj of data) {
  total += obj.value;
}

var canvas = document.getElementById('myCanvas');
var ctx = canvas.getContext('2d');
var previousRadian;
var middle = {
  x: canvas.width / 2,
  y: canvas.height / 2,
  radius: canvas.height / 2,
};




for (obj of data) {
  previousRadian = previousRadian || 0;
  obj.percentage = parseInt((obj.value / total) * 100)

  ctx.beginPath();
  ctx.fillStyle = obj.color;
  obj.radian = (Math.PI * 2) * (obj.value / total);
  ctx.moveTo(middle.x, middle.y);
  //middle.radius - 2 is to add border between the background and the pie chart
  ctx.arc(middle.x, middle.y, middle.radius - 2, previousRadian, previousRadian + obj.radian, false);
  ctx.closePath();
  ctx.fill();
  ctx.save();
  ctx.translate(middle.x, middle.y);
  ctx.fillStyle = "black";
  ctx.font = middle.radius / 10 + "px Arial";
  ctx.rotate(previousRadian + obj.radian);
  var labelText = "'" + obj.label + "' " + obj.percentage  + "%";
  ctx.fillText(labelText, ctx.measureText(labelText).width / 2, 0);
  ctx.restore();




  previousRadian += obj.radian;
}
</script>
</div>

</div>
<div class="col-md-6 pull-right">
<div class="row">
<div class="class=col-md-5 card" style="margin: 20px; border-radius: 25px;">
  <div class="card-body">
   <div class="radio">
     Choose time period:
     <br>
     <select>
       <option value="month" selected>this month</option>
       <option value="year">this year</option>
     </select>
   </div>
  </div>
</div>
</div>
<div class="title_canvas"> Monthly transactions </div>
<script>
window.onload = function () {

var chart = new CanvasJS.Chart("chartContainer", {
	theme: "light2", // "light1", "light2", "dark1", "dark2"
	animationEnabled: true,
/*	title:{
		text: "Share Value - 2016"
	},*/
	axisX: {
		interval: 1,
		intervalType: "month",
		valueFormatString: "MMM"
	},
	axisY:{
		valueFormatString: "$#0"
	},
	data: [{
		type: "line",
		markerSize: 12,
		xValueFormatString: "MMM, YYYY",
		yValueFormatString: "$###.#",
		dataPoints: [
			{ x: new Date(2016, 00, 1), y: 61, indexLabel: "gain", markerType: "triangle",  markerColor: "#6B8E23" },
			{ x: new Date(2016, 01, 1), y: 71, indexLabel: "gain", markerType: "triangle",  markerColor: "#6B8E23" },
			{ x: new Date(2016, 02, 1) , y: 55, indexLabel: "loss", markerType: "cross", markerColor: "tomato" },
			{ x: new Date(2016, 03, 1) , y: 50, indexLabel: "loss", markerType: "cross", markerColor: "tomato" },
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
chart.render();

} // <canvas height="450" width="550" style="border: solid 0.5px lightgrey;" id="canvas"></canvas>
</script>
<div class="text-center">
 <div id="chartContainer" style="height: 370px; width: 100%;"></div>
</div>

</div>
</div>


<?php
  $flag = "statistics";
?>

<?php require_once __SITE_PATH . '/view/_footer.php'; ?>
