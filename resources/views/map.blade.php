<!DOCTYPE html>
<meta http-equiv="X-UA-Compatible" content="IE=Edge"/>
<html lang=en>

<head>
	<link rel="stylesheet" href={{ URL::asset('css/jquery-ui-1.9.2.custom.css')}} />
	

    <!-- Load JavaScript Libraries -->
    <script type="text/javascript" src="{{ URL::asset('js/jquery-2.1.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/jquery-ui.min.js') }}"></script>
 

</head>

<body style="background: #000;">

<div id="starDialog">
	
	ID: <input type="text" name="star_id" id="star_id">
	<br>
	X: <input type="text" name="star_x" id="star_x">
	Y: <input type="text" name="star_y" id="star_y">
</div>

</body>

<script type="text/javascript">

var ctx;
var scale = 1.0;
var centerX = 600;
var centerY = 400;
var stars = new Array();
var canvas;
var initialX = 0;
var initialY = 0;
var finalX = 0;
var finalY = 0;
var originX = 0;
var originY = 0;




function draw_b() {
  ctx.fillRect(50, 25, 150, 100);
}

function draw_circle(x,y,radius,color, fill) {

    ctx.beginPath();
    ctx.arc(x, y, radius, 0, Math.PI*2, false);
    ctx.closePath();
    
    if (fill == false) {
	    ctx.strokeStyle = color;
	    ctx.stroke();
    } else {
    	ctx.fillStyle = color;
    	ctx.fill();
    }
}

function drawEllipseByCenter(ctx, cx, cy, w, h) {
  drawEllipse(ctx, cx - w/2.0, cy - h/2.0, w, h);
}

function drawEllipse(ctx, x, y, w, h) {
  var kappa = .5522848,
      ox = (w / 2) * kappa, // control point offset horizontal
      oy = (h / 2) * kappa, // control point offset vertical
      xe = x + w,           // x-end
      ye = y + h,           // y-end
      xm = x + w / 2,       // x-middle
      ym = y + h / 2;       // y-middle

  ctx.beginPath();
  ctx.moveTo(x, ym);
  ctx.bezierCurveTo(x, ym - oy, xm - ox, y, xm, y);
  ctx.bezierCurveTo(xm + ox, y, xe, ym - oy, xe, ym);
  ctx.bezierCurveTo(xe, ym + oy, xm + ox, ye, xm, ye);
  ctx.bezierCurveTo(xm - ox, ye, x, ym + oy, x, ym);
  //ctx.closePath(); // not used correctly, see comments (use to close off open path)
  ctx.stroke();
}

function draw_star(x,y,starClass) {

		
	colors = [ "#fff", "#00ffff", "#ffff00", "#ffa500", "#770000"  ]
	x = x/1000;
	y = y/1000;

	draw_circle(x,y,starClass+1,colors[starClass],true);

	if (scale > 4) {
		for (j = 0; j < starClass; j++) {
			ctx.lineWidth = 0.1;
			draw_circle(x,y,starClass+1+((j+2)*5),"#777777",false);
		}

	}
}

function render_map() {
	ctx.save();
	ctx.setTransform(1,0,0,1,0,0);
	// Will always clear the right space
	ctx.clearRect(0,0,ctx.canvas.width,ctx.canvas.height);
	ctx.restore();

	
	stars.forEach (function(x) {
		draw_star(x.x,x.y,x.starClass);
	});
}


// Returns a random integer between min (included) and max (excluded)
// Using Math.round() will give you a non-uniform distribution!
function getRandomInt(min, max) {
  return Math.floor(Math.random() * (max - min)) + min;
}


function create_map() {
	
	for (var j = 0; j < 100; j++) {

		x = getRandomInt(1,1600000);
		y = getRandomInt(1,1000000);
		starClass = getRandomInt(0,5);

		stars.push({"id" : j,"x" : x, "y" : y, "starClass" : starClass});
	}
}

mouseWheel = function(e) {
  if(!e) {
    e = window.event;
  }
  e.preventDefault();
  // note sign reversal on e.detail
  var delta = (e.wheelDelta)?e.wheelDelta:-e.detail;
  var m = (delta > 0)?1.1:.9;
  scale *= m;
  console.log("scale is "+scale);

  ctx.setTransform(scale, 0, 0, scale, 0, 0);
  render_map();
}


function register_events() {

	 // zoom with mouse wheel
	 canvas.addEventListener('mousewheel',function(e){
	      mouseWheel(e); 
	      return false;
	  }, false);
	  // Firefox hack
	 canvas.addEventListener('DOMMouseScroll',function(e){
	      mouseWheel(e); 
	      //alert("Zoom");
	      return false;
	  }, false);

	var dragging = 0;
	 //
	 // handle click and drag
	$("#mainmap").on('mousedown', function (e) {
		dragging = -0;

		initialX = e.pageX;
		initialY = e.pageY;

		  $("#mainmap").on('mousemove', function handler(e) {
		  	dragging = 1;
		  	console.log(e);
		    if (e.type === 'mouseup') {
		      
		    } else {
		      //console.log("drag");
		      // drag
		    }
		  });
		   
	 
	});
	$("#mainmap").on('mouseup', function (e) {
		if (dragging == 1 ) {
			var tx = initialX - e.pageX;
			var ty = initialY - e.pageY;

			originX = originX - tx;
			originY = originY - ty;

			console.log("tx = "+tx+" ty = "+ty);

			ctx.translate(tx,ty);
			render_map();
		} else {
			// click
		      
		      console.log(e);
		      clickX = e.clientX - canvas.offsetLeft;
		      clickY = e.clientY - canvas.offsetTop;
			  console.log("click at "+clickX+" , "+clickY);

		      stars.forEach (function(star) {
		      		if (clickY > ((star.y/1000)-20-originY)*scale && clickY < ((star.y/1000)+20-originY)*scale
		      			&& clickX > ((star.x/1000)-20-originX)*scale && clickX < ((star.x/1000)+20-originX)*scale) {
		      			console.log(star);
						draw_circle(star.x,star.y,10,"#fff", false);
						$("#star_id").val(star.id);
						$("#star_x").val(star.x);
						$("#star_y").val(star.y);
						$("#starDialog").dialog("open");

						
		      		}


			   });
		}
		$("#mainmap").off('mousemove');
	});
}



$( document ).ready(function() {


	$("#starDialog").dialog({

		autoOpen : false,
		modal : true, 
		dialogClass: "no-close",
		width: 800,
		height: 500,
		buttons: {
			'Cancel' : function() {
				$(this).dialog("close");
			},

			'OK' : function() {
		

			},

		}

	});

	var size = {
	  width: window.innerWidth || document.body.clientWidth,
	  height: window.innerHeight || document.body.clientHeight
	}
	canvas = document.createElement('canvas');
	canvas.id     = "mainmap";
	canvas.width  = size.width;
	canvas.height = size.height;
	canvas.style.zIndex   = 8;
	canvas.style.position = "absolute";
	canvas.style.border   = "1px solid";

	document.body.appendChild(canvas);

	
	ctx = canvas.getContext("2d");
	
	create_map();
	render_map();
	register_events();

});	 

</script>
