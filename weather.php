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
	<script>

		/**
		 * Window error handler. Suppresses error messages, and re-routes to server.
		 * Aborts server reporting if localStorage is not available for counting number of errors.
		 * Stops reporting after counting 1000 errors.
		 * 
		 * NB! Should always be included directly after opening <head> tag
		 * 
		 * @param  {str} 	msg  	Error message
		 * @param  {str} 	url  	Url of the file
		 * @param  {int} 	line 	The line where the error occurred
		 * 
		 * @return {true} 			Always returns true, in order to suppress any visible error messages
		 */
		window.onerror = function(msg, url, line) {

			// trap any further errors
	    try {
		    var
		    	errorCount = null,

		    	/**
		    	 * Property	Description
						appCodeName	Returns the code name of the browser
						appName	Returns the name of the browser
						appVersion	Returns the version information of the browser
						cookieEnabled	Determines whether cookies are enabled in the browser
						geolocation	Returns a Geolocation object that can be used to locate the user's position
						language	Returns the language of the browser
						onLine	Determines whether the browser is online
						platform	Returns for which platform the browser is compiled
						product	Returns the engine name of the browser
						userAgent	Returns the user-agent header sent by the browser to the server
		    	 */

		    	data 	= { 
		    						navigator : { 
		    							userAgent 		: navigator.userAgent,
		    							appCodeName 	: navigator.appCodeName,
		    							appVersion 		: navigator.appVersion,
		    							cookieEnabled : navigator.cookieEnabled,
		    							language 			: navigator.language,
		    							product 			: navigator.product,
		    							platform 			: navigator.platform,
		    						}
		    					},

		    	xhr 	= new XMLHttpRequest(); // var


				if (typeof(Storage) != "undefined") {

					// returns null if non-existent
					errorCount = localStorage.getItem("errorCount");
					if (errorCount === null) {
						errorCount = 1;
					}
					else {

						// convert to integer, and increment
						errorCount = parseInt(errorCount, 10);
						errorCount++;
					}

					// update localStorage with current value
					localStorage.setItem("errorCount", errorCount);
				}

				if (errorCount === null) {

					console.error("No localStorage!");
					console.error("msg : " + msg);
					console.error("url : " + url);
					console.error("line : " + line);

					// don't send to server unless we know we are reliably counting errors locally
					return true;
				}

				// collect relevant data
		    data.msg 	= msg;
		    data.url 	= url;
		    data.line = line;
		    data.location = window.location;

				console.error("Sending to server: " + msg);

		    // send it to the errorlogger
		    // we don't care if it gets there, that's not our problem
		    xhr.open('GET', "assets/php/errorlog.php?error=" + JSON.stringify(data), true);
				xhr.send();
	    }
	    catch(e) {

        // suppress any additional error
        // 
        // aaand maybe we should do a refresh...
        // that would have to be only if we know for certain that we can 
        // write to localStorage, otherwise infinite error loops would be possible
				console.error("error in window.onerror: " + e);
	    }

	    // and damn the torpedoes
	    return true;
		};
	</script>

	<meta charset="utf-8">
	<title>Øyafestivalen 2016</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="/assets/font/clan.css">
	<script type="text/javascript" src="assets/js/mustache.js"></script>
	<script type="text/javascript" src="assets/js/pi.core.js"></script>
	<style type="text/css">

		html, body {
			color 			: #000;
			margin 			: 0;
			padding 		: 0;
			font-family : 'ClanOT', sans-serif;


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

      /* don't display caret, use normal mouse cursor when hovering over text */
      cursor: pointer;
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

		header {
			font-weight : 700;
			height 			: 90px;
			color 			: #fff;
			min-width 	: 100%;
			background 	: rgb(38,188,244);
			background 	: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(38,188,244,1)), color-stop(50%,rgba(38,188,244,1)), color-stop(51%,rgba(0,173,242,1)), color-stop(100%,rgba(0,173,242,1)));
			background  : -webkit-linear-gradient(top,  rgba(38,188,244,1) 0%,rgba(38,188,244,1) 50%,rgba(0,173,242,1) 51%,rgba(0,173,242,1) 100%);
			background  : linear-gradient(to bottom,  rgba(38,188,244,1) 0%,rgba(38,188,244,1) 50%,rgba(0,173,242,1) 51%,rgba(0,173,242,1) 100%);
		}


		ul, li {
			list-style-type: none;
			list-style: none;
		}

		li.period {
			-webkit-animation: period .4s ;
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


		.clock {
			margin-top 	: 50px;
			margin-left : 10px;
			font-size 	: 40px;
			line-height : 45px;
		}

		header .title {
			padding 		: 8px;
			margin-left : auto;
			margin-right: auto;

			text-align 	: center;

			background-repeat 	: no-repeat;
			background-position : center center;
		}


		section.content {
			max-height: 569px;
			overflow 	: hidden;
		}

		footer {
			/*padding-left 		: 10px;*/
			padding 				: 8px 10px 4px;
			text-align 			: center;
			color 					: #fff;
			height 					: 45px;
			font-size 			: 36px;
			line-height 		: 38px;
			font-weight 		: 500;
			background-color: #000;
		}


		@media (max-width: 600px) {

			section.content {
				height: 633px;
			}

			footer {
				font-size: 32px;
			}
		}

		@media (min-width: 602px) {

			section.content {
				height: 249px;
			}
		}


		.symbol {
			visibility 	: hidden;
			margin 			:0px;
			margin-bottom: -12px;
			margin-top: 2px;
			width: 50px;
			height: 50px;
			padding 		: 0;
		}

		.temperature {
			font-size: 32px;
			font-weight: 500;
			color: rgb(255, 33, 00);
		}

		.windSpeed {
			color: rgb(00, 33, 255);
			font-weight: 500;
			text-align: right;
			min-width: 80px;
		}


		.precipitation {
			color: rgb(99, 99, 99);
		}
		

		#temperature {
			color: #fff;
		}



		div.weather-line {
			margin-left: 3em;
		}

	</style>

</head>
<body>
	<section class="content">
	</section>

	<script id="weather-template" type="text/template">
		<div class="weather-line"><strong>{{fromStr}} - {{toStr}}</strong>
			<img src="assets/img/sym/svg/{{symbol}}.svg" width="45" height="45" style="margin: -2px">
			<span class="temperature">{{temperature}}&deg;</span>
			<span class="precipitation">{{precipitation}} mm</span>
			<span class="windSpeed">{{windSpeed}} m/s</span>
		</div>
	</script>

	<script type="text/javascript">


	document.addEventListener("DOMContentLoaded", function () {

		var
			forecast 		= <?php print(json_encode($weather)); ?>,
			section 		= document.getElementsByClassName("content")[0],
			currentTime = null,
			weather 		= document.getElementById("weather");


		/**
		 * Recursive function to normalize data, by moving attributes from @attributes to parent object.
		 * BTW, fuck you very much, people who invented XML. You know who you are.
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

			if (!forecast) {
				console.error("No forecast!");
			}


			for (var i = 0; i < forecast.forecast.length; i++) {
				chunk 	= format(orderData(forecast.forecast[i]));
				element = document.createElement("li");
				element.className = "period";


				element.innerHTML = Mustache.render(template, chunk);
				section.appendChild(element);
			}

			return i;
		}


		// initial update from data
		updateWeather();

		// update at intervals
		setInterval(updateWeather, 60000);

	});

	</script>
</body>
</html>