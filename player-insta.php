<?php
	
	/**
	 * Player for big screens - Øyafestivalen 2016
	 */


	session_start();

	// error_reporting(E_ALL);

	// support functions for weather
	require_once("./assets/php/weather.php");
	$weather = getWeather();
	require_once("./assets/php/weather.nowcast.php");
	$nowcast = getNowcast();

	// Mustache engine for PHP
	require_once("./../assets/php/mustache/Engine.php");

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
	<link rel="stylesheet" type="text/css" href="/assets/font/publico.css">
	<!-- <link rel="stylesheet" type="text/css" href="/html5/assets/font/gtwalsheim.css"> -->

	<script type="text/javascript" src="assets/js/mustache.js"></script>
	<script type="text/javascript" src="assets/js/pi.core.js"></script>
	<script type="text/javascript" src="assets/js/pi.xhr.js"></script>
	<script type="text/javascript">	

	function refreshFonts() {
		var
			win, doc,
			title, ingress, footer, image, 
			footerDisp, titleDisp, ingressDisp,
			t = document.createTextNode(' '),
			i = document.createTextNode(' '),
			f = document.createTextNode(' '),
			contentframe = document.getElementById("contentframe"),
			disp;  // don't worry about previous display style


		console.log("contentframe : " + contentframe + ", type : " + typeof contentframe, contentframe);

		win = contentframe.contentWindow; // reference to iframe's window
		// reference to document in iframe
		doc = contentframe.contentDocument? contentframe.contentDocument: contentframe.contentWindow.document;

		if (!win) {
			console.error("No win!");
			// console.log("contentframe: ", contentframe);
			return false;
		}
		else {
			console.info("win : ", win);
		}

		// win.addEventListener("load", function() {
		// 	console.log("ONLOAD!!!");
		// });

		if (!doc) {
			console.error("No doc!");
			console.log("contentframe: ", contentframe);
			return false;
		}
		else {
			if (doc && typeof doc.getElementById == "function") {
				title = doc.getElementById("title");
				ingress = doc.getElementById("ingress");
				footer = document.getElementById("footer");
				console.info("Finding title!");
				console.info("title: ", title);
				console.info("Finding ingress!");
				console.info("ingress: ", ingress);
			}
			else {
				console.error("Still no title!");
			}
		}

		doc.addEventListener("DOMContentLoaded", function() {
			console.log("DOMContentLoaded!!!", document);
		});

		if (!title || !ingress) {
			console.error("NO ELEMENTS!");
			return;
		}
		titleDisp = title.style.display;
		ingressDisp = ingress.style.display;
		footerDisp = footer.style.display;

		title.appendChild(t);
		title.style.display = 'none';
    title.style.display = titleDisp;
		ingress.appendChild(i);
		ingress.style.display = 'none';
    ingress.style.display = ingressDisp;
		footer.appendChild(f);
		footer.style.display = 'none';
    footer.style.display = footerDisp;

		// console.info("Setting timeout in refreshFonts");
		// setTimeout(function() {
		// 	doc.body.innerHTML = doc.body.innerHTML;
		// }, 200); // you can play with this timeout to make it as short as possible		
	}


	</script>

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
      pointer-events: none;
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

			/* HEADER BACKGROUND COLOURS */
			/* BLUE: 		#4594DF  rgb(69, 128, 223)  */
			/* ORANGE: 	#E36018  rgb(227, 96, 24)  	*/
			/* PURPLE: 	#E64BCA  rgb(230, 75, 202)  */

			/* TEXT COLOURS */
			/* BLUE: 		#1B3162 	rgb(27, 49, 98)  	*/
			/* ORANGE: 	#5E1600 	rgb(94, 22, 0) 		*/
			/* PURPLE: 	#5B0039 	rgb(90, 0, 56) 		*/


			background 	: rgb(69, 128, 223);

/*
			background 	: rgb(48,48,48);
			background 	: -webkit-gradient(linear, left top, left bottom, color-stop(0%,rgba(48, 48, 48, 1)), color-stop(50%, rgba(48, 48, 48, 1)), color-stop(51%, rgba(85, 85, 85, 1)), color-stop(100%, rgba(85, 85, 85, 1)));
			background  : -webkit-linear-gradient(top, rgba(48, 48, 48, 1) 0%, rgba(48, 48, 48, 1) 50%, rgba(85, 85, 85, 1) 51%, rgba(85, 85, 85, 1) 100%);
			background  : linear-gradient(to bottom, rgba(48, 48, 48, 1) 0%, rgba(48, 48, 48, 1) 50%, rgba(85, 85, 85, 1) 51%, rgba(85, 85, 85, 1) 100%);
*/
			/*overflow 		: hidden;*/
			overflow 		: visible;

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
			margin-top 	: 26px;
			margin-right: 16px;
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
			font-weight 		: 400;
			background 			: rgb(27, 49, 98);
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
			margin-top 	: 26px;
			margin-left : 16px;
			font-size 	: 40px;
			line-height : 45px;
		}



		@media (max-width: 600px) {

 			#footerlogo {
 				display:inline-block;
 				position:absolute; 
 				left:14px; 
 				bottom:28px;
 				width: 24px;
 				height: 24px;
 			}

			footer {
				padding-top: 6px;
				height: 45px;
			}
			section.content {
				height: 633px;
			}

			section.content.fullscreen {
				top: -90px;
				height: 768px;
			}

			footer {
				font-size: 32px;
				line-height: 36px;
			}
		}

		@media (min-width: 601px) {

 			#footerlogo {
 				display:inline-block;
 				position:absolute; 
 				left:44px; 
 				bottom:7px;
 			}

			section.content {
				height: 249px;
			}
			section.content.fullscreen {
				top: -90px;
				height: 384px;
			}
		}


		.symbol {
			display: none;
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

		.logo-holder {
			display: block;
			margin: 0 auto;
			position: absolute;
			top: 6px;
			text-align: center;
			width: 100%;
			height: 110px;
/*			background-image: url(assets/img/icon-114.png);
			background-repeat: no-repeat;
			background-size: 100%;
*/			z-index: 5003;
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
		<div class="logo-holder">
			<img src="assets/img/ap-logo-256.png" width="110" height="110">
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
		var
			slideCount = 0;

		console.info("reloadPlayer");

		if (window.Storage) {

			// returns null if non-existent
			slideCount = localStorage.getItem("slideCount");
			if (slideCount === null) {
				slideCount = 1;
			}
			else {
				// convert to integer, and increment
				slideCount = parseInt(slideCount, 10);
				slideCount++;
			}

			// update localStorage with current value
			localStorage.setItem("slideCount", slideCount);			
		}

		// one screen, then instagram, another screen, then video. Rinse, repeat.
		if (slideCount > 2 && (slideCount % 2 == 0)) {
			console.info("loading video, slideCount : " + slideCount);
			if (slideCount % 4 == 0) {
				loadInsta();
			}
			else {
				loadVideo(slideCount);
			}
			// document.body.innerHTML = document.body.innerHTML;
		}
		else {
			console.info("not loading video, slideCount : " + slideCount);
			location.reload();
		}
	}


	function loadInsta() {
		var 
			contentframe = document.getElementById("contentframe");

		contentframe.src = "instagram.php";

		console.info("loading insta")
		// setTimeout(location.reload, 20000);
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
			data = window.data.videos[id % window.data.videos.length];
			if (data) {
				enterFullscreen();
				console.log("entering fullscreen mode");
				video.src = data.uri;
				video.style.display = "block";
				video.style.background = "transparent";
				video.style.opacity = 0;
				video.style.position = "absolute";
				video.style.top = 0;
				video.style.left = 0;
				video.style.right = 0;
				video.style.bottom = 0;
				video.style.zIndex = 50000;

				// there should only be 1 video at a time
				video.id = "video";
				video.setAttribute("preload", true);
				// video.setAttribute("autoplay", true);
				// video.addEventListener("canplay", onready);
				video.addEventListener("ended", reloadPlayer);
				video.addEventListener("canplay", function (e) {
					video.style.opacity = 1;
					video.play();
				});

				document.body.appendChild(video);
			}
			else {
				console.error("No data!");
				console.info("data:");
				console.info(window.data);
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
			url = "assets/php/videolist.php?cb=" + (Math.random() * 10000);

			url += "&width=" + width + "&height=" + height;

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

					pi.xhr.get(self.jsonfile + "?cb=" + (Math.random()*10000), self.onupdate, pi.log);
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
					result = pi.xhr.get(list + "?cb=" + (Math.random()*10000), callback, onerror);
				}

			}; // playlist




				window.player = {
					_frames 	: document.querySelectorAll("iframe.screen"),
					_playlist : playlist,
					_created 	: Date.now(),
					_started 	: null,
					_header 	: document.querySelector("header"),
					_footer 	: document.querySelector("footer"),
					_head			: "<!doctype html> <html> <head> <title><\/title> <meta name=\"viewport\" content=\"width=device-width, initial-scale=1, user-scalable=no\"> <link rel=\"stylesheet\" type=\"text\/css\" href=\"\/assets\/font\/clan.css\"> <link rel=\"stylesheet\" type=\"text\/css\" href=\"\/assets\/font\/publico.css\"><script type=\"text\/javascript\" src=\"assets\/js\/mustache.js\"><\/script> <script type=\"text\/javascript\" src=\"assets\/js\/pi.core.js\"><\/script> <script type=\"text\/javascript\" src=\"assets\/js\/pi.xhr.js\"><\/script> <style type=\"text\/css\"> html,body {margin: 0; padding: 0; height: 100%; min-height: 100%; text-align: center} <\/style> <\/head> <body>",
					_tail			: '<\/body><\/html>',

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

					_replaceAll : function(html, search, replace) {
						while (html.indexOf(search)>-1) {
							html = html.replace(search, replace);
						}
						return html;
					},


					_setIframeContent : function(iframe, html) {
						var
							self = player,
							win,
							html = self._replaceAll(html, "&#x2F;", "\/"),
							iframe = iframe instanceof HTMLIFrameElement ? iframe : document.getElementById(iframe);

						win = iframe.contentWindow || (iframe.contentDocument.document || iframe.contentDocument);
						win.document.open();
						win.document.write(self._head)
						win.document.write(html);
						win.document.write(self._tail);
						win.document.close();
						// console.info("Updated iframe content!");
						// // console.info(self._head);
						// console.info(html);
						// console.info(self._tail);
					},

					show : function(screen) {
						var
							self = player,
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

								screen.image = "https:\/\/kreateam.io\/html5\/oya\/" + screen.image;

								var
									inner = Mustache.render(html, screen);
								// console.log("setting innerHTML: " + inner );

								// _setIframeContent(contentframe, head + inner);
								self._setIframeContent(contentframe, inner);

								// contentframe.contentWindow.document.body.innerHTML = inner;

								if (screen.fullscreen === true) {
									enterFullscreen();
								}
								else if (player.fullscreen === true) {
									// Que?
									exitFullscreen();
								}
								console.info("RENDERING screen INTO TEMPLATE : ", screen);
								// setTimeout(refreshFonts, 100);
								
								// contentframe.contentWindow.document.innerHTML = inner;
							}
						}


					},

					preload : function(screen) {
						var
							self = player,
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

								console.log("setting innerHTML: " + inner );
								nextframe.contentWindow.document.body.innerHTML = inner;
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
						var
							self = player;

						if (!e || !e.data) {
							console.error("No event");
							return;
						}
						// on reply from iframe
						console.info("IFRAME says: " + e.data, e.data);
						if (e.data.bgColor) {
							if (self._header) {
								self._header.style.backgroundColor = e.data.bgColor;
							}
						}
						if (e.data.fgColor) {
							if (self._footer) {
								self._footer.style.backgroundColor = e.data.fgColor;
							}
						}
					}

			}; // player

		// listen for messages posted to our window
		window.addEventListener('message', player.onFrameMessage, false);

		function onTemplatesLoaded(json) {
			var
				data,
				json = json || null;

			if (json) {
				try {

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
				catch(e) {
					console.error("Invalid JSON:");
					console.error(json);
				}
			}
		};

		pi.xhr.get("templates.php" + "?cb=" + (Math.random()*10000), onTemplatesLoaded);

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
	<div style="position: fixed; z-index: 10000; bottom: 0; right: 0; pointer-events: default; -webkit-touch-callout: default;-webkit-user-select: default;-moz-user-select: default;-ms-user-select: default;user-select: default;cursor: default;pointer-events: default;">
		<script> console.debug('NOWCAST: <?php json_encode($nowcast);	?>');</script>
	</div>
</body>
</html>