<?php
	
	/**
	 * 
	 */


	session_start();

	// error_reporting(E_ALL);

	require_once("./assets/php/weather.php");
	$weather = getWeather();

?>
<!doctype html>
<html>
<head>
	<script type="text/javascript" src="assets/js/errorhandler.js"></script>
	<meta charset="utf-8">

	<title>Øyafestivalen 2015</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="/html5/assets/font/clan.css">
	<script type="text/javascript" src="assets/js/mustache.js"></script>
	<script type="text/javascript" src="assets/js/pi.core.js"></script>
	<script type="text/javascript" src="assets/js/pi.xhr.js"></script>
	<style type="text/css">

		* {
			margin  : 0;
			padding : 0;
      cursor: none;
		}

		html, body {
			color 			: #000;
			font-family : 'ClanOT', sans-serif;

			/* no scrollbars */
			overflow: hidden;

      -webkit-font-smoothing: antialiased;

      -webkit-box-sizing: border-box;
         -moz-box-sizing: border-box;
              box-sizing: border-box;

     /* Make text non-selectable */
      -webkit-touch-callout: none;
        -webkit-user-select: none;
           -moz-user-select: none;
            -ms-user-select: none;
                user-select: none;

      /* hide mouse pointer */
      cursor: none;
		}

		::-webkit-scrollbar {
			width: 0;
		}

		::-webkit-scrollbar-track {
			background: none;
		}

		::-webkit-scrollbar-thumb {
			background: none;
		}


		iframe.contentframe {

			/* fill container */
			width: 100%;
			height: 100%;
			min-height: 100%;

			background-color: rgba(255,5,45,0.7);
			border: none;

			-webkit-transition: all .4s ease-out;
							transition: all .4s ease-out;
		}


		header {
			position: relative;
			display: block;
			font-weight : 700;
			top 				: 0;
			height 			: 90px;
			color 			: #fff;
			min-width 	: 100%;
			background 	: rgb(38,188,244);
			background 	: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(38,188,244,1)), color-stop(50%, rgba(38,188,244,1)), color-stop(51%, rgba(0,173,242,1)), color-stop(100%, rgba(0,173,242,1)));
			background  : -webkit-linear-gradient(top, rgba(38,188,244,1) 0%, rgba(38,188,244,1) 50%, rgba(0,173,242,1) 51%, rgba(0,173,242,1) 100%);
			background  : linear-gradient(to bottom, rgba(38,188,244,1) 0%, rgba(38,188,244,1) 50%, rgba(0,173,242,1) 51%, rgba(0,173,242,1) 100%);
			overflow 		: hidden;

			-webkit-transition: all .4s ease-out;
							transition: all .4s ease-out;
		}

		header.fullscreen {
			top: -90px;
		}

		header .title {
			padding 		: 8px;
			margin-left : auto;
			margin-right: auto;

			text-align 	: center;

			background-repeat 	: no-repeat;
			background-position : center center;
		}

		header .weather {
			float: right;
		}

		header .clock {
			float: left;
		}

		header .temperature {
			display 		: block;
			font-size 	: 40px;
			line-height : 42px;
			margin-top 	: 0;
			margin-right: 10px;
			text-align 	: right;
		}

		section.content {
			position 	: relative;
			top 			: 0;
			width 		: 100%;
			height 		: 100%;
			/*max-height: 569px;*/
			overflow 	: hidden;

			-webkit-transition: all .4s ease-out;
							transition: all .4s ease-out;
		}

		footer {
			padding 				: 8px 10px 4px;
			text-align 			: center;
			color 					: #fff;
			height 					: 45px;
			font-size 			: 36px;
			line-height 		: 38px;
			font-weight 		: 500;
			background-color: #000;
			overflow 				: hidden;

			-webkit-transition : all .4s ease-out;
							transition : all .4s ease-out;
		}


		ul, li {
			list-style-type: none;
			list-style: none;
		}

		li.period {
			-webkit-animation: period .4s ;
		}

		.clock {
			margin-top 	: 50px;
			margin-left : 10px;
			font-size 	: 40px;
			line-height : 45px;
		}



		@media (max-width: 600px) {

			section.content {
				height: 569px;
			}
			section.content.fullscreen {
				top: -90px;
				height: 704px;
			}

			footer {
				font-size: 32px;
			}
		}

		@media (min-width: 601px) {

			section.content {
				height: 249px;
			}
			section.content.fullscreen {
				top: -90px;
				height: 384px;
			}
		}


		.symbol {
			visibility 	: hidden;
			margin-bottom: -5px;
			margin-top: 2px;
			width: 50px;
			height: 50px;
		}

		.temperature {
			font-size: 32px;
			color: rgb(255, 33, 00);

		}

		.windSpeed {
			color: rgb(00, 33, 255);
			font-weight: 600;
		}


		.precipitation {
			color: rgb(99, 99, 99);
		}
		

		#temperature {
			color: #fff;
		}

	</style>

</head>
<body>
	<?php

		// Mustache templates
		include './templates.html';
	?>

	<header id="header">
		<!-- The centered div ("title") needs to be after the floats, in the HTML -->
		<div id="clock" class="clock"></div>
		<div id="weather" class="weather"><img id="symbol" class="symbol" src="assets/img/sym/svg/01d.svg" width="50" height="50"><span id="temperature" class="temperature"></span></div>
		<div class="title">
			<img src="assets/img/osloby-hvit.png" height="75">
		</div>
	</header>

	<section id="content" class="content">
		<iframe src="" class="contentframe"></iframe>
	</section>
	<footer id="footer">
		Les mer på osloby.no/oya
	</footer>




	<script type="text/javascript">

		function onPlaylistLoaded(data) {
			console.log("Playlist loaded.");
			// console.log("received playlist data: ", data);
		}

		function onLoadError(error) {
			console.error("Playlist LoadError: " + error, error);
		}

		function getJSON (list, callback, onerror) {
			var
				result = null,
				list = list || "assets/php/data/playlist.json";

			// console.log("Retrieving playlist");

			pi.require("xhr", false, false, function() {
				// console.log("Sending xhr");
				result = pi.xhr.get(list, callback, onerror);
			});
		}


		document.addEventListener("DOMContentLoaded", function(){
			var
				i = 0;

			getJSON(null, onPlaylistLoaded);

		});

	</script>



	<script type="text/javascript">


	document.addEventListener("DOMContentLoaded", function () {

		var
			forecast 		= <?php print(json_encode($weather)); ?>,
			section 		= document.getElementsByClassName("content")[0],
			currentTime = null,
			clock 			= document.getElementById("clock"),
			weather 		= document.getElementById("weather"),
			temperature = document.getElementById("temperature");


		/**
		 * Recursive function to normalize data, by moving attributes from @attributes to parent object.
		 * 
		 * @param  {Object} 	obj 	The object to normalize
		 * 
		 * @return {Object}     		The normalized object
		 */
		function normalize(obj) {
			var
				result = {};

			if (!obj) return false;

			for (var i in obj) {
				if (i == "@attributes") {

					// copy attributes to parent object
					for (var j in obj[i]) {
						result[j] = obj[i][j];
					}
				}
				else {
					result[i] = normalize(obj[i]);
				}
			}
			return result;
		}


		function orderData(data) {
			var
				data 		= data || false,
				output 	= {};

			if (!(pi.isArray(data) || pi.isObject(data))) {
				console.error("in orderData(), parameter 'data' is not array or object.");
				return false;
			}

			return normalize(data);
		}


		function updateTime() {
			var
				result 	= "",
				now 		= new Date();


	    /**
	     * pi.strPad():  JS version of PHP's str_pad()
	     * 
	     * @param  {string} str       The string to pad
	     * @param  {int}    padto     Desired length
	     * @param  {string} padstr    Pad string
	     * @param  {bool}   padleft   Flag to pad string from the left (default is pad from right)
	     * 
	     * @return {string}     The padded string
	     */
				result = pi.strPad(now.getHours(), 2, "0", true) + ":" + pi.strPad(now.getMinutes(), 2, "0", true);
				if (result != currentTime) {
					// console.log("Updating clock: " + result);
					clock.innerHTML = result;
					currentTime = result;
				}
		}


		function formatTime(when) {
		    var 
			    date = when || new Date();
		    return pi.strPad(date.getUTCHours(), 2, "0", true);
		}


		function updateWeather() {
			var
				result 	= "",
				element = null,
				now 		= new Date(),
				template = document.getElementById("weather-template").innerHTML,
				tempSet	= false,
				chunk 	= null,
				format  = function (data) {
					var
						today		= Date.now(),
						result 	= {},
						data 		= data || null;

					if (data) {
						result.from	= new Date(data['from']);
						result.to		= new Date(data['to']);
						result.fromStr 	= formatTime(result.from);
						result.toStr 		= formatTime(result.to);
						// console.log(data['from'] + " becomes : " + result.from);

						result.period 	= typeof data['period'] != "undefined" ? parseInt(data['period'], 10) : null;
						result.symbol 	= data['symbol']['var'];

						result.windSpeed 			= parseFloat(data['windSpeed']['mps'], 10);
						result.temperature 		= parseFloat(data['temperature']['value'], 10);
						result.precipitation 	= parseFloat(data['precipitation']['value'], 10);
						result.windDirection 	= parseFloat(data['windDirection']['deg'], 10);
					}
					return result;
				};

			// alert(forecast);
			// section.innerHTML = JSON.stringify(forecast);

			if (!forecast) {
				console.error("No forecast!");
			}


			// alert(forecast.forecast);
			for (var i = 0; i < forecast.forecast.length; i++) {
				chunk 	= format(orderData(forecast.forecast[i]));
				element = document.createElement("li");
				element.className = "period";

				if (!tempSet) {
					// console.log("Updating temperature: " + chunk.temperature + "&deg;", chunk);

					temperature.innerHTML = chunk.temperature + "&deg;";
					symbol.style.visibility = "visible";
					symbol.src = "assets/img/sym/svg/" + chunk.symbol + ".svg";
					tempSet = true;
				}

				// element.innerHTML = Mustache.render(template, chunk);
				// section.appendChild(element);
			}

			// console.log("Created " + i + " elements.");
			return i;
			// result = pi.strPad(now.getUTCHours(), 2, "0", true) + ":" + pi.strPad(now.getUTCMinutes(), 2, "0", true);

		}

		function toggleFullscreen() {
			var
				content = document.getElementById("content"),
				header 	= document.getElementById("header"),
				footer 	= document.getElementById("footer");

			if (header.classList.contains("fullscreen")) {
				header.classList.remove("fullscreen");
				content.classList.remove("fullscreen");
			}
			else {
				header.classList.add("fullscreen");
				content.classList.add("fullscreen");
			}
		}



		// initial update from data
		updateTime();
		updateWeather();


	});

	</script>
</body>
</html>