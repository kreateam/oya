<?php
	
	/**
	 * Player for big screens - Øyafestivalen 2016
	 */


	session_start();

	// error_reporting(E_ALL);

	// support functions for weather
	require_once("./assets/php/weather.php");

	// Mustache engine for PHP
	require_once("./../assets/php/mustache/Engine.php");
	$weather = getWeather();

?>
<!doctype html>
<html>
<head>
<!--  <script type="text/javascript" src="assets/js/errorhandler.js"></script>
 -->	

 	<meta charset="utf-8">

	<title>Øyafestivalen 2016</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<link rel="stylesheet" type="text/css" href="/assets/font/clan.css">
	<link rel="stylesheet" type="text/css" href="/html5/assets/font/gtwalsheim.css">

	<script type="text/javascript" src="assets/js/mustache.js"></script>
	<script type="text/javascript" src="assets/js/pi.core.js"></script>
	<script type="text/javascript" src="assets/js/pi.xhr.js"></script>
	<style type="text/css">

		* {
			margin  : 0;
			padding : 0;
      cursor: none;
			border: none;
		}

		html, body {
			color 			: #000;
			font-family : 'ClanOT', sans-serif;
			background: transparent;

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

			/*background-color: rgba(255,5,45,0.7);*/
			border: none;

			-webkit-transition: all .4s ease-out;
							transition: all .4s ease-out;
		}


		/* hide until loaded, ready and needed */
		#nextframe {
			display: none;
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
			padding 		: 18px;
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
			padding 				: 6px 10px 4px;
			text-align 			: center;
			color 					: #fff;
			height 					: 45px;
			width 					: 100%;
			font-size 			: 32px;
			line-height 		: 34px;
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

			footer {
				padding-top: 4px;
				height: 60px;
			}
			section.content {
				height: 618px;
			}

			section.content.fullscreen {
				top: -90px;
				height: 768px;
			}

			footer {
				font-size: 28px;
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


		#debug {
			position: absolute;
			display: block;
			bottom: 0;
			overflow-y: scroll;
			cursor: none;
			pointer-events: none;
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
		<div id="weather" class="weather"><img id="symbol" class="symbol" src="" width="50" height="50"><span id="temperature" class="temperature"></span></div>
		<div class="title">
			<img src="/cdn/logo/aftenposten-white.svg" height="50">
		</div>
	</header>

	<section id="content" class="content">
		<iframe src="" id="contentframe" class="contentframe"></iframe>
		<iframe src="" id="nextframe" class="nextframe"></iframe>
	</section>
	<footer id="footer">
		Les mer på ap.no/oya
	</footer>
	<section id="debug">

	</section>
<script type="text/javascript">

	function reloadPlayer () {
		console.info("reloadPlayer");
		// document.body.innerHTML = document.body.innerHTML;
		location.reload();
	}


	function resetStatusText() {
		var
			statusbar = document.getElementById("footer");
			statusbar.textContent = "Les mer på ap.no/oya";
	}

	function setStatusText(txt) {
		var
			statusbar = document.getElementById("footer");

		/** @todo Maybe check that footer is showing, and maybe scroll text that is too long */
		if (typeof txt == "string") {
			// statusbar.textContent = txt;
		}
	}


	function enterFullscreen() {
		var
			container = document.getElementById("content"),
			header	= document.getElementById("header"),
			footer	= document.getElementById("footer");

		console.info("entering fullscreen");
		if (window.player.fullscreen === true) {
			console.info("aborting");		
			// return false;
		}

		container.classList.add("fullscreen");
		header.classList.add("fullscreen");
		footer.classList.add("fullscreen");
		window.player.fullscreen = true;
	}

	function exitFullscreen() {
		var
			container = document.getElementById("content"),
			header	= document.getElementById("header"),
			footer	= document.getElementById("footer");

		if (window.player.fullscreen === false) {
			return false;
		}
		container.classList.remove("fullscreen");
		header.classList.remove("fullscreen");
		footer.classList.remove("fullscreen");
		window.player.fullscreen = false;
	}


  function doOnOrientationChange() {
    switch(window.orientation) 
    {
      case -90:
      case 90:
        // console.info('landscape');
        break;
      case 0:
        // console.info('portrait');
        break;
      default:
        // console.info('no orientation : ' + window.orientation);
        break;
    }
  }

  window.addEventListener('orientationchange', doOnOrientationChange);

  // Initial execution if needed
  doOnOrientationChange();



	function loadVideo(id, onready) {
		var
			container = document.getElementById("content"),
			header	= document.getElementById("header"),
			footer	= document.getElementById("footer"),
			video = document.createElement("video"),
			onended = onended || null,
			data = null;

		if (!pi.isNumeric(id)) {
			console.error("param id is not a number");
		}
		else {
			// console.info("playing video");
		}
		if (window.data && window.data.videos && window.data.videos.length) {
			for (var i = 0; i < window.data.videos.length; i++) {
				if (window.data.videos[i].id == id) {
					data = window.data.videos[i];
					break;
				}
			}

			if (data) {
				enterFullscreen();
				console.log("entering fullscreen mode");
				video.src = encodeURIComponent(data.uri);
				video.style.display = "block";
				video.style.background = "transparent";
				video.style.opacity = 0;
				video.style.position = "absolute";
				video.style.top = 0;

				// there should only be 1 video at a time
				video.id = "video";
				video.setAttribute("preload", true);
				// video.setAttribute("autoplay", true);
				video.addEventListener("canplay", onready);
				video.addEventListener("ended", reloadPlayer);
				video.addEventListener("canplaythrough", function (e) {
					video.style.opacity = 1;
					video.play();
				});

				container.appendChild(video);
			}
			else {
				console.error("No data!");
			}
		}

	}


	/**
	 * Load video list
	 */

	document.addEventListener("DOMContentLoaded", function() {
		var
			videos = [],
			width = window.innerWidth,
			height = window.innerHeight,
			url = "assets/php/videolist.php";

			url += "?width=" + width + "&height=" + height;

		// console.info("Getting URL  :" + url);
		pi.xhr.get(url, function (json) {
				var
					data = null;

				try {
					// console.log(json);
					data = JSON.parse(json);
					if (data && data['videos'] && data['videos'].length) {
						if (!window.data) {
							window.data = {};
						}
						console.info("Loaded " + data['videos'].length + " videos");
						window.data.videos = data.videos;
					}
				}
				catch (e) {
					console.error(e);
				}
		}, console.error);


	});
</script>


<script>


		function getTemplate(name) {
			var
				templates, template,
				name = name || "default";

			if (window.data && window.data.templates && window.data.templates.length) {
				templates = window.data.templates;
				for (var i = 0; i < templates.length; i++) {
					template = templates[i];
					// console.log("iteration : " + i);
					if (template.template && template.template == name || template.filename && template.filename == name) {
						// console.info(template.filename + " matches : " + name);
						// console.info("returning template");
						return template.filecontent;
					}
					else {
					
					}
				}
			}
			return null;
		}



	
	/* playlist and stuff */

	document.addEventListener("DOMContentLoaded", function() {
		
			window.playlist = {

				UPDATE_INTERVAL : 5000,

				_rotated	: 0,
				_owner 		: null,
				_interval : null,
				jsonfile 	: "assets/php/data/playlist.json",
				_loaded 	: false,
				data 			: null,
				_lastrotation : null,
				_nextrotation : null,


				init : function(owner) {

					// console.info("playlist.init()");
					if (owner) {
						this._owner = owner;
					}
					if (this._interval) {
						clearInterval(this._interval);
						this._interval = null;
					}
					this.load()
					this._interval = setInterval(this.update, this.UPDATE_INTERVAL);
				},

				getNext : function() {
					var
						result = null,
						self = playlist;

					if (self && self.data) {
						if (self.data.queue) {
							for(var idx in self.data.queue) {
								result = self.data.queue[idx];
								return result;
							}
						}
						if (self.data.next) {
							for(var idx in self.data.next) {
								result = self.data.next[idx];
								return result;
							}
						}
					}
					return result;
				},

				handleUpdatedPlaylist : function (newdata) {
					var
						newdata = newdata || null,
						self = playlist;

					if (!newdata) {
						console.error("handleUpdatedPlaylist was called wihout a parameter");
						return false;
					}

					self._rotated = newdata.info.rotated;
					if (self._owner && typeof self._owner.onUpdatedPlaylist == "function") {
						console.info("calling onUpdatedPlaylist() from handleUpdatedPlaylist");
						self._owner.onUpdatedPlaylist.call();
					}

				},

				onupdate : function(json) {
					var
						self = playlist,
						data = null;

					try {
						data = JSON.parse(json);

						if (self._rotated && data && data.info && data.info.rotated && data.info.rotated > self._rotated) {

							/**
							 * For now
							 */

							 reloadPlayer();
							 if (window.data && window.data.videos) {
							 		var
							 			videoCount = window.data.videos;
							 		if (window.data.videos && window.data.videos.length) {
										// loadVideo(Math.round(Math.random() * window.data.videos.length));
							 		}
							 }

							console.info("data.info.rotated : " + data.info.rotated + ", self._rotated : " + self._rotated);

							self._data = data;
							self._rotated = data.info.rotated;
							self.handleUpdatedPlaylist(data);
						}
						else {
							self._rotated = data.info.rotated;

							// console.info("first run, or no update");
						}
					}
					catch (e) {
						console.error("error in onupdate : " + e, json);
					}
					// console.info("json : " + json);
				},

				update : function() {
					var
						self = playlist;

					pi.xhr.get(self.jsonfile, self.onupdate, pi.log);
					// console.info("updating playlist..")
				},

				onload : function(json) {
					var 
						self = playlist,
						_data = JSON.parse(json) || null;

					if (_data && _data.status == "ok") {
						if (self._loaded === false) {
							self._loaded = Date.now();
						}
						self.data = _data.playlist;
						if (self._owner && typeof self._owner.onUpdatedPlaylist == "function") {
							self._owner.onUpdatedPlaylist.call(self._owner);
						}

						// console.info("playlist loaded.");
					}
					else {
						console.error("No playlist data");
					}
				},

				load : function(list, callback, onerror) {
					var
						result = null,
						callback = callback || playlist.onload,
						list = list || "assets/php/playlist.php";

					// console.info("loading playlist");
					result = pi.xhr.get(list, callback, onerror);
				}

			}; // playlist




				window.player = {
					_frames 	: document.querySelectorAll("iframe.screen"),
					_playlist : playlist,
					_created 	: Date.now(),
					_started 	: null,
					fullscreen: false,

					init : function() {
						// sends message to iframe windows
						// console.info("player.init()");
						this.sendMessage("ping");
						this._playlist.init(this);
					},
					
					start : function() {
						// console.log("player.start called!");
						if (this._started === null) {
							this.init();
							this._started = Date.now();
							// console.info("_started : " + this._started);
						}
					},

					show : function(screen) {
						var
							contentframe = document.getElementById("contentframe"),
							screen = screen || null;

						if (!screen) {
							console.error("player.show was called without a parameter");
							return false;
						}
						else {
							console.info("self.show was triggered");
						}

						// unwrap extra data in JSON format
						if (screen.data) {
							try {
								chunk = JSON.parse(screen.data);
								// console.log("Successfully parsed JSON");
							}
							catch(e) {
								console.error(e);
							}
							// add each entry to parent object
							for (var key in chunk) {
								screen[key] = chunk[key];
							}
						}

						if (screen.statustext) {
							setStatusText(screen.statustext);
						}

						if (screen.uri) {
							contentframe.src = screen.uri;
							if (screen.fullscreen === true) {
								enterFullscreen();
							}
							else if (player.fullscreen === true) {
								exitFullscreen();
							}
						}
						else {
							if (screen.template) {
								html = getTemplate(screen.template);
								if (!html) {
									console.error("Couldn't find template : " + screen.template);
									return false;
								}


								var
									inner = Mustache.render(html, screen);
								// console.log("setting innerHTML: " + inner );


								contentframe.contentWindow.document.body.innerHTML = inner;

								if (screen.fullscreen === true) {
									enterFullscreen();
								}
								else if (player.fullscreen === true) {
									exitFullscreen();
								}
								console.info("RENDERING TEMPLATE : ");
								
								// contentframe.contentWindow.document.innerHTML = inner;
							}
						}


					},

					preload : function(screen) {
						var
							nextframe = document.getElementById("nextframe"),
							screen = screen || null;

						if (!screen) {
							console.error("player.preload was called without a parameter");
							return false;
						}
						else {
							// console.info("self.show was triggered");
						}

						// unwrap extra data in JSON format
						if (screen.data) {
							try {
								chunk = JSON.parse(screen.data);
								// console.log("Successfully parsed JSON");
							}
							catch(e) {
								console.error(e);
							}
							// add each entry to parent object
							for (var key in chunk) {
								screen[key] = chunk[key];
							}
						}

						if (screen.uri) {
							nextframe.src = screen.uri;
						}
						else {
							if (screen.template) {
								html = getTemplate(screen.template);
								if (!html) {
									console.error("Couldn't find template : " + screen.template);
									return false;
								}

								var
									inner = Mustache.render(html, screen);
								// console.log("setting innerHTML: " + inner );


								nextframe.contentWindow.document.body.innerHTML = inner;

								// console.info("RENDERING TEMPLATE : " + screen.template);
								
								// contentframe.contentWindow.document.innerHTML = inner;
							}
						}


					},

					onPlaylistLoaded : function (e) {
						var
							self = player;

						// console.info("onPlaylistLoaded!");
					},


					onUpdatedPlaylist : function (w) {
						var
							item = null,
							self = player;

						item = self._playlist.data.current;
						if (item) {
							if (window.data && window.data.templates) {
								console.info("templates loaded, calling show()");
								self.show(self._playlist.data.current);
							}
							else {
								console.info("waiting for templates to load");
								pi.on("templates.loaded", self.show(self._playlist.data.current));
							}

						}
						else {
							console.error("no item");
						}
						// console.info("_rotate is NOT set, duration : " + item.duration);

					},


					sendMessage: function(msg, domain) {
						var
							domain = domain || null,
							iframe;

						if (domain === null) {
							var 
								l = window.location;
								domain = l.protocol + "//" + l.host;
						}
						if (this._frames && this._frames.length) {
							for (var i = 0; i < this._frames.length; i++) {
								iframe = this._frames[i];
								if (iframe.contentWindow && typeof iframe.contentWindow.postMessage == "function") {
									// console.debug("sending msg to iframe " + i + " : " + msg + ", domain: " + domain);

									iframe.contentWindow.postMessage(msg, domain);
								}
							}
						}
					},

					onFrameMessage: function(e) {
						// on reply from iframe
						// console.log("iframe says: " + e, e);
					}

			}; // player

		// listen for messages posted to our window
		window.addEventListener('message', player.onFrameMessage, false);

		function onTemplatesLoaded(json) {
			var
				data,
				json = json || null;

			if (json) {
				data = JSON.parse(json) || null;

				if (data && data.files && data.files.length) {
					if (!window.data) {
						window.data = {};
					}
					window.data.templates = data.files;
					pi.events.trigger("templates.loaded");
					player.start();
				}
			}
		};

		pi.xhr.get("templates.php", onTemplatesLoaded);

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
		setInterval(updateTime, 5000);
		updateWeather();


	});

	</script>
</body>
</html>