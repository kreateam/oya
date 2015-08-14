<?php
	
	/**
	 * 
	 */



	session_start();

	// error_reporting(E_ALL);

	require_once("./assets/php/program.php");
	$program = getProgram();

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
		    	data 	= { navigator : { userAgent : navigator.userAgent, platform : navigator.platform }},
		    	xhr 	= new XMLHttpRequest();

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
					console.error ("msg : " + msg);
					console.error ("url : " + url);
					console.error ("line : " + line);
					// don't send to server unless we know we are counting errors locally
					return true;
				}

				// collect relevant data
		    data.msg 	= msg;
		    data.url 	= url;
		    data.line = line;
		    data.location = window.location;

				console.error("Sending to server: " + msg);
		    // send it to the errorlogger
		    xhr.open('GET', "assets/php/errorlog.php?error=" + JSON.stringify(data), true);
				xhr.send();
		    // img.src = "assets/php/errorlog.php?time=" + data.time + "&error=" + JSON.stringify(data);
	    }
	    catch(e) {
				console.error("error in window.onerror: " + e);
        // suppress any additional error
	    }

	    // always
	    return true;
		};
	</script>

	<meta charset="utf-8">
	<title>Øyafestivalen 2015</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="/html5/assets/font/clan.css">
	<script type="text/javascript" src="assets/js/mustache.js"></script>
	<script type="text/javascript" src="assets/js/pi.core.js"></script>
	<style type="text/css">

		html, body {
			color 			: #000;
			margin 			: 0;
			padding 		: 0;
			font-family : 'Clan OT', 'ClanOT', sans-serif;


      -webkit-font-smoothing: antialiased;

      -webkit-box-sizing: border-box;
         -moz-box-sizing: border-box;
              box-sizing: border-box;

     /* Make text non-selectable */
/*      -webkit-touch-callout: none;
        -webkit-user-select: none;
           -moz-user-select: none;
            -ms-user-select: none;
                user-select: none;*/

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
			-webkit-animation: period .4s ease-out;
		}

		header .program {
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
				height: 569px;
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
			margin-bottom: -7px;
			margin-top: 2px;
			width: 50px;
			height: 50px;
			padding 		: 0;
		}

		.temperature {

		}

		


	</style>

</head>
<body>
	<header id="header">
		<!-- The centered div ("title") needs to be after the floats, in the HTML -->
		<div id="clock" class="clock"></div>
		<div id="program" class="program"><img id="symbol" class="symbol" src="assets/img/sym/svg/01d.svg" width="50" height="50"><span id="temperature" class="temperature"></span></div>
		<div class="title">
			<img src="assets/img/osloby-hvit.png" height="75">
		</div>
	</header>

	<section class="content">
		<ul></ul>
		<pre>
			<?php
				// print_r($program);
			?>
		</pre>
		
	</section>
	<footer id="footer">
		Les mer på osloby.no/oya
	</footer>


		<!--  
			{
			  "scene": "amfiet",
			  "time": "14:15",
			  "day": "Onsdag",
			  "date": "2015-08-12T14:15:00.000Z",
			  "sort": "1-14:15",
			  "artist": "Razika"
			}
		  -->

	<script id="program-template" type="text/template">
		<div><strong>{{artist}}</strong>
			<span class="day">{{day}}</span>
			<span class="time">{{time}}&deg;</span>
			<span class="scene">{{scene}}</span>
		</div>
	</script>

	<script type="text/javascript">


	document.addEventListener("DOMContentLoaded", function () {

		var
			scene1 			= "amfiet",
			scene2 			= "vindfruen",
			program 		= <?php print(json_encode($program)); ?>,
			section 		= document.getElementsByClassName("content")[0],
			currentTime = null;


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
					result[i] = obj[i];
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
					console.log("Updating clock: " + result);
					clock.innerHTML = result;
					currentTime = result;
				}
		}


		function formatTime(when) {
		    var 
			    date = when || new Date();
		    return pi.strPad(date.getUTCHours(), 2, "0", true);
		}


		function updateProgram() {
			var
				result 	= "",
				element = null,
				concerts= [],
				now 		= new Date(),
				template = document.getElementById("program-template").innerHTML,
				tempSet	= false,
				chunk 	= null,
				getDate = function (dateStr) {
					var
						tmp, time,
						day, hour, min = 0;

					tmp = dateStr.split("-");
					day = tmp[0];
					time = tmp[1].split(":");

					console.log("time : " + time);
					// year, month, day, hours, minutes, seconds, milliseconds);
					return new Date(2015, 7, 11 + parseInt(day, 10), 2+parseInt(time[0], 10), parseInt(time[1], 10), 0, 0);

				},
				format  = function (data) {
					var
						today		= Date.now(),
						arr 		= [],
						result 	= {},
						data 		= data || null;

					if (data) {
						arr = data["td"];
						console.log("arr : " + arr);
						result["scene"] = arr[3].trim().toLowerCase();
						result["time"] = arr[1];

						if ([scene1, scene2].indexOf(result["scene"]) > -1) {
							var
								sort = arr[4];
							result["day"] 	= sort["span"][0];
							result["date"] 	= sort["span"][1];
							result["sort"] 	= sort["@attributes"]["data-sort-value"];
							result["date"]  = getDate(result["sort"]);

							result["artist"] = arr[2]["a"];
							// console.log("Scene: " + result["scene"] + ", index: " + ["sirkus, amfiet"].indexOf(result["scene"]));
						}
						else {
							// skip the scenes we are not interested in
							return false;
						}
					}
					return result;
				};


			if (program && program.program && program.program.tbody && program.program.tbody.tr) {
				var
					tr = program.program.tbody.tr;

				for (var i = 0; i < tr.length; i++) {
					// console.log("Chunk no. " + i);
					chunk = format(orderData(tr[i]));
					if (!chunk) {
						continue;
					}
					else {
						concerts.push(chunk);
					}
				}
			}
			else {
				console.error("No program lines found!");
			}


				concerts.sort(function(a, b){
				    if(a.sort < b.sort) return -1;
				    if(a.sort > b.sort) return 1;
				    return 0;
				});


				for (var i = 0; i < concerts.length; i++) {
					element = document.createElement("div");
					element.innerHTML = JSON.stringify(concerts[i]);
					section.appendChild(element);
				}

			return i;
			// result = pi.strPad(now.getUTCHours(), 2, "0", true) + ":" + pi.strPad(now.getUTCMinutes(), 2, "0", true);

		}


		updateTime();
		updateProgram();
		setInterval(updateTime, 5000);
		// setInterval(updateProgram, 60000);

	});

	</script>
</body>
</html>