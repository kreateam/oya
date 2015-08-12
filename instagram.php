<?php
	
	/**
	 * 
	 */


	session_start();

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
	<title>Øya 2015</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="/html5/assets/font/clan.css">
	<script type="text/javascript" src="assets/js/mustache.js"></script>
	<script type="text/javascript" src="assets/js/pi.core.js"></script>
	<script type="text/javascript" src="assets/js/pi.xhr.js"></script>
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

		header .instagram {
			float: right;
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
			max-height: 618px;
			overflow 	: hidden;
		}


		@media (max-width: 600px) {

			section.content {
				height: 618px;
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


	</style>

</head>
<body>
	<section class="content">

	</section>

	<script id="instagram-template" type="text/template">
		<div><strong>{{fromStr}} - {{toStr}}</strong>
			<img src="assets/img/sym/svg/{{symbol}}.svg" width="45" height="45" style="margin: -2px">
			<span class="temperature">{{temperature}}&deg;</span>
			<span class="precipitation">{{precipitation}} mm</span>
			<span class="windSpeed">{{windSpeed}} m/s</span>
		</div>
	</script>

	<script type="text/javascript">


	document.addEventListener("DOMContentLoaded", function () {

		var
			section 		= document.getElementsByClassName("content")[0],
			currentTime = null,
			instagram 		= document.getElementById("instagram");


		function render(data) {
			var
				data = data || null;
			if (data === null) {
				console.error("No param in render()");
				return false;
			}
			console.info("Ready to render: ", data);
		}



		function onLoaded(json) {
			var
				data,
				json = json || null;

			if (json === null) {
				console.error("No param in onLoaded()");
				return false;
			}
			try {
				data = JSON.parse(json);
				console.log("data: " + data, data);
				if (!window.data) {
					window.data = {};
				}
				window.data.instagram = data;
				render(data);
			}
			catch (e) {
				console.error(e);
			}
		}


		function updateInstagram() {
			pi.xhr.get("assets/php/instagram.php", onLoaded, console.error);

			// result = pi.strPad(now.getUTCHours(), 2, "0", true) + ":" + pi.strPad(now.getUTCMinutes(), 2, "0", true);

		}


		// initial update from data
		updateInstagram();

	});

	</script>
</body>
</html>