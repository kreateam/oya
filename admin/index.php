<?php

	session_start();

	error_reporting(E_ALL);

	$users = array(
		"xman" => "tsxx",
		"rune" => "apnettopp1",
		"kreateam" => "apnettopp1",
		"osloby_no" => "osloby.no",
		"chakarls" =>	"Karlsen2015"
		);


	function denied($message) {
    header('WWW-Authenticate: Basic realm="Kreateam Admin"');
    header('HTTP/1.0 401 Unauthorized');
    die($message || "Unauthorized.");
	}

	if (!isset($_SERVER['PHP_AUTH_USER'])) {
		denied("No user.");
	} 
	elseif (!isset($_SERVER['PHP_AUTH_PW'])) {
		denied("Empty password.");
	}
	else {

		$user = strtolower($_SERVER['PHP_AUTH_USER']);
		$pass = $_SERVER['PHP_AUTH_PW'];

		$_SESSION['user'] = $user;

		if (!isset($users[$user]) || $users[$user] != $pass) {
			denied("Rejected.");
		}
	}

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
	<!--script type="text/javascript" src="assets/js/errorhandler.js"></script-->

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="/html5/assets/font/clan.css">
	<link rel="stylesheet" type="text/css" href="assets/css/tabstrip.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/buttons.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/forms.css" />

	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>

	<!-- needed for buttons.js -->
	<script src="/html5/assets/js/jquery.min.js"></script>
	<script src="assets/js/mustache.js"></script>
	<script src="assets/js/pi.core.js"></script>
	<script src="assets/js/pi.xhr.js"></script>

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


		/* animation and transition defaults */
		* {
			-webkit-animation-timing-function: cubic-bezier(0.190, 1.000, 0.220, 1.000);
							animation-timing-function: cubic-bezier(0.190, 1.000, 0.220, 1.000);
			-webkit-animation-duration: 1s;
							animation-duration: 1s;

			-webkit-animation-fill-mode: both;
							animation-fill-mode: both;

			-webkit-transition-timing-function: cubic-bezier(0.190, 1.000, 0.220, 1.000);
							transition-timing-function: cubic-bezier(0.190, 1.000, 0.220, 1.000);
			-webkit-transition-duration: .4s;
							transition-duration: .4s;
		}

	</style>

	<style type="text/css">

		span {
			-webkit-transition: color .4s;
			 				transition: color .4s;
		}

	</style>

	<style type="text/css">


		::-webkit-scrollbar {
		    width: 10px;
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
			font-weight: 400;
			min-height: 100%;
			min-width: 100%;
			height: 100%;

      /*-webkit-font-smoothing: antialiased;*/


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
			padding-left 	: 0.25em;
			padding-right : 0.25em;
			text-align 		: right;
		}


		iframe {
			background-color: #fff;
			border: solid 1.5em rgba(0, 0, 0, 0.3);
			/*background: none repeat scroll 0% 0% transparent; */
			/*width: 100%;*/

			-webkit-background-clip: padding-box; /* for Safari */
	    				background-clip: padding-box; /* for IE9+, Firefox 4+, Opera, Chrome */		
    }

		header {
			margin-bottom: .4em;
		}

		footer {
			position: fixed;
			bottom: 0;
			margin-right: auto;
			margin-left: auto;
			width: 66%;
			min-height: 45px;
			font-weight: 400;
			font-size: 1.5em;
			background-color: #272822;

			-webkit-transition: height .4s;
							transition: height .4s;
		}

		.highlighted {
			color: #000;
			font-weight: 700;
		}


		.content {
			width: 100%;
			height: 100%;
		}

		.item {
			display: block;
			margin: 2px 0.5em;
			padding-top: 2px;
			text-align: left;
			background-color: rgba(39, 40, 34, 0.6);
			-webkit-transition: top .4s;
							transition: top .4s;
		}

		.item::before {
			/*content: "+ ";*/
		}

		.hashtag {
			font-weight: 600;
			color: rgba(38,188,244,1);

			-webkit-transition: color 1.2s cubic-bezier(0.190, 1.000, 0.220, 1.000);
							transition: color 1.2s cubic-bezier(0.190, 1.000, 0.220, 1.000);
		}

		.hashtag:hover {
			color: #fff;
		}

		.hashtag.twitter {
			color: rgba(38,188,244,1);
		}

		.hashtag.instagram {
			color: #FF0098;
		}


		.screen {
			/* to override the iframes's inline */
			display: block;

			/* initial state, because we want to fade in @load */
			opacity: 0;

			-webkit-transform: scale(0.5, 0.5);
							transform: scale(0.5, 0.5);

			-webkit-transition: border .4s, opacity .4s, -webkit-transform .2s; /* Safari */
			-webkit-transition: border .4s, opacity .4s, transform .2s;
							transition: border .4s, opacity .4s, transform .2s;

		}

		#screen1 {
			position 	: fixed;
			left 			: 0;
			bottom 		: 0;

			-webkit-transform-origin : bottom left;
					-ms-transform-origin : bottom left;
							transform-origin : bottom left;
		}

		#screen2 {
			position 	: fixed;
			right 		: 0;
			bottom 		: 0;

			-webkit-transform-origin : bottom right;
					-ms-transform-origin : bottom right;
							transform-origin : bottom right;
		}

		.wrapper {
			position 		: relative;
			margin 			: 0;
			padding 		: 0;
			width 			: 100%;
			height 			: 100%;
			min-height 	: 100%;

			background-color: #fff;
		}

		.left {
			float 		: left;
			width 		: 17%;
			height 		: 100%;
			min-height: 100%;			

			padding-right: 8px;

			color: #fff;
			background-color: #C21E29;

			text-align: right;
		}

		.right ::selection {
			background: #EC008C;
		}

		.right {
			float 		: right;
			width 		: 17%;
			height 		: 100%;
			min-height: 100%;

			padding-left: 8px;

			color: #fff;
			background-color: #0099D6;

			text-align: right;
		}

		.middle {
			position 	: relative;
			width 		: 66%;
			min-height: 100%;
			height 		: 100%;
			margin 		: 0;
			padding 	: 0;

			margin-left : auto;
			margin-right: auto;

			font-size 	: 1.5em;
			font-weight : 300;

			color: #fff;
			background-color: rgba(39, 40, 34, 0.8);

			text-align: center;
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

		.up-arrow {
			width : .8em;
			height : .8em;
			display: inline-block;
			position: relative;
			padding-top: 0.2em;
			margin-right: 0.1em;
			margin-left: 0.1em;
			font-weight: 100;
			cursor: pointer;
		}

		.down-arrow {
			width : .8em;
			height : .8em;
			display: inline-block;
			position: relative;
			padding-top: 0.2em;
			margin-right: 0.1em;
			margin-left: 0.1em;
			font-weight: 100;
			cursor: pointer;
		}

		.arrow-down:hover {
			color: #fff;
		}

		.arrow-up:hover {
			color: #fff;
		}

		.item-menu {
			display: inline-block;
			color: rgba(255,255,255,0.1);
			/*float: right;*/
			text-align: left;
			width: auto;
			font-size: 1.2em;
			font-weight: 100;
			overflow-y: visible;
		}

		.coming-up {
			margin-left 	: 2.5em;
			margin-bottom : 2.5em;

			text-align 	: left;
			font-weight : 300;
		}

		.day {
			display: none;
			font-weight: 300;
		}

		.time {
			font-weight: 300;
			margin-right: .25em;
		}

		.artist {

			/* keep it all on one line, don't break on spaces */
			white-space: nowrap;
			font-weight: 900;

     	/* Make text selectable */
      -webkit-touch-callout: auto;
        -webkit-user-select: auto;
           -moz-user-select: auto;
            -ms-user-select: auto;
                user-select: auto;

      cursor: text;
		}


		.fat {
			font-weight: 900;
			font-size: 125%;
		}

		.unselectable {
	    -webkit-touch-callout: none;

	    -webkit-user-select: none;
	    	 -moz-user-select: none;
	    		-ms-user-select: none;
	    				user-select: none;
		}

		.selectable {
	    -webkit-touch-callout: auto;

	    -webkit-user-select: auto;
	    	 -moz-user-select: auto;
	    		-ms-user-select: auto;
	    				user-select: auto;			
	    cursor: auto;
		}


		#amfiet-hours {
			/*font-weight: 900;*/
		}

		#amfiet-minutes {
			/*font-weight: 900;*/
		}

		#vindfruen-hours {
			/*font-weight: 900;*/
		}

		#vindfruen-minutes {
			/*font-weight: 900;*/
		}


		#demo {
			text-align: center;
			
		}


		/* the content divs of the tabbed toolbar  */
		.tabstrip div {
			text-align: left;
		}

		#toolbar {
			position: relative;
			z-index: 100;

			font-family: Helvetica, Lato, sans-serif;
			font-weight: 400;
			font-size: 66%;
			height: 3.6em;
			/*text-align: left;*/

			/* doesn't work ... why o whai */
			-webkit-transition: top .3s cubic-bezier(0.190, 1.000, 0.220, 1.000), height .3s cubic-bezier(0.190, 1.000, 0.220, 1.000); 
			 				transition: top .3s cubic-bezier(0.190, 1.000, 0.220, 1.000), height .3s cubic-bezier(0.190, 1.000, 0.220, 1.000); /* easeOutExpo */
		}

		#toolbar.active {
			height: 20em;
		}

		#toolbar .closebutton {
			background-color: #272822;
			color: #fff;
			font-weight: 900;
			cursor: pointer;
		}


		#queue-duration {

			} 

		.queue-total {
			font-size: 66%;
			font-weight: 100;
		}

	</style>

<script type="text/javascript">

	// set time from server, so there are no discrepancies in timing
	window.serverTime = <?php print(time()*1000)?>;
	window.serverTimeRaw = "<?php echo (time()*1000)?>";
	
	window.clientTime = new Date().getTime();

	// console.log("serverTime : " + serverTime);
	// console.log("clientTime : " + clientTime);

	// remember delta between server and client clock, in millisecs
	window.deltaTime = clientTime - serverTime;

</script>


<script type="text/javascript">

	/**
	 * Handle keyboard commands
	 * 
	 * @param  {[type]} e [description]
	 */
	function keypressed(e) {

		// early escape
    if (document.activeElement instanceof HTMLInputElement || document.activeElement instanceof HTMLTextAreaElement) {
    	return;
    }

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

    // console.log("keypressed (" + keynum + ") : " + document.activeElement);

    key = String.fromCharCode(keynum);

    if (keynum == 27) {
    	hideModal();
    }

    /**
     * Handle keyboard commands
     * 
     * @param  {char} 	key The key that was pressed
     * @return {bool}   Returns true when capturing keystroke, otherwise false.
     */
    switch (key) {
	    case "1":
				if (screens[0].style.opacity == 0) {
					screens[0].style.opacity = 1;
				}
				else {
					screens[0].style.opacity = 0;
				}
				break;
			case "2":
				if (screens[1].style.opacity == 0) {
					screens[1].style.opacity = 1;
				}
				else {
					screens[1].style.opacity = 0;
				}
				break;

	    case "Z":
				if (screens[1].style.transform == "scale(1, 1)" || screens[1].style.transform == "scale(1)") {
					screens[0].style.transform = "scale(0.5, 0.5)";
					screens[1].style.transform = "scale(0.5, 0.5)";
					screens[0].style.border = "1em solid rgba(0,0,0,0.3)";
					screens[1].style.border = "1em solid rgba(0,0,0,0.3)";
				}
				else {
					// console.log("transform : " + screens[1].style.transform);
					screens[0].style.transform = "scale(1, 1)";
					screens[1].style.transform = "scale(1, 1)";
					screens[1].style.webKitTransform = "scale(1, 1)";
					screens[0].style.border = "2px solid rgba(0,0,0,0.4)";
					screens[1].style.border = "2px solid rgba(0,0,0,0.4)";
				}
				break;
			default:
				// nothing, at the moment

    } // switch

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
</head>
<body onkeydown="return keypressed(event);">
	<script type="text/javascript">

		/**
		 * callback for iframe.load
		 * 
		 * @param  {iframe} elem 
		 * @return {void}
		 */
		function iframeloaded(elem) {
			// console.log("iframe loaded : " + elem.id);
			elem.style.opacity = 0.1;
		}

	</script>

	<?php

		/** Mustache templates  */
		include '../templates.html';
	?>

	<div class="wrapper">
		<div class="left">
				<h4>Amfiet</h4>
				<div class="coming-up">
					<h1>Next</h1>
					<h2 class="artist">Razika</h2>
					<span><strong>in</strong></span>
						<div class="day-counter">&nbsp;&nbsp;&nbsp;&nbsp;<span id="amfiet-days" class="fat"></span> days
						</div>
					<div class="hour-counter">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="amfiet-hours" class="fat"></span> hours</div>
					<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="amfiet-minutes" class="fat"></span> minutes</div>
				</div>

				<ul id="program1">
					
				</ul>
		</div>
		<div class="right">
				<h4>Vindfruen</h4>
				<div class="coming-up">
					<h1>Coming Up</h1>
					<h2 class="artist">The Switch</h2>
					<span><strong>in</strong></span>
						<div class="day-counter">
							&nbsp;&nbsp;&nbsp;&nbsp;<span id="vindfruen-days" class="fat"></span> days
						</div>
					<div class="hour-counter">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="vindfruen-hours" class="fat"></span> hours</div>
					<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="vindfruen-minutes" class="fat"></span> minutes</div>
				</div>
				<ul id="program2">
					
				</ul>
		</div>
		<style type="text/css">
			[draggable] {
			  -webkit-user-select: none;
			  -khtml-user-select: none;
			  -moz-user-select: none;
			  user-select: none;

			  /* Required to make elements draggable in old WebKit */
			  -khtml-user-drag: element;
			  -webkit-user-drag: element;
			}
		</style>

		<div class="middle">
			<section class="content">
				<header>
					Play Queue
				</header>

				<section id="current">
					<div class="section-title">Now playing</div>
					<ul>
						<li id="item-0" class="item playing">Weather - today<span class="duration">2:45</span></li>
					</ul>
				</section>


<style type="text/css">

	.item-menu ul {
		-webkit-padding-start : 0px;
	  text-align: left;
	  display: inline;
	  margin: 0;
	  /*padding: 15px 4px 17px 0;*/
	  list-style: none;
	  -webkit-box-shadow: 0 0 5px rgba(0, 0, 0, 0.15);
	  				box-shadow: 0 0 5px rgba(0, 0, 0, 0.15);
	}

	.item-menu ul li {
	  font: bold 10px/16px sans-serif;
	  display: inline-block;
	  margin-right: -4px;
	  position: relative;
	  padding: 1px 10px;
	  cursor: pointer;
	  -webkit-transition: all 0.2s;
	  		-ms-transition: all 0.2s;
	  				transition: all 0.2s;
	}

	.item-menu ul li:hover {
	  background: #555;
	  color: #fff;
	}

	.item-menu ul li ul {
	  padding: 0;
	  position: absolute;
	  /*top: 48px;*/
	  left: 0;
	  width: 150px;
	  -webkit-box-shadow: none;
	  -moz-box-shadow: none;
	  box-shadow: none;
	  display: none;
	  opacity: 0;
	  visibility: hidden;
	  -webkit-transiton: opacity 0.2s;
	  transition: opacity 0.2s;
	}

	.item-menu ul li ul li { 
	  background: rgba(0,0,0,0.66); 
	  display: block; 
	  color: #fff;
	  text-shadow: 0 -1px 0 #000;
	}

	.item-menu ul li ul li:hover { 
	  background: rgba(99,99,99,0.66); 
	}

	.item-menu ul li:hover ul {
	  display: block;
	  opacity: 1;
	  visibility: visible;
	}
</style>


				<section id="queue">
					<div class="section-title">Queued <span id="queue-duration" class="queue-total">1:30</span></div>
					<ul>
						<li id="item-1" class="item">
							Food report #32 
							<span class="duration">0:30</span>
						</li>
						<li id="item-2" class="item">
							Instagram 
							<span class="hashtag instagram">#osloby #latest</span>
							<span class="duration">1:00</span>
						</li>
					</ul>
				</section>

				<section id="next-items">
					<div class="section-title">Next Items <span id="next-duration" class="queue-total">6:00</span></div>
					<ul>
						<li class="item">
							<div class="item-menu">
								<ul>
								  <li>+<ul>
								      <li>Play next</li>
								      <li>Add to Queue</li>
								      <li>Add to Next tracks</li>
								    </ul>
								  </li>
								</ul>	
							</div>
							Announcement : &lt;TBA&gt;
							<span class="duration">0:30</span>
						</li>
						<li class="item" draggable="true">
							<div class="item-menu">
								<ul>
								  <li>+<ul>
								      <li>Play next</li>
								      <li>Add to Queue</li>
								      <li>Add to Next tracks</li>
								    </ul>
								  </li>
								</ul>	
							</div>
							Instagram
							<span class="hashtag twitter">#osloby</span>
							<span class="duration">1:30</span>
						</li>
						<li class="item" draggable="true">
							<div class="item-menu">
								<ul>
								  <li>+<ul>
								      <li>Play next</li>
								      <li>Add to Queue</li>
								      <li>Add to Next tracks</li>
								    </ul>
								  </li>
								</ul>	
							</div>
							Instagram
							<span class="hashtag twitter">#oya</span>
							<span class="duration">1:30</span>
						</li>
						<li class="item" draggable="true">
							<div class="item-menu">
								<ul>
								  <li>+<ul>
								      <li>Play next</li>
								      <li>Add to Queue</li>
								      <li>Add to Next tracks</li>
								    </ul>
								  </li>
								</ul>	
							</div>
							Coming up...
							<span class="duration">2:30</span>
						</li>
					</ul>
				</section>

				<section id="demo"></section>
			</section>



<script>
	
	/* playlist and stuff */

	document.addEventListener("DOMContentLoaded", function() {
		var
			playlist = {
				updated : Date.now() || 0,
				_loaded : false,

				onupdate : function() {
					console.info("playlist updated!")
				},

				update : function() {
					console.info("updating playlist")
				},

				onload : function(json) {
					var 
						self = playlist,
						_data = JSON.parse(json) || null;

					if (_data) {
						if (self._loaded === false) {
							self._loaded = Date.now();
						}

						console.info("playlist loaded: " + self._loaded, _data);
					}
					else {
						console.error("No playlist data");
					}
				},

				load : function(list, callback, onerror) {
					var
						result = null,
						list = list || "assets/php/data/playlist.json";

					// console.info("loading playlist");

					pi.require("xhr", false, false, function() {
						// console.log("Sending xhr");
						result = pi.xhr.get(list, callback, onerror);
					});

				}
			}; // playlist


			// heigh ho
			playlist.load(null, playlist.onload);


			var 
				player = {
					_frames 	: document.querySelectorAll("iframe.screen"),
					_playlist : playlist,
					_created 	: Date.now(),
					_started 	: null,

					init : function() {
						this.sendMessage("ping");
					},
					
					start : function() {
						// console.log("player.start called!");
						if (this._started === null) {
							this.init();
							this._started = Date.now();
							// console.info("_started : " + this._started);
						}
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
									console.debug("sending msg to iframe " + i + " : " + msg + ", domain: " + domain);

									iframe.contentWindow.postMessage(msg, domain);
								}
							}
						}
					},

					onFrameMessage: function(e) {
						// on reply from iframe
						console.log("iframe says: " + e, e);
					}

			}; // player

		window.addEventListener('message', player.onFrameMessage, false);

		// let's go 
		player.start();

	});

</script>




<style type="text/css">
	
	/* forms and stuff */


	progress {
		visibility: hidden;
	}

	optgroup {
		text-transform: capitalize;
	}

	textarea,
	input[type="text"] {
	  display: inline-block;
	  margin: 0;
	  width: 90%;
	  font-family: sans-serif;
	  font-size: 75%;
	  -webkit-appearance: none;
	  appearance: none;
	  
	  box-shadow: none;
	  border-radius: none;
	  padding: .4em;
	  border: solid 1px #dcdcdc;
	  transition: box-shadow 0.3s, border 0.3s;
	}

	textarea:focus,
	textarea.focus,
	input[type="text"]:focus,
	input[type="text"].focus {
	  outline: none;
	  border: solid 1px rgba(38,188,244,.8);

  	box-shadow: 0 0 15px 5px rgba(39,40,34,.3);
	}

 	#image-search {
 		width: 20em;
 	}

	.error {
		display: none;
		background-color: #ff0545;
		padding: 0.2em 0.5em;
		color: #fff;
	}

	.status {
		display: none;
		background-color: #0545ff;
		padding: 0.2em 0.5em;
		color: #fff;
	}

	.editor {
		/*background-color: rgba(39,40,34,0.4);*/
		float: left;
		height: 100%;
		width: 4em;
	}


	figure {
		display: inline-block;
		margin: .2em .8em;
	}

	img.preview {
		padding: 0;
		height: 6em;
	}

	img.preview:hover {

	}

	.preview {
		display: inline;
		clear: both;
		float: left;
		text-align: left;

		/*margin-top: .6em;*/
	}

	/* the spans that hold individual preview images */
	.preview-image {	
    display : inline-block;
    /*width: 25%;*/
		/*margin: .2em .8em;*/

	}

</style>

			<footer>
			<div id="toolbar" class="tabstrip selectable">
		    <ul>
	        <li>
            <input type="radio" name="tabstrip-0" id="tabstrip-0-0" />
            <label for="tabstrip-0-0">New...</label>
            <div id="template-selector">
            	<!-- will be rendered from template -->
            </div>
	        </li>
	        <li>
            <input type="radio" name="tabstrip-0" id="tabstrip-0-1" checked="checked" />
            <label for="tabstrip-0-1">Images</label>
            <div class="images">
            	+ <input type="file" name="image-upload" id="image-upload" accept="image/*" />
            	&nbsp;&nbsp;filter &nbsp;&nbsp;<input type="text" name="image-search" id="image-search" />
            	<progress id="image-upload-progress" max="100"></progress>
            	<span id="image-upload-status" class="status"></span>
							<div>
	            	<div id="image-editor" class="editor"></div>
	            	<div id="image-preview" class="preview"></div>
							</div>
            </div>
	        </li>
	        <li>
            <input type="radio" name="tabstrip-0" id="tabstrip-0-2" />
            <label for="tabstrip-0-2">Screens</label>
            <div class="videos">
            	<input type="file" name="video-upload" id="video-upload" accept="video/*" />
            	<progress id="video-upload-progress" max="100"></progress>
            </div>
	        </li>
	        <li>
            <input type="radio" name="tabstrip-0" id="tabstrip-0-3" />
            <label for="tabstrip-0-3">Instagram</label>
            <div class="instagram">
              <style>
              	.ig-b- { 
              		display: inline-block; 
              	}

								.ig-b- img { 
									visibility: hidden; 
								}

								.ig-b-:hover {
									background-position: 0 -60px; 
									} 

								.ig-b-:active {
									background-position: 0 -120px;
								}

								.ig-b-48 {
									width: 48px;
									height: 48px;
									background: url(//badges.instagram.com/static/images/ig-badge-sprite-48.png) no-repeat 0 0; 
								}

								@media only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (-moz-min-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: 2 / 1), only screen and (min-device-pixel-ratio: 2), only screen and (min-resolution: 192dpi), only screen and (min-resolution: 2dppx) {
									.ig-b-48 {
										background-image: url(//badges.instagram.com/static/images/ig-badge-sprite-48@2x.png);
										background-size: 60px 178px;
									}
								}
							</style>

							<a target="_blank" href="http://instagram.com/osloby_no?ref=badge" class="ig-b- ig-b-48"><img src="//badges.instagram.com/static/images/ig-badge-48.png" alt="Osloby.no - Instagram" />osloby.no</a>

              <p>There are many variations of passages of Lorem Ipsum available, 
                  but the majority have suffered alteration in some form, 
                  by injected humour, or randomised words which don't look 
                  even slightly believable. If you are going to use a 
                  passage of Lorem Ipsum, you need to be sure there isn't 
                  anything embarrassing hidden in the middle of text. All 
                  the Lorem Ipsum generators on the Internet tend to 
                  repeat predefined chunks as necessary, making this the 
                  first true generator on the Internet. It uses a dictionary
                  of over 200 Latin words, combined with a handful of model 
                  sentence structures, to generate Lorem Ipsum which looks 
                  reasonable. The generated Lorem Ipsum is therefore 
                  always free from repetition, injected humour, or 
                  non-characteristic words etc.
              </p>
            </div>
	        </li>
	        <li class="closebutton">
            <input type="radio" name="tabstrip-0" id="tabstrip-0-4" />
            <label for="tabstrip-0-4" class="closebutton">x</label>
	        </li>
		    </ul>
			</div>
			</footer>
		</div>

	</div>

	<iframe id="screen1" class="screen" scrolling="no" src="player.php" width="512" height="704" onload="iframeloaded(this)"></iframe>
	<iframe id="screen2" class="screen" scrolling="no" src="weather.php" width="672" height="384" onload="iframeloaded(this)"></iframe>

	<script type="text/javascript">


		function updateTemplateList() {
			var
				template, templates,
				templateSelector = document.getElementById("template-selector"),
				itemTemplate = document.getElementById("template-selector-item-template").innerHTML,
				li, obj,
				ul = document.createElement("ul");

			// console.log("updateTemplateList()!");

			if (window.data && window.data.templates && window.data.templates.length) {
				templates = window.data.templates;
				for (var i = 0; i < templates.length; i++) {
					obj = !!templates[i].json ? templates[i].json : templates[i];
					console.info("rendering : ", obj);
					li = document.createElement("li");
					li.innerHTML = Mustache.render(itemTemplate, obj);
					ul.appendChild(li);
				}
				templateSelector.appendChild(ul);
				return i;
			}
			else {
				console.error("No templates!");
			}
			return null;
		}


		function getTemplate(name) {
			var
				templates, template,
				name = name || "default";

			if (window.data && window.data.templates && window.data.templates.length) {
				templates = window.data.templates;
				for (var i = 0; i < templates.length; i++) {
					template = templates[i];
					if (template.name && template.name == name) {
						return template.filecontent;
					}
				}
			}
			return null;
		}


		window.addEventListener('load', function() {
			var
				screen = document.getElementById("screen1"),
				templates = {
					updated : 0
				},
				onTemplatesLoaded = function(json) {
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
							console.info("window.data.templates are loaded: ", window.data.templates);
							updateTemplateList();
						}
					}
					// console.info("templates loaded: ", data);
				};

			// console.info("loading templates: " + data);
			pi.xhr.get("templates.php", onTemplatesLoaded)

		});

	</script>


	
	<script type="text/javascript">


		function editImage(id) {
    	var
    		data = findImage(id),
    		editor  = document.getElementById("modal-dialog"),
	      images 	= editor.getElementsByTagName("img"),
				template= document.getElementById("image-modal-dialog-template").innerHTML,
				image, filename = "";


			if (images && images.length) {
				for (var i = 0; i < images.length; i++) {
					editor.removeChild(images[i]);
				}
			}

			image = document.createElement("img");


      image.src 					= data.uri;
      image.className 		= "zoomInDown";
      image.style.display = "inline";
      image.style.height 	= "16em";
      // editor.style.width = "auto";

      // console.log("file.name : " + file.name);
      editor.innerHTML = Mustache.render(template, data);

      var
      	submit = document.getElementById("form-image-editor-submit"),
      	titleField = document.getElementById("form-image-editor-title");

      submit.addEventListener("click", onImageSave);


      if (titleField && titleField instanceof HTMLInputElement) {
      	titleField.focus();
      	titleField.select();
      }


      if (editor.firstChild) {
      	console.log("editor.insertBefore");
      	// editor.appendChild(image);
	      editor.insertBefore(image, editor.firstChild);
      }
      else {
      	console.log("editor.appendChild");
      	editor.appendChild(image);
      }
			var
				modalForm = document.getElementById("modal-one");

			console.log("toggling modal");
			if (modalForm.classList.contains("active")) {
				modalForm.classList.remove("showing");
				setTimeout(function(){
					modalForm.classList.toggle("active");
				}, 1000);
			}
			else {
				modalForm.classList.add("active");
				setTimeout(function(){
					modalForm.classList.toggle("showing");
				}, 1);
			}
		}; // editImage


		function findImageElement(id) {
			var
				result = false,
				id = id || null,
				images = window.data.images || [];

			if (id && images && images.length) {
				for (var i = 0; i < images.length; i++) {
					console.log(i + " : " + images[i].postId);
					if (images[i].postId == id) {
						
						result = document.getElementById("i" + id);

						break;
					}
				}
			}

			return result;
		}

		function findImage(id) {
			var
				result = false,
				id = id || null,
				images = window.data.images || [];

			if (id && images && images.length) {
				for (var i = 0; i < images.length; i++) {
					// console.log(i + " : " + images[i].postId);
					if (images[i].postId == id) {
						result = images[i];
						break;
					}
				}
			}

			return result;
		}


		function highlight(container, what, spanClass) {
	    var
	    	spanClass = spanClass || "highlighted",
	    	content = container.innerHTML,
	      pattern = new RegExp('(>[^<.]*)(' + what + ')([^<.]*)','gi'),
	      replaceWith = '$1<span ' + ( spanClass ? 'class="' + spanClass + '"' : '' ) + '">$2</span>$3',
	      highlighted = content.replace(pattern, replaceWith);

	    return (container.innerHTML = highlighted) !== content;
		}


		/**
		 * global support function
		 * @param  {array|null} images [description]
		 * @return {[type]}        [description]
		 */
		function updateImages(images) {
			var
				image,
				images = images || window.data.images || [],
		    imageSearch = document.getElementById("image-search"),
				template = document.getElementById("imagelist-item-template").innerHTML,
				previews = document.getElementById("image-preview"),
				fragment = document.createDocumentFragment();

			if (!images || typeof images.length != "number") {
				console.error("no images!");
				return;
			}
	
			previews.innerHTML = "";

			for (var i = images.length-1; i >= 0; i--) {
				image = document.createElement("span");
				image.className = "preview-image";
				// console.log ("image : ", images[i]);
				image.innerHTML = Mustache.render(template, images[i]);
				fragment.appendChild(image);
			}

			previews.appendChild(fragment);

			if (imageSearch && imageSearch.value) {
				// console.log("highlighting : " + imageSearch.value);
				highlight(previews, imageSearch.value);
			}
		}

		document.addEventListener("DOMContentLoaded", function () {
			var
				result = null,
				imageSearch = document.getElementById("image-search"),
				imageList   = document.getElementById("image-preview");




			imageSearch.addEventListener("input", function(e) {
				if (this && this.value) {
					result = fuzzyMatchImages(this.value);
					updateImages(result);
				}
				else {
					// no search term, so show everything
					updateImages();
					// suppress
				}

			});

			function fuzzyMatchImages(input) {
			  var
  				images = window.data.images,
			  	thorough = input.toString().length > 5,
			  	reg = new RegExp(input.split('').join('\\w*').replace(/\W/, ""), 'i');

			  return images.filter(function(image) {
	
			  // console.log("image : " + image, image);
		    if (image.title && image.title.match(reg)) {
			      return image;
			    }
			    else if (image.tags && image.tags.match(reg)) {
			    	return image;
			    }
			    else if (image.filename && image.filename.match(reg)) {
			    	return image;
			    }
			    else if (image.name && image.name.match(reg)) {
			    	return image;
			    }
			    else if (thorough) {
			    	if (image.description && image.description.match(reg)) {
			    		return image;
			    	}
			    }
			    else {
			    	return false;
			    }
			  });
			}

		});

		
	</script>

	<script type="text/javascript">


		function onImageSave(e) {
    	var 
    		fields,
    		xhr = new XMLHttpRequest(),
    		fieldset = document.getElementById("form-image-editor-fieldset").children,
    		formData = new FormData(fieldset),
	      status  = document.getElementById("image-upload-status"),
	      modalForm = document.getElementById("modal-one");


			console.log("fieldset" + fieldset, fieldset);

			if (fieldset && fieldset.length) {
				for (var i = 0; i < fieldset.length; i++) {
					if (fieldset[i] instanceof HTMLInputElement || fieldset[i] instanceof HTMLTextAreaElement) {
						console.log(typeof fieldset[i] + " : " + fieldset[i], fieldset[i]);
			    	formData.append(fieldset[i].name || fieldset[i].id, fieldset[i].value);
					}
				}
			}

    	// fieldset.forEach(function(p,i,a) {
    	// 	console.log(i + " : " + p, p);
    	// });

    	console.log("Sending: " + formData, formData);


    	xhr.open("POST", "admin/image-update.php");
    	xhr.send(formData);

    	xhr.onload = function() {
    		if (this.status == 200) {
    			status.textContent = "Success.";
	  			status.className = "status";
    			console.log("Success!");
    			console.log("response: " + this.responseText);
    			hideModal();
  				try {
	    			var 
	    				response = JSON.parse(this.responseText),
	    				span = document.createElement("span"),
	    				preview = document.getElementById("image-preview"),
	    				template = document.getElementById("imagelist-item-template").innerHTML;

	    			// new image ?
	    			if (!findImage(response.postId)) {
	 	    			window.data.images.push(response);
	    			}
	    			updateImages();

	    			// // console.log("RESPONSE: ", response);
	    			// span.innerHTML = Mustache.render(template, response);
	    			// if (preview.firstChild) {
	    			// 	preview.insertBefore(span, preview.firstChild);
	    			// }
	    			// else {
	    			// 	preview.appendChild(span);
	    			// }    				
    			}
    			catch(e) {
    				console.error(e);
    			}
    		}
    	};
    	xhr.onerror = function() {
  			status.textContent = "Success.";
  			status.className = "error";
  			console.log("Error!");
    	};


    }


		/** @todo  This seems safe enough, no? */
		document.querySelector('#image-upload').addEventListener('change', function(e) {
			  var
			  	file 	= this.files[0],
			  	progress = document.getElementById("image-upload-progress"),
			  	data 	= new FormData(),
			  	xhr 	= new XMLHttpRequest(),


			  	// @todo Maybe some visual feedback on upload progress
			  	onprogress = function(e) {
				    if (e.lengthComputable) {
				      var
				      	percentComplete = (e.loaded / e.total) * 100;

				      if (progress) {
				      	progress.value = percentComplete;
				      }

				      console.log(percentComplete + '% uploaded');
				    }
				  },

				  /**
				   * When image is returned from server
				   * @return {void}
				   */
				  onload = function() {
				  	if (progress) {
				  		progress.style.visibility = "hidden";
				  	}
				    if (this.status == 200) {
				    	var
					      resp 		= JSON.parse(this.response),
				    		preview = document.getElementById("image-preview"),
				    		editor  = document.getElementById("modal-dialog"),
					      images 	= editor.getElementsByTagName("img"),
					      status  = document.getElementById("image-upload-status"),
								template= document.getElementById("image-modal-dialog-template").innerHTML,
								data 		= {},
								image, filename = "";

							if (resp.error) {
								status.style.display = "inline";
								status.style.className = "error";
								status.textContent = resp.error;
							}
							else if (resp.status) {
								status.style.display = "inline";
								status.style.className = "status";
								status.textContent = resp.status;
							}

							if (resp.filename) {
								filename = resp.filename;
							}
							else {
								filename = file.name;
							}

							if (images && images.length) {
								for (var i = 0; i < images.length; i++) {
									editor.removeChild(images[i]);
								}
							}

							image = document.createElement("img");


				      image.src 					= resp.dataUri;
				      image.className 		= "zoomInDown";
				      image.style.display = "inline";
				      image.style.height 	= "16em";
				      // editor.style.width = "auto";

				      // console.log("file.name : " + file.name);
				      editor.innerHTML = Mustache.render(template, {"title" : file.name, "filename" : filename});

				      var
				      	submit = document.getElementById("form-image-editor-submit"),
				      	titleField = document.getElementById("form-image-editor-title");

				      submit.addEventListener("click", onImageSave);

				      if (titleField && titleField instanceof HTMLInputElement) {
				      	titleField.focus();
				      	titleField.select();
				      }

			// <legend>New Image</legend>
			// <input type="text" name="title" value="" />
			// <input type="text" name="message" value="" />
			// <input type="text" name="description" value="" />
			// <input type="text" name="time" value="" />
			// <input type="text" name="date" value="" />
			// <input type="text" name="tags" value="" />


				      if (editor.firstChild) {
				      	console.log("editor.insertBefore");
				      	// editor.appendChild(image);
					      editor.insertBefore(image, editor.firstChild);
				      }
				      else {
				      	console.log("editor.appendChild");
				      	editor.appendChild(image);
				      }
							var
								modalForm = document.getElementById("modal-one");

							console.log("toggling modal");
							if (modalForm.classList.contains("active")) {
								modalForm.classList.remove("showing");
								setTimeout(function(){
									modalForm.classList.toggle("active");
								}, 1000);
							}
							else {
								modalForm.classList.add("active");
								setTimeout(function(){
									modalForm.classList.toggle("showing");
								}, 1);
							}
				    }
				    else {
				    	// throws to window.onerror, where we trap and redirect to server
				    	throw "Xhr error: " + this.status;
				    }
			    }; // onload


		  	if (progress) {
		  		progress.style.visibility = "visible";
		  	}

		  	if (!file) {
		  		console.error("No file selected!");
		  		return;
		  	}

			  console.log("file : ", file);
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

		document.querySelector('#video-upload').addEventListener('change', function(e) {
			  var
			  	progress = document.getElementById("video-upload-progress"),
			  	file 	= this.files[0],
			  	data 	= new FormData(),
			  	xhr 	= new XMLHttpRequest(),

			  	// @todo Maybe some visual feedback on upload progress
			  	onprogress = function(e) {
				    if (e.lengthComputable) {
				      var
				      	percentComplete = (e.loaded / e.total) * 100;

				      if (progress) {
				      	progress.value = percentComplete;
				      }
				      console.log(percentComplete + '% uploaded');
				    }
				    else {
				    	console.error("Length not computable!");
				    }
				  },

				  /**
				   * When video link is returned from server
				   * @return {void}
				   */
				  onload = function() {
				    if (this.status == 200) {
				    	console.log("uploaded");
				    }
				    else {
				    	// throws to window.onerror, where we trap and redirect to server
				    	throw "Xhr error: " + this.status;
				    }
			    };

		  	if (progress) {
		  		progress.style.visibility = "visible";
		  	}

			   // populate formdata
			  data.append("video-upload", file);
			  data.append("username", "<?php echo $_SESSION['username'];?>");
			  data.append("postId", pi.uuid());

			  xhr.upload.onprogress = onprogress;
			  xhr.onload = onload;

			  xhr.open('POST', 'admin/video-upload.php', true);
			  xhr.send(data);

			}, false);
	</script>


	<script type="text/javascript">



		/**
		 * Just to show/hide the toolbar
		 */


		function hideToolbar() {
		 	var
		 		toolbar = document.getElementById("toolbar");

		 	if (toolbar && toolbar.classList) {
		 		console.log("removing class 'active'");
			 	toolbar.classList.remove("active");
		 	}
		 	else {
		 		console.error("smthn wrng in hideToolbar()");
		 	}
		 	return false;
		}


		document.addEventListener("DOMContentLoaded", function () {
			var
				visible 	= false,
				tabs 			= document.querySelectorAll("#toolbar li"),
				handlers 	= [];

			function onTabClicked(e) {
				var
					footers = document.getElementsByTagName("footer"),
					toolbar = document.getElementById("toolbar");

				// console.log("onTabClicked() this : " + this, this);

				if (this && this.classList && this.classList.contains("closebutton")) {
					if (toolbar && toolbar.classList.contains("active")) {
						console.log("deactivating toolbar");
						toolbar.classList.remove("active");
						// toolbar.classList.remove("active");
						e.preventDefault();
						e.stopPropagation();
						return true;
					}
				}

				if (toolbar && toolbar.classList.contains("active")) {
					// console.log("toolbar already active : " + this, this);
					// toolbar.classList.remove("active");
					// nada
				}
				else {
					console.log("activating toolbar : " + this, this);
					visible = true;
					// if (handlers && handlers.length) {
					// 	for (var i = 0; i < handlers.length; i++) {
					// 		console.log("removing event listener : " + i, handlers[i]);
					// 		handlers[i].removeEventListener("click", onTabClicked);
					// 	}
					// }
					toolbar.classList.add("active");
				}
				e.stopPropagation();

			}

			if (tabs && tabs.length) {
				for (var i = 0; i < tabs.length; i++) {
					if (handlers.indexOf(tabs[i]) == -1 ) {
						tabs[i].addEventListener("click", onTabClicked);
						handlers.push(tabs[i]);
					}
					else {
						console.error("already has a handler : " + tabs[i], tabs[i]);
					}
				}
			}

		});

		</script>


	<script type="text/javascript">


	document.addEventListener("DOMContentLoaded", function () {

		var
			scene1 			= "amfiet",
			scene2 			= "vindfruen",
			concerts 		= [],
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



		function hideDayCounters() {
			var
				counters = document.getElementsByClassName("day-counter");

			if (counters && counters.length) {
				for (var i = 0; i < counters.length; i++) {
					counters[i].style.display = "none";
				}
			}
		}




		function updateTime(scene) {
			if (typeof scene == "undefined") {
				updateTime("amfiet");
				updateTime("vindfruen");
				return;
			}

			var
				scene = scene || false,

				// a and b are javascript Date objects
				result 	= "",
				then 		= getNextConcert(scene),

				// set clock forwards by n weeks
				now 		= new Date(Date.now() + (3 * 7 * 24 * 60 * 60 * 1000)),
				days, hours, minutes,
				remainingDays 		= scene ? document.getElementById(scene + "-days") : document.getElementById("amfiet-days"),
				remainingHours 		= scene ? document.getElementById(scene + "-hours") : document.getElementById("amfiet-hours"),
				remainingMinutes 	= scene ? document.getElementById(scene + "-minutes") : document.getElementById("amfiet-minutes"),
				remainingSeconds 	= 0,
				dateDiff = function(a, b) {
				  var 
				  	utc1 = Date.UTC(a.getFullYear(), a.getMonth(), a.getDate(), a.getUTCHours(), a.getMinutes()),
				  	utc2 = Date.UTC(b.getFullYear(), b.getMonth(), b.getDate(), b.getUTCHours(), b.getMinutes());

				  return (utc2 - utc1);
				};


			remainingSeconds = Math.round(dateDiff(now, then)/1000);

			days 		= Math.floor(remainingSeconds / (60 * 60 * 24));
			hours 	= Math.floor((remainingSeconds / (60 * 60)) % 24);
			minutes = Math.floor((remainingSeconds/60) % 60);

			// kind of pedantic, but still. don't touch the DOM unless you have to
     	if (remainingDays.textContent != days) {
     		remainingDays.textContent = days;
     		if (days === 0 && hours < 23) {

     			// removes the row displaying days left
     			hideDayCounters();
     		}
     	}
     	if (remainingHours.textContent != hours) {
     		remainingHours.textContent = hours;
     	}
     	if (remainingMinutes.textContent != minutes) {
     		remainingMinutes.textContent = minutes;
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

					// console.log("time : " + time);
					// year, month, day, hours, minutes, seconds, milliseconds);
					return new Date(2015, 7, 11 + parseInt(day, 10), parseInt(time[0], 10), parseInt(time[1], 10), 0, 0);

				},

				format  = function (data) {
					var
						today		= Date.now(),
						arr 		= [],
						result 	= {},
						data 		= data || null;

					if (data) {
						arr = data["td"];
						// console.log("arr : " + arr);
						result["scene"] = arr[3].trim().toLowerCase();
						result["time"] = arr[1];

						if ([scene1, scene2].indexOf(result["scene"]) > -1) {
							var
								sort = arr[4];
							result["day"] 	= sort["span"][0];
							// console.log("Date added : " + sort["span"][1]);
							// result["date"] 	= sort["span"][1];
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



			// from HTML scraped off of http://oyafestivalen.no/program/
			if (program && program.program && program.program.tbody && program.program.tbody.tr) {
				var
					tr = program.program.tbody.tr;

				while(concerts.pop());

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
					// console.log(scene1 + " : " + concerts[i]["artist"]);
					program1.appendChild(element);
				}
				else if (concerts[i]["scene"] == scene2) {
					// console.log(scene2 + " : " + concerts[i]["artist"]);
					program2.appendChild(element);
				}
				else {
					console.error("No scene matches '" + concerts[i]["scene"] + "'");
					// nada
				}
			}

			// getNextConcert();

			// because why not
			return i;
		}; // function updateProgram()


		/**
		 * Get full date of next concert
		 *
		 * @param  {str} [scene] 	Which scene to check. Optional.
		 * @return {int}       		Unix timestamp
		 */
		function getNextConcert(scene, dummyDate) {
			var
				scene 	= scene || null,
				program = program || null,
				result 	= null,
				dummyDate = dummyDate || new Date(Date.now() + (3 * 7 * 24 * 60 * 60 * 1000)),
				earliest = Date(0);

			for (var i in concerts) {
				if (scene && concerts[i]["scene"] != scene) {
					// console.log("skipping: " + concerts[i], concerts[i]);
					continue;
				}
				if (result === null) {
					// console.log("Scene : " + scene + ", earliest : " + concerts[i]["date"]);
					earliest = concerts[i]["date"];
					if (earliest < dummyDate) {

						// already started, on to the next one
						continue;
					}

					// console.log("returning earliest : " + earliest, earliest);
					return earliest;
				}
			}
			return earliest;
		}


		updateProgram();
		updateTime("vindfruen");
		updateTime("amfiet");

		setInterval(updateTime, 5000);

		// updateProgram();

	});

	</script>

	<script type="text/javascript">

	var
		data = window.data || {},
		previousTime = "",
		currentItem = document.querySelectorAll(".item.playing")[0];

	// console.log("data : " + data, data);


	function rotatePlaylist() {
		var
			upnext = null,
			currentQueue = document.querySelectorAll("#current ul")[0], 
			currentItem = document.querySelectorAll(".item.playing")[0],
			nextQueue = document.querySelector("#next-items > ul"),
			queuedItems = document.querySelectorAll("#queue ul > li"),
			nextItems = document.querySelectorAll("#next-items ul > li");


		if (queuedItems && queuedItems.length) {
			upnext = queuedItems[0];
			// console.log("queued: " + upnext, upnext);
		}
		else if (nextItems && nextItems.length) {
			upnext = nextItems[0];
			// console.log("up next: " + upnext, upnext);
		}
		else {
			upnext = nextItems.length;
			// console.log("no more items: " + upnext, upnext);
		}

				// <section id="queue">
				// 	<div class="section-title">Queued</div>
				// 	<ul>
				// 		<li id="item-1" class="item">Food report #32 


		if (upnext && upnext.classList) {
			currentItem.classList.remove("playing");
			// if (nextQueue) {
			// 	nextQueue.appendChild(currentItem);
			// }
			currentQueue.replaceChild(upnext, currentItem);
			upnext.classList.add("playing");
			// console.log("rotatePlaylist : " + upnext);
		}
		else {
			console.error("no more items in rotatePlaylist()!");
		}

	}



	function updatePlaylist() {
		var
			item,
			now = new Date(),
			currentTime = null,
			currentItem = document.querySelectorAll(".item.playing"),

			subtractSecond = function (timeStr) {
				var
					parts = timeStr.split(":"),
					minutes = seconds = 0;

				if (parts && parts.length >= 2) {
					minutes = parseInt(parts[0], 10);
					seconds = parseInt(parts[1], 10);
				}
				else {
					console.error("no parts!");
				}

				if (seconds == 0) {
					if (minutes > 0) {
						minutes--;
						seconds = 60;
					}
					else {
						console.log("rotatePlaylist()");
						rotatePlaylist();
					}
				}
				seconds--;

				return pi.strPad(minutes, 1, "0", true) + ":" + pi.strPad(seconds, 2, "0", true);
			};


		if (currentItem && currentItem.length) {
			item = currentItem[0];
			currentTime = item.getElementsByClassName("duration")[0];
			if (previousTime != currentTime.textContent) {
				// console.log("previous time: " + previousTime);
				previousTime = currentTime.textContent;
				// console.log("current time: " + currentTime.textContent);
				currentTime.textContent = subtractSecond(currentTime.textContent);
			}
		}
		else {
			console.error("no currentItem !");
		}

	}


	setInterval(updatePlaylist, 1000);

	</script>


<style type="text/css">

	.btn {
	  position: relative;
	  display: inline-block;
	  padding: 8px 15px;
	  background: #428bca;
	  border: #357ebd solid 1px;
	  border-radius: 3px;
	  color: #fff;
	  font-size: 14px;
	  text-decoration: none;
	  text-align: center;
	  min-width: 60px;
	  transition: color .1s ease;
	  /* top: 40em;*/
	}

	.btn:hover {
	  background: #357ebd;
	}

	.btn.btn-big {
	  font-size: 18px;
	  padding: 15px 20px;
	  min-width: 100px;
	}

	.btn-close {
	  color: #aaaaaa;
	  font-size: 30px;
	  text-decoration: none;
	  position: absolute;
	  right: 5px;
	  top: 0;
	  cursor: pointer;
	}

	.btn-close:hover {
	  color: #919191;
	}

	.modal-body {
	  padding: 20px;
	}

	.modal-dialog {
		color: #272822;
	 	position: relative;
	  background: #fefefe;
	  border: #333333 solid 1px;
	  border-radius: 5px;
	  text-align: left;
	  /*position: fixed;*/
	  z-index: 11;
		width: 33em;
    margin: 10% auto;
  	/*top: 50%;*/
/*  	-webkit-transform: translateY(-25%);    
  	transform: translateY(-25%);    
*/  }

	.modal-header,
	.modal-footer {
	  padding: 10px 20px;
	}

	.modal-header {
	  border-bottom: #eeeeee solid 1px;
	}

	.modal-header h2 {
	  font-size: 20px;
	}

	.modal-footer {
	  border-top: #eeeeee solid 1px;
	  text-align: right;
	}


	.modal {
		position: fixed;
		display: none;
		opacity: 0;
		min-height: 100%;
		min-width: 100%;
		top: 0;
		right: 0;
		color : #fff;
		background-color: rgba(0, 0, 0, 0.4);
		background: -moz-radial-gradient(center, ellipse cover,  rgba(255,0,59,0.5) 0%, rgba(55,0,255,0.21) 100%);
		background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,rgba(255,0,59,0.5)), color-stop(100%,rgba(55,0,255,0.21)));
		background: -webkit-radial-gradient(center, ellipse cover,  rgba(255,0,59,0.5) 0%,rgba(55,0,255,0.21) 100%);
		background: -o-radial-gradient(center, ellipse cover,  rgba(255,0,59,0.5) 0%,rgba(55,0,255,0.21) 100%);
		background: -ms-radial-gradient(center, ellipse cover,  rgba(255,0,59,0.5) 0%,rgba(55,0,255,0.21) 100%);
		background: radial-gradient(ellipse at center,  rgba(255,0,59,0.5) 0%,rgba(55,0,255,0.21) 100%);

		z-index: 4998;

		-webkit-transition: opacity 0.4s;
		transition: opacity 0.4s;

	}

	.modal .relative-wrapper {
		position: relative;
	}

	.modal.active {
		display: block;
	}

	.modal.showing {
		opacity: 1;
	}

</style>



	<div class="modal" id="modal-one" onclick="onModalClick(this);">
		<div class="relative-wrapper">
		  <div id="modal-dialog" class="modal-dialog" onclick="noClick();">
	    </div>
	  </div>
	</div>

<script type="text/javascript">


	function noClick(e) {
 		if (!e) {
	    if (window.event) {
	     	e = window.event;
	     }
	    else {
	     	return;
			}
 		}
		if (e && typeof e.preventDefault == "function") {
			e.preventDefault();
			e.stopPropagation();
		}
 		if (e.cancelBubble != null) e.cancelBubble = true;
		if (e.stopPropagation) e.stopPropagation();
		if (e.preventDefault) e.preventDefault();
		if (window.event) e.returnValue = false;
		if (e.cancel != null) e.cancel = true;
		// console.log("Clicked outside modal dialog. This : ", e);
	}


	function onModalClick(e) {
		hideModal();
		console.log("Clicked outside modal dialog. This : ", e);
	}
 	
	function showModal(figure) {
		var
			figure = figure || null,
			image = document.createElement("div"),
			modal = document.getElementById("modal-one");

		if (figure) {

			modal.classList.add("active");
			setTimeout(function() {
				modal.classList.add("showing");
			}, 1);			
		}
	}


	function hideModal() {
		var
			modal = document.getElementById("modal-one");

		modal.classList.remove("showing");
		setTimeout(function() {
			modal.classList.remove("active");
		}, 400);
	}

</script>


	<script type="text/javascript">
		document.addEventListener("DOMContentLoaded", function() {
			updateImages();
		});
	</script>


<script type="text/javascript">

	/**
	 * Load data from JSON files
	 */
	document.addEventListener("DOMContentLoaded", function() {
		var
			data = window.data || {};


		/**
		 * load settings
		 */
		pi.xhr.get("assets/php/data/settings.json", function(json) {
			if (json) {
				data.settings = JSON.parse(json);
				data.session = {
					user : "<?php print($user); ?>"
				};

				pi.log("settings : " + data.settings, data.settings);
			}

		}, pi.log);

		/**
		 * load images
		 */
		pi.xhr.get("admin/data-load.php?file=images.json", function(json) {
			if (json) {
				// console.log("Received JSON: " + json);
				try {
					data.images = JSON.parse(json);
					console.log("loaded " + data.images.length + " images.");
					// lazy-load(ish)
					setTimeout(updateImages, 1000);
				}
				catch (e) {
					console.error("Exception: " + e);
				}
			}
		}, pi.log);

		if (!window.data) {
			window.data = data;
		}
	});
</script>

<script type="text/javascript">

	/**
	 * Load screens from SQLite database
	 */
	document.addEventListener("DOMContentLoaded", function() {
		var
			screens = {},
			data = window.data || {};

		/**
		 * load settings
		 */
		pi.xhr.get("assets/php/screens.php", function(json) {
			if (json) {
				screens = JSON.parse(json);
				// pi.log("screens : " + json);
			}
		}, pi.log);



		if (!window.data) {
			window.data = data;
		}
	});
</script>


<?php 
	if (isset($DEBUG)) {
		echo $DEBUG;
	}
?>
	<script src="assets/js/buttons.js"> </script>
</body>
</html>