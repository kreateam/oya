<?php

	session_start();

	error_reporting(E_ALL);

	// require_once("../assets/php/weather.php");
	// $weather = getWeather();

	require_once("../assets/php/program.php");
	$program = getProgram();

?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Øyafestivalen 2015</title>

	<!-- redirect requests, one dir up -->
	<base href="../">

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="/html5/assets/font/clan.css">
	<link rel="stylesheet" type="text/css" href="assets/css/contextmenu.css" media="all" />

	<script type="text/javascript" src="assets/js/contextmenu.js"></script>
	<script type="text/javascript" src="assets/js/mustache.js"></script>
	<script type="text/javascript" src="assets/js/pi.core.js"></script>

	<style type="text/css">
			@-webkit-keyframes zoomInDown {
		  0% {
		    opacity: 0;
		    -webkit-transform: scale3d(.1, .1, .1) translate3d(0, -1000px, 0);
		    transform: scale3d(.1, .1, .1) translate3d(0, -1000px, 0);
		    -webkit-animation-timing-function: cubic-bezier(0.550, 0.055, 0.675, 0.190);
		    animation-timing-function: cubic-bezier(0.550, 0.055, 0.675, 0.190);
		  }

		  60% {
		    opacity: 1;
		    -webkit-transform: scale3d(.475, .475, .475) translate3d(0, 60px, 0);
		    transform: scale3d(.475, .475, .475) translate3d(0, 60px, 0);
		    -webkit-animation-timing-function: cubic-bezier(0.190, 1.000, 0.220, 1.000);
		    animation-timing-function: cubic-bezier(0.190, 1.000, 0.220, 1.000);
		  }
		}

		@keyframes zoomInDown {
		  0% {
		    opacity: 0;
		    -webkit-transform: scale3d(.1, .1, .1) translate3d(0, -1000px, 0);
		    transform: scale3d(.1, .1, .1) translate3d(0, -1000px, 0);
		    -webkit-animation-timing-function: cubic-bezier(0.550, 0.055, 0.675, 0.190);
		    animation-timing-function: cubic-bezier(0.550, 0.055, 0.675, 0.190);
		  }

		  60% {
		    opacity: 1;
		    -webkit-transform: scale3d(.475, .475, .475) translate3d(0, 60px, 0);
		    transform: scale3d(.475, .475, .475) translate3d(0, 60px, 0);
		    -webkit-animation-timing-function: cubic-bezier(0.190, 1.000, 0.220, 1.000);
		    animation-timing-function: cubic-bezier(0.190, 1.000, 0.220, 1.000);
		  }
		}

		.zoomInDown {
		  -webkit-animation-name: zoomInDown;
		  				animation-name: zoomInDown;

		 	-webkit-animation-duration: .7s;
		  			 	animation-duration: .7s;

		  -webkit-animation-fill-mode: both;
		  				animation-fill-mode: both;
  		}

	</style>

	<style type="text/css">

		::-webkit-scrollbar {
		    width: 12px;
		}
		 
		::-webkit-scrollbar-track {
		    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3); 
		    border-radius: 10px;
		}
		 
		::-webkit-scrollbar-thumb {
		    border-radius: 10px;
		    -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.5); 
		}

		html, body {
			color: #000;
			background: rgb(38,188,244);
			margin: 0;
			padding: 0;

			font-family: 'ClanOT', sans-serif;

			/* our canonical em */
	  	font-size: 8px;
			min-height: 100%;
			min-width: 100%;
			height: 100%;

     	/* Make text non-selectable */
      -webkit-touch-callout: none;
        -webkit-user-select: none;
           -moz-user-select: none;
            -ms-user-select: none;
                user-select: none;

      cursor: default;

			-webkit-box-sizing: border-box;
			 	 -moz-box-sizing: border-box;
							box-sizing: border-box;
		}

		h1, h2, h3, h4, h5, h6 {
			font-weight: 300;
			text-align: center;
		}	

		@media (max-width: 1280px) {
		  html, body {
		  	font-size: 10px;
		  }
		}

		@media (max-width: 1920px) {
		  html, body {
		  	font-size: 12px;
		  }
		}

		@media (min-width: 1921px) {
		  html, body {
		  	font-size: 16px;
		  }
		}


		ul, li {
			list-style: none;
			list-style-type: none;
		}

		li {
			padding-left: 4px;
			padding-right: 4px;
			text-align: right;
		}


		iframe {
			background-color: #fff;
			border: solid 16px rgba(0, 0, 0, 0.3);
			/*background: none repeat scroll 0% 0% transparent; */
			/*width: 100%;*/

			-webkit-background-clip: padding-box; /* for Safari */
	    				background-clip: padding-box; /* for IE9+, Firefox 4+, Opera, Chrome */		
    }

		header {
			margin-bottom: 1em;
		}

		footer {
			position: fixed;
			bottom: 0;
			margin-right: auto;
			margin-left: auto;
			width: 66%;
			height: 45px;
			font-weight: 400;
			font-size: 1.5em;
			background-color: #272822;
		}

		.content {
			width: 100%;
			height: 100%;
		}

		.item {
			margin: 2px 0.5em;
			padding-top: 2px;
			text-align: left;
			background-color: rgba(39, 40, 34, 0.6);
		}

		.item::before {
			content: "+ ";
		}


		.hashtag {
			font-weight: 600;
			color: rgba(38,188,244,1);
		}

		.screen {
			display: block;
			opacity: 0;
			-webkit-transform: scale(0.5, 0.5);
							transform: scale(0.5, 0.5);

			-webkit-transition: opacity .4s ease-out, -webkit-transform .2s ease-out; /* Safari */
			-webkit-transition: opacity .4s ease-out, transform .2s ease-out;
							transition: opacity .4s ease-out, transform .2s ease-out;

		}

		#screen1 {
			position: fixed;
			left: 0px;
			bottom: 0px;
			-webkit-transform-origin : bottom left;
					-ms-transform-origin : bottom left;
							transform-origin : bottom left;
		}

		#screen2 {
			position: fixed;
			right: 0px;
			bottom: 0px;
			-webkit-transform-origin : bottom right;
					-ms-transform-origin : bottom right;
							transform-origin : bottom right;
		}

		.wrapper {
			background-color: #fff;
			position: relative;
			margin : 0;
			padding: 0;
			width: 100%;
			height: 100%;
			min-height: 100%;
		}

		.left {
			float: left;
			color: #fff;
			background-color: #C21E29;
			padding-right: 8px;
			text-align: right;
			width: 17%;
			height: 100%;
			min-height: 100%;
		}

		.right {
			float: right;
			width: 17%;
			height: 100%;
			min-height: 100%;

			padding-left: 8px;

			color: #fff;
			background-color: #0099D6;

			text-align: right;
		}

		.middle {
			color: #fff;
			margin: 0;
			padding: 0;
			font-size: 2em;
			font-weight: 300;
			position: relative;
			background-color: rgba(39, 40, 34, 0.8);
			width: 66%;
			min-height: 100%;
			height: 100%;
			text-align: center;
			margin-left: auto;
			margin-right: auto;
		}
		

		.right ::after {
			clear: both;
		}

		.section-title {
			font-weight: 900;

		}

		.duration {
			float: right;
		}

		.coming-up {
			margin-left: 40px;
			text-align: left;
			margin-bottom: 40px;
			font-weight: 300;
		}

		.fat {
			font-weight: 900;
		}

		.day {
			display: none;
			font-weight: 300;
		}

		.time {
			font-weight: 300;
			margin-right: 4px;
		}

		.artist {
			font-weight: 900;
		}


		#scene1-hours {
			font-weight: 900;
		}

		#scene1-minutes {
			font-weight: 900;
		}

		#scene2-hours {
			font-weight: 900;
		}

		#scene2-minutes {
			font-weight: 900;
		}


		#demo {
			text-align: center;
			
		}

	</style>

</head>
<script type="text/javascript">

	/**
	 * Handle keyboard commands
	 * 
	 * @param  {[type]} e [description]
	 */
	function keypressed(e) {
		var
			screens = document.getElementsByClassName("screen"),
			keynum, key;

    if (window.event) {
    	keynum = e.keyCode;
    }
    else {
      if (e.which) {
	  		keynum = e.which;
      }
    }

    key = String.fromCharCode(keynum);

    if (key == "1") {
			if (screens[0].style.opacity == 0) {
				screens[0].style.opacity = 1;
			}
			else {
				screens[0].style.opacity = 0;
			}
    }
    if (key == "2") {
			if (screens[1].style.opacity == 0) {
				screens[1].style.opacity = 1;
			}
			else {
				screens[1].style.opacity = 0;
			}
    }
    if (key == "Z") {
			if (screens[1].style.transform == "scale(1, 1)") {
				screens[0].style.transform = "scale(0.5, 0.5)";
				screens[1].style.transform = "scale(0.5, 0.5)";
			}
			else {
				console.log("transform : " + screens[1].style.transform);
				screens[0].style.transform = "scale(1, 1)";
				screens[1].style.transform = "scale(1, 1)";
			}
			// if (screens[1].style.webkitTransform == "scale(1, 1)") {
			// 	screens[0].style.webkitTransform = "scale(0.5, 0.5)";
			// 	screens[1].style.webkitTransform = "scale(0.5, 0.5)";
			// }
			// else {
			// 	console.log("webkitTransform : " + screens[1].style.webkitTransform);
			// 	screens[0].style.webkitTransform = "scale(1, 1)";
			// 	screens[1].style.webkitTransform = "scale(1, 1)";
			// }

    }

    if (key != "M") {
    	return true;
    }

		if (screens && screens.length) {
			for (var i = 0; i < screens.length; i++) {
				if (screens[i].style.opacity == 0) {
					screens[i].style.opacity = 1;
				}
				else {
					screens[i].style.opacity = 0;
				}
			}
		}
	}

</script>
<body onkeydown="return keypressed(event);">
	<script type="text/javascript">

		/**
		 * callback for iframe.load
		 * 
		 * @param  {iframe} elem 
		 * @return {void}
		 */
		function iframeloaded(elem) {
			console.log("iframe loaded : " + elem.id);
			elem.style.opacity = 1;
		}

	</script>

	<script id="program-template" type="text/template">
		<span class="artist">{{artist}}</span>
		<span class="day">{{day}}</span>
		<span class="time">{{time}}</span>
	</script>


	<div class="wrapper">
		<div class="left">
				<h4>Amfiet</h4>
				<div class="coming-up">
					<h1>Next</h1>
					<h2 class="artist">Razika</h2>
					<span><strong>in</strong> 
						<br>&nbsp;&nbsp;<span class="fat">1</span> month, 
						<br>&nbsp;&nbsp;&nbsp;&nbsp;<span class="fat">9</span> days, 
					</span>
					<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="scene1-hours">4</span> hours, 
					<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="scene1-minutes">5</span> minutes.
				</div>

				<ul id="program1">
					
				</ul>
		</div>
		<div class="right">
				<h4>Vindfruen</h4>
				<div class="coming-up">
					<h1>Coming Up</h1>
					<h2 class="artist">The Switch</h2>
					<span><strong>in</strong>
						<br>&nbsp;&nbsp;<span class="fat">1</span> month, 
						<br>&nbsp;&nbsp;&nbsp;&nbsp;<span class="fat">9</span> days, 
					</span>
					<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="scene2-hours">4</span> hours, 
					<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="scene2-minutes">50</span> minutes.
				</div>
				<ul id="program2">
					
				</ul>
		</div>
		<div class="middle">
			<section class="content">
				<header>
					Play Queue
				</header>

				<section id="current">
					<div class="section-title">Now playing</div>
		
					<ul>
						<li class="item">Weather - today<span class="duration">0:42</span></li>
					</ul>
					
				</section>
				<section id="queue">
					<div class="section-title">Queued</div>
					<ul>
						<li class="item">Food report #32 <span class="duration">0:30</span></li>
						<li class="item">Instagram <span class="hashtag">#osloby #latest</span><span class="duration">3:00</span></li>
					</ul>
				</section>

				<section id="next-tracks">
					<div class="section-title">Next Items</div>
					<ul>
						<li class="item">Announcement : &lt;TBA&gt;<span class="duration">0:30</span></li>
						<li class="item">Tweets <span class="hashtag">#osloby</span><span class="duration">1:30</span></li>
						<li class="item">Tweets <span class="hashtag">#oya</span><span class="duration">1:30</span></li>
						<li class="item">Coming up...<span class="duration">2:30</span></li>
					</ul>
				</section>
				<section id="demo">
					
				</section>
			</section>
			<footer>
				<span>+</span>
				<input type="file" name="image-upload" id="image-upload" accept="image/*"/>
			</footer>
		</div>

	</div>

	<iframe id="screen1" class="screen" scrolling="no" src="weather.php" width="512" height="704" onload="iframeloaded(this)"></iframe>
	<iframe id="screen2" class="screen" scrolling="no" src="weather.php" width="672" height="384" onload="iframeloaded(this)"></iframe>
	
	<script type="text/javascript">

		
	</script>

	<script type="text/javascript">

		/** @todo should maybe rewrite the invokation some.. */
		document.querySelector('#image-upload').addEventListener('change', function(e) {
			  var
			  	file 	= this.files[0],
			  	data 	= new FormData(),
			  	xhr 	= new XMLHttpRequest(),

			  	onprogress = function(e) {
				    if (e.lengthComputable) {
				      var
				      	percentComplete = (e.loaded / e.total) * 100;

				      console.log(percentComplete + '% uploaded');
				    }
				  },

				  onload = function() {
				    if (this.status == 200) {
				    	var
				    		section = document.getElementById("demo"),
					      image 	= document.createElement('img'),
					      resp 		= JSON.parse(this.response);

				      image.src = resp.dataUri;
				      image.className = "zoomInDown";
				      image.style.display = "inline-block";
				      if (section.firstChild) {
				      	console.log("insertBefore");
					      section.insertBefore(image, section.firstChild);
				      }
				      else {
				      	console.log("appendChild");
				      	section.appendChild(image);
				      }
				    }
				    else {
				    	// throws to window.onerror, where we trap and redirect to server
				    	throw "Xhr error: " + this.status;
				    }
			    };


			   // populate formdata
			  data.append("image-upload", file);
			  data.append("username", "<?php echo $_SESSION['username'];?>");
			  data.append("postId", pi.uuid());

			  xhr.upload.onprogress = onprogress;
			  xhr.onload = onload;

			  xhr.open('POST', 'admin/image-upload.php', true);
			  xhr.send(data);

			}, false);
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
		 * 
		 * @todo Rewrite to avoid infinite recursion in special cases
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
				result 		= "",
				element 	= null,
				concerts 	= [],
				now 			= new Date(),
				template 	= document.getElementById("program-template").innerHTML,
				tempSet		= false,
				chunk 		= null,
				program1 	= document.getElementById("program1"),
				program2 	= document.getElementById("program2"),

				getDate  	= function (dateStr) {
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
				}; // var



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


			concerts.sort(function(a, b) {
			    if(a.sort < b.sort) return -1;
			    if(a.sort > b.sort) return 1;
			    return 0;
			});


			for (var i = 0; i < concerts.length; i++) {
				element = document.createElement("li");
				element.innerHTML = Mustache.render(template, concerts[i]);
				if (concerts[i]["scene"] == scene1) {
					console.log(scene1 + " : " + concerts[i]["artist"]);
					program1.appendChild(element);
				}
				else if (concerts[i]["scene"] == scene2) {
					console.log(scene2 + " : " + concerts[i]["artist"]);
					program2.appendChild(element);
				}
				else {
					console.error("No scene matches '" + concerts[i]["scene"] + "'");
					// nada
				}
			}

			return i;
			// result = pi.strPad(now.getUTCHours(), 2, "0", true) + ":" + pi.strPad(now.getUTCMinutes(), 2, "0", true);

		}


		updateProgram();
		// setInterval(updateProgram, 60000);

		// updateProgram();

	});

	</script>
</body>
</html>