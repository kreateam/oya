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

	<!-- <link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'> -->
	<link href='http://fonts.googleapis.com/css?family=Roboto:400,100,100italic,300,400italic,500,700,900' rel='stylesheet' type='text/css'>
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

		 	-webkit-animation-duration: .4s;
		  			 	animation-duration: .4s;

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
			-webkit-transition-duration: .3s;
							transition-duration: .3s;
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
			/*background: rgb(39,40,34);*/
			/*background: #52534E;*/
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

		.blur {
		  -webkit-filter: blur(4px);
		  -webkit-filter: -webkit-blur(4px);
		  		-ms-filter: blur(4px);
							filter: blur(4px);
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

    iframe:hover {
			opacity: 0.33;
    }

		header {
			padding-top: .4em;
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
			margin: 1px 0.5em;
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
			color: #EC008C;
		}


		.screen {
			/* to override the iframes's inline */
			display: block;

			/* initial state, because we want to fade in @load */
			opacity: 0.1;

			-webkit-transform: scale(0.5, 0.5);
							transform: scale(0.5, 0.5);

			-webkit-transition: border .4s, opacity .4s, -webkit-transform .2s; /* Safari */
			-webkit-transition: border .4s, opacity .4s, transform .2s;
							transition: border .4s, opacity .4s, transform .2s;

			pointer-events : none;
		}


		.screen.preview {
			opacity: 1;
			z-index: 4999999;
		}


		#playqueue {
			-webkit-transition: -webkit-filter 0.5s ease-out; 
	 				transition: -webkit-filter 0.5s ease-out; /* easeOutExpo */			
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
			/*background-color: #C21E29;*/
			background-color: #0080c3;

			text-align: right;
			-webkit-transition: background-color .4s;
			transition: background-color .4s;
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
			background-color: #0080c3;

			text-align: right;
			-webkit-transition: background-color .4s;
			transition: background-color .4s;
		}

		.left.next, .right.next {
			background-color: #C21E29;
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


		@media (max-width: 1080px) {
			body {
				font-size: 10px;
			}

			/* hide on mobile */
			header,
			footer,
			.pronomen,
			.screen,
			#now-playing,
			#toolbar,
			#amfiet-header, #vindfruen-header,
		  #program1, #program2 {
		  	display: none;
		  }

		  .right, .left {
		  	margin: 0;
		  	padding: 0;

		  	width: 50%;
		  	max-width: 50%;
		  	height: auto;
		  	min-height: 80px;
		  }
		  .middle {
		  	width: 100%;
		  	clear: both;
		  	position: relative;
		  }
		  #toolbar {
		  	font-size: 8px;
		  }
		  footer {
				width: 100%;
		  }

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

			font-family: Roboto, Helvetica, Lato, sans-serif;
			font-weight: 300;
			font-size: 66%;
			height: 32px;
			/*text-align: left;*/

			/* doesn't work ... why o whai */
			-webkit-transition: top .3s cubic-bezier(0.190, 1.000, 0.220, 1.000), height .3s cubic-bezier(0.190, 1.000, 0.220, 1.000), -webkit-filter 0.5s ease-out; 
			 				transition: top .3s cubic-bezier(0.190, 1.000, 0.220, 1.000), height .3s cubic-bezier(0.190, 1.000, 0.220, 1.000), -webkit-filter 0.5s ease-out; /* easeOutExpo */
		}

		#toolbar.active {
			height: 20em;
		}

		#toolbar .closebutton {
			background-color: #000;
			color: #fff;
			/*font-weight: 400;*/
			cursor: pointer;
			font-size: 100%;
		}


		#queue-duration {

			} 

		.queue-total {
			font-size: 66%;
			font-weight: 100;
		}


		div.images {
			display: block;
		}

		.screen-list-item, .template-selector-item {
			font-size: 14px;
			padding-left: 1em;
			padding-right: 1em;
			cursor: pointer;

			-webkit-transition: all 0.4s;
							transition: all 0.4s;
		}

		.screen-list-item:hover, .template-selector-item:hover {
			color: #fff;	
		/*font-weight: 400;*/
			background-color: #0080c3;
		}

		.template-selector-item-menu {
			color: #fff;
			font-size: 14px;
			background-color: rgba(39,40,34,0.6);
			padding-left: 1em;
			padding-right: 1em;
			cursor: pointer;

			-webkit-transition: all 0.4s;
							transition: all 0.4s;
		}

		.template-selector-item-menu:hover {
			color: #fff;
			background-color: #ff0545;
		}

		#template-selector ul {
		  columns: 4;
		  -webkit-columns: 4;
	  	-moz-columns: 4;
	  }

		#screen-image-preview {
			height: 584px;
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
			// elem.style.opacity = 0.1;
		}

	</script>

	<?php

		/** Mustache templates  */
		include '../templates.html';
	?>

	<div id="wrapper" class="wrapper">
		<div id="amfiet" class="left">
				<h4>Amfiet</h4>
				<div class="coming-up">
					<h1 id="amfiet-header">Next</h1>
					<h2 id="amfiet-artist" class="artist">Razika</h2>
					<span class="pronomen"><strong>in</strong></span>
						<div class="day-counter">&nbsp;&nbsp;&nbsp;&nbsp;<span id="amfiet-days" class="fat"></span> days
						</div>
					<div class="hour-counter">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="amfiet-hours" class="fat"></span> hours</div>
					<div>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span id="amfiet-minutes" class="fat"></span> minutes</div>
				</div>

				<ul id="program1">
					
				</ul>
		</div>
		<div id="vindfruen" class="right">
				<h4>Vindfruen</h4>
				<div class="coming-up">
					<h1 id="vindfruen-header">Coming Up</h1>
					<h2 id="vindfruen-artist" class="artist">The Switch</h2>
					<span class="pronomen"><strong>in</strong></span>
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
			<section id="playqueue" class="content">
				<header>
					Play Queue
				</header>

				<section id="current">
					<div id="now-playing" class="section-title">Now playing</div>
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
					<div class="section-title">Queued <span id="queue-duration" class="queue-total"></span></div>
					<ul>
					</ul>
				</section>

				<section id="next-items">
					<div class="section-title">Next Items <span id="next-duration" class="queue-total"></span></div>
					<ul>
					</ul>
				</section>

				<section id="demo"></section>
			</section>



<script>
	
	/* playlist and stuff */

	document.addEventListener("DOMContentLoaded", function() {
		var
			playlist = {
				updated : 0,
				_data : null,
				_loaded : false,

				onupdate : function() {
					console.info("playlist updated!")
				},

				update : function() {
					var
						self = playlist;
					pi.xhr.get("assets/php/playlist.php", self.onload, console.error);
				},

				processItem : function(item) {
					var
						result = {};
					// json
					if (item && typeof item.data == "string") {
						try {
							var
								chunk = JSON.parse(item.data);
							for (var key in chunk) {
								item[key] = chunk[key];
							}
						}
						catch (e) {
							console.error(e);
						}
						return item;
					}
				},


				processItems : function(list) {
					var
						self = playlist;
					// json
					if (list && list.current && typeof list.current.data == "string") {
						var processed = self.processItem(list.current);
						if (processed) {
							list.current = processed;
						}
					}

					if (list && list.queue && list.queue.length) {
						for (var i = 0; i < list.queue.length; i++) {
							var processed = self.processItem(list.queue[i]);
							if (processed) {
								list.queue[i] = processed;
							}
						}
					}

					if (list && list.next && list.next.length) {
						for (var i = 0; i < list.next.length; i++) {
							var processed = self.processItem(list.next[i]);
							if (processed) {
								list.next[i] = processed;
							}
						}
					}
					return list;
				},

				onload : function(json) {
					var 
						self = playlist,
						_data = JSON.parse(json) || null;

					if (_data) {
						if (self._loaded === false) {
							self._loaded = Date.now();
							if (_data['playlist'] && _data['status'] && _data['status'] == "ok") {
								if (!window.data) {
									window.data = {};
								}
								console.info("playlist loaded: " + self._loaded, _data.playlist);

								window.data.playlist = self.processItems(_data.playlist);
								console.info("calling self.render()");
								self.render();
							}
						}
					}
					else {
						console.error("No playlist data");
					}
				},

				load : function(list, callback, onerror) {
					var
						result = null,
						list = list || "assets/php/playlist.php";

					// console.info("loading playlist");

					pi.require("xhr", false, false, function() {
						// console.log("Sending xhr");
						result = pi.xhr.get(list, callback, onerror);
					});

				},

				/**
				 * Render playlist to DOM
				 */
				render : function () {
					var
						playlist = window.data.playlist || null,
						currentTmpl = document.getElementById("playlist-current-template").innerHTML,
						queueTmpl 	= document.getElementById("playlist-queue-template").innerHTML,
						nextTmpl 		= document.getElementById("playlist-next-template").innerHTML,
						current = document.getElementById("current"),
						queue 	= document.getElementById("queue"),
						next 		= document.getElementById("next-items");

					if (!playlist) {
						console.error("No playlist in window.data !");
						return false;
					}
					else {
						// console.info("Ready to render : " + playlist, playlist);
					}
					if (current && currentTmpl) {
						// console.info("rendering current : " + playlist.current, playlist.current);
						current.innerHTML = Mustache.render(currentTmpl, playlist.current);
					}
					if (queue && queueTmpl) {
						if (playlist.queue && playlist.queue.length) {
							queue.innerHTML = Mustache.render(queueTmpl, playlist.queue);
							// console.info("rendering queue : ", playlist.queue);
						}
						else {
							console.info("queue is empty : ", playlist.queue);
						}
					}
					if (next && nextTmpl) {
						if (playlist.next && playlist.next.length) {
							// console.info("rendering next-items: ", playlist.next);
							next.innerHTML = Mustache.render(nextTmpl, playlist.next);
						}
						else {
							console.info("next-items is empty : ", playlist.next);
						}
					}
					return false;
				}
			}; // playlist


			// heigh ho
			playlist.load(null, playlist.onload, pi.log);


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
	input[type="text"],
	input[type="number"] {
	  display: inline-block;
	  margin: 0;
	  width: 96%;
	  font-family: 'Roboto', sans-serif;
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
	input[type="number"]:focus,
	input[type="number"].focus,
	input[type="text"]:focus,
	input[type="text"].focus {
	  outline: none;
	  border: solid 1px rgba(38,188,244,.8);

  	box-shadow: 0 0 15px 5px rgba(39,40,34,.3);
	}

 	#image-search {
 		width: 20em;
 	}

 	#screen-search {
 		width: 40em;
 	}


	.error {
		display: none;
		background-color: #C21E29;
		/*background-color: #ff0545;*/
		padding: 0.2em 0.5em;
		color: #fff;
	}

	.status {
		display: none;
		background-color: #0080c3;
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

	#screen-preview ul {
	  columns: 4;
	  -webkit-columns: 4;
  	-moz-columns: 4;
  }


	#screen-preview li {
		display: block;
	}


	/* the spans that hold individual preview images */
	.preview-image {	
    display : inline-block;
    /*width: 25%;*/
		/*margin: .2em .8em;*/

	}

	.preview-image figcaption {
		font-size: 0.75em;
	}

	label {
	  font-weight: 300;
	  font-size: 75%;
	}

	#toolbar {
		font-weight: 300;
	}

	.searchsymbol {
		position: relative;
		opacity: 0.78;
		height: 1em;
		top: 0.2em;
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
            	<img src="assets/img/search.svg" height="24" class="searchsymbol">
            	<input type="text" name="image-search" id="image-search" />
            	<progress id="form-upload-progress" max="100"></progress>
            	<span id="form-status" class="status"></span>
							<div>
	            	<div id="image-editor" class="editor"></div>
	            	<div id="image-preview" class="preview"></div>
							</div>
            </div>
	        </li>
	        <li>
            <input type="radio" name="tabstrip-0" id="tabstrip-0-2" />
            <label for="tabstrip-0-2">Screens</label>
            <div class="screens">
            	<img src="assets/img/search.svg" height="24" class="searchsymbol">
            	<input type="text" name="screen-search" id="screen-search" />
							<div>
	            	<div id="screen-preview" class="preview"></div>
							</div>
            </div>
	        </li>
	        <li>
            <input type="radio" name="tabstrip-0" id="tabstrip-0-3" />
            <label for="tabstrip-0-3">Instagram</label>
            <div id="instagram" class="instagram">
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
	        <li>
            <input type="radio" name="tabstrip-0" id="tabstrip-0-4" />
            <label for="tabstrip-0-4">Video</label>
            <div class="videos">
            	<input type="file" name="video-upload" id="video-upload" accept="video/*" />
            	<progress id="video-upload-progress" max="100"></progress>
							<div>
	            	<div id="video-preview" class="preview"></div>
							</div>
            </div>
	        </li>
	        <li class="closebutton">
            <input type="radio" name="tabstrip-0" id="tabstrip-0-5" />
            <label for="tabstrip-0-5" class="closebutton">×</label>
	        </li>
		    </ul>
			</div>
			</footer>
		</div>

	</div>

	<iframe id="screen1" class="screen" scrolling="no" src="player.php" width="512" height="704" onload="iframeloaded(this)"></iframe>
	<iframe id="screen2" class="screen" scrolling="no" src="player.php" width="672" height="384" onload="iframeloaded(this)"></iframe>

	<script type="text/javascript">

		var 
			previewProto = {
				active : false,
				current : null,
				screen1 : document.getElementById("screen1"),
				screen2 : document.getElementById("screen2"),
				initial : {
					src1 : null,
					src2 : null
				},

				init : function () {
					this.initial.src1 = this.screen1.src;
					this.initial.src2 = this.screen2.src;
				}
			}; // preview

		previewProto.init();
		window.data = window.data || {};
		window.data.preview = previewProto;


		function enterPreviewMode(previewItem) {
			var
				fieldsets = document.getElementsByTagName("fieldset"),
				preview = window.data.preview;

			if (!preview.screen1.src) {
				alert("error : no previous src for preview screens");
				return false;
			}
			// console.info("previewItem : " + previewItem, previewItem);
			preview.active = true;
			preview.current = previewItem;
			preview.initial.src1 = preview.screen1.src;
			preview.initial.src2 = preview.screen2.src;
			preview.screen1.classList.add("preview");
			preview.screen2.classList.add("preview");
			addFieldsetChangeListeners();

		}


		function exitPreviewMode(previewItem) {
			var
				preview = window.data.preview;

			console.info("previewItem : " + previewItem, previewItem);
			if (!preview.initial.src1) {
				alert("error : no previous src for preview screens");
				return false;
			}
			preview.screen1.src = preview.initial.src1;
			preview.screen2.src = preview.initial.src2;
			preview.screen1.classList.remove("preview");
			preview.screen2.classList.remove("preview");
			preview.current = null;
		}


		function updatePreviews(data) {
			var 
				contentframe1, contentframe2, tmpl,
				previews = [],
				iframes = null,
				preview = window.data.preview || null,
				data = data || null;
			if (!data || !preview) {
				console.error("either param data is null, or window.data.preview is null.")
				return false;
			}
			if (!preview.current) {
				console.error("No current template! Aborting update...");
				return false;
			}
			else {
				// console.info("preview.current : " + preview.current, preview.current);
			}

			// if we already have a handle to the contentElement within the iframe's body,
			// and if the handle is still valid
			if (preview.contentElements && preview.contentElements.length >= 2 && preview.contentElements[0].contentDocument) {
				contentframe1 = preview.contentElements[0];
				contentframe2 = preview.contentElements[1];
			}
			else {
				iframes = document.querySelectorAll("iframe");
				if (iframes && iframes.length) {
					for (var i = 0; i < iframes.length; i++) {
						previews.push(iframes[i].contentDocument || iframes[i].contentWindow.document);
					}
				}
				if (previews.length == 2) {
					previews[0] = previews[0].getElementById("contentframe");
					previews[1] = previews[1].getElementById("contentframe");
					// contentframe1 = previews[0];
					// contentframe2 = previews[1];
					if (window.data.preview) {
						window.data.preview.contentElements = previews;
					}
					else {
						console.error("No preview object in window.data!");
					}
				}
				// console.info("data : " + JSON.stringify(data));
				// console.info("tmpl : " + tmpl);
				// console.info("previews : " + previews, previews);
			}

			contentframe1 = preview.contentElements[0];
			contentframe2 = preview.contentElements[1];

			if (contentframe1) {
				var 
					domdoc = contentframe1.contentDocument || contentframe1.contentWindow.document,
					domwin = contentframe1.contentWindow;
				// console.info("Rendering : contentframe1, data = ", data);
				if (data.statustext && typeof window.data.preview.screen1.contentWindow.setStatusText == "function") {
					console.info("updating statustext");
					window.data.preview.screen1.contentWindow.setStatusText(data.statustext);
				}

				domdoc.body.innerHTML = Mustache.render(preview.current, data)
			}
			else {
				console.error("contentframe1 is nought");
			}
			if (contentframe2) {
				var 
					domdoc = contentframe2.contentDocument || contentframe2.contentWindow.document,
					domwin = contentframe1.contentWindow;
				// console.info("Rendering : contentframe2");
				domdoc.body.innerHTML = Mustache.render(preview.current, data);
				if (data.statustext && typeof window.data.preview.screen2.contentWindow.setStatusText == "function") {
					console.info("updating statustext");
					window.data.preview.screen2.contentWindow.setStatusText(data.statustext);
				}
				// console.info("Result : " + domdoc.body.innerHTML);
			}
			else {
				console.error("contentframe2 is nought");
			}

			// console.log("contentframe1 : " + contentframe1, contentframe1);
			// console.log("contentframe2 : " + contentframe2, contentframe2);
			// contentframe1.innerHTML = Mustache.render(tmpl, data);
			// contentframe2.innerHTML = Mustache.render(tmpl, data);

		}

		function onStatusTextUpdate(e) {
			console.info("onStatusTextUpdate");
			onFieldsetChange(e);
		}


		function onFieldsetChange(e) {
			var
				fieldset = document.getElementsByTagName("fieldset"),
				hiddens = null,
				data = {};
			
			if (this && this.children && this.children.length) {

				// get all input elements with "hidden" attribute
				hiddens = this.querySelectorAll("input[type=\"hidden\"]");

				// console.log("this.children.length : " + this.children.length);
				// console.log("hiddens : " + hiddens, hiddens);
				for (var i = 0; i < this.children.length; i++) {
					if (this.children[i] instanceof HTMLInputElement || this.children[i] instanceof HTMLTextAreaElement) {
						// console.log(typeof this.children[i] + " : " + this.children[i], this.children[i]);
						// console.info("name : " + this.children[i].name);
						// console.info("value : " + this.children[i].value);
			    	data[this.children[i].name] = this.children[i].value;
					}
					else {
						// console.info(i + ": " + typeof this.children[i] + " " + this.children[i], this.children[i]);
					}
				}
			}
			else {
				console.error("No fieldsets found! this.children : " + typeof this.children, this.children);
				return;
			}

			updatePreviews(data);
			// console.info("onFieldsetChange: " + e, e);
			// console.info("this.children: " + this.children, this.children);
			// console.info("data: " + data, data);
		}

		function addFieldsetChangeListeners(e) {
			var
				fieldsets = document.getElementsByTagName('fieldset');

			if (fieldsets && fieldsets.length) {
				for (var i = 0; i < fieldsets.length; i++) {
					// fieldsets[i].addEventListener("change", onFieldsetChange);
					fieldsets[i].addEventListener("input", onFieldsetChange);
					// fieldsets[i].onChange();
				}
			}
		}



	</script>


	<script type="text/javascript">


		function reloadScreens () {
			var
				url = "assets/php/screens.php";

			console.info("getting screens...");
			pi.xhr.get(url, function(json) {
				var
					data = null;

				try {
					data = JSON.parse(json);
					if (!window.data) {
						window.data = {};
					}
					if (data && data.screens && data.screens.length) {
						for (var i = 0; i < data.screens.length; i++) {
							if (typeof data.screens[i]['data'] == "string") {
								// console.info("new screen : " + data.screens[i], data.screens[i]);
								try {
								var
									obj = JSON.parse(data.screens[i]['data']);
								}
								catch (e) {
									console.error(e);
								}

									for (var key in obj) {
										if (typeof data.screens[i][key] == "undefined") {
											data.screens[i][key] = obj[key];
										}
									}
							}
						}
						window.data.screens = data.screens;
						console.info("updating screen list afte reload");
						updateScreenList();
					}
				}
				catch(e) {
					console.error("Unable to parse screen list JSON : " + e);
				}

			}, pi.log); // pi.xhr.get()

		}



		/**
		 * global support function
		 * @param  {array|null} images [description]
		 * @return {[type]}        [description]
		 */
		function updateScreens(screens) {
			var
				screen,
				screens = screens || window.data.screens || [],
		    screenSearch = document.getElementById("screen-search"),
				template = document.getElementById("screen-list-item-template").innerHTML,
				previews = document.getElementById("screen-preview"),
				fragment = document.createDocumentFragment(),
				ul = document.createElement("ul");

			if (!screens || typeof screens.length != "number") {
				console.error("no screens!");
				return;
			}
	
			previews.innerHTML = "";

			// fragment.appendChild(document.createElement("ul"));

			for (var i = screens.length-1; i >= 0; i--) {
				li = document.createElement("li");
				screen = document.createElement("span");
				screen.className = "preview-screen";
				// console.log ("screen : ", screens[i]);
				screen.innerHTML = Mustache.render(template, screens[i]);
				li.appendChild(screen);
				ul.appendChild(li);
			}

			fragment.appendChild(ul);

			previews.appendChild(fragment);

			if (screenSearch && screenSearch.value) {
				// console.log("highlighting : " + screenSearch.value);
				highlight(previews, screenSearch.value);
			}
		}

		document.addEventListener("DOMContentLoaded", function () {
			var
				result = null,
				screenSearch = document.getElementById("screen-search"),
				screenList   = document.getElementById("screen-preview");

			screenSearch.addEventListener("input", function(e) {
				if (this && this.value) {
					result = fuzzyMatchScreens(this.value);
					updateScreens(result);
				}
				else {
					// no search term, so show everything
					updateScreens();
					// suppress
				}

			});

			function fuzzyMatchScreens(input) {
			  var
  				screens = window.data.screens,
			  	thorough = input.toString().length > 5,
			  	reg = new RegExp(input.split('').join('\\w*').replace(/\W/, ""), 'i');

			  if (!screens) {
			  	return false;
			  }

			  return screens.filter(function(screen) {
	
			  // console.log("screen : " + screen, screen);
		    if (screen.title && screen.title.match(reg)) {
			      return screen;
			    }
			    else if (screen.tags && screen.tags.match(reg)) {
			    	return screen;
			    }
			    else if (screen.filename && screen.filename.match(reg)) {
			    	return screen;
			    }
			    else if (screen.name && screen.name.match(reg)) {
			    	return screen;
			    }
			    else if (thorough) {
			    	if (screen.description && screen.description.match(reg)) {
			    		return screen;
			    	}
			    }
			    else {
			    	return false;
			    }
			  });
			}

		});



		function getScreen(index) {
			var
				screens = window.data.screens || null,
				result = null;
			if (typeof index == "string") {
				index = parseInt(index, 10);
			}

			if (isNaN(index)) {
				return null;
			}

			if (screens && screens.length) {
				for (var i = 0; i < screens.length; i++) {
					if (screens[i]['id'] == index) {
						result = screens[i];
						break;
					}
				}
			}
			return result;
		}


		function editScreen(index) {
			var
				tpl = null,
				screen = null,
				index = index || null;

			if (index === null) {
				console.error("No index parameter in editScreen()");
				return false;
			}
			if (typeof index != "number") {
				index = parseInt(index, 10);
			}

			screen = getScreen(index);
			tpl = screen['filename'] ? screen['filename'] : null;
			console.info("editing screen: " + index, screen);

			var
				tmpl = getTemplate(tpl),
				data = screen,
				previews = [],
				iframes = document.querySelectorAll("iframe"),
				form = getForm(tpl),
    		editor  = document.getElementById("modal-dialog"),
	      images 	= editor.getElementsByTagName("img"),
	      status  = document.getElementById("form-status"),
				image, filename = "";


			if (data.filename) {
				filename = data.filename;
			}
			else {
				filename = data.name;
			}

			if (iframes && iframes.length) {
				for (var i = 0; i < iframes.length; i++) {
					previews.push(iframes[i].contentDocument || iframes[i].contentWindow.document);
					// var iframe = document.getElementById('iframeId');
					// var innerDoc = iframe.contentDocument || iframe.contentWindow.document;
				}
			}
			if (previews.length == 2) {
				previews[0] = previews[0].getElementById("contentframe");
				previews[1] = previews[1].getElementById("contentframe");
				if (window.data.preview) {
					window.data.preview.contentElements = previews;
				}
				else {
					console.error("No preview object in window.data!");
				}
			}
			// console.info("data : " + JSON.stringify(data));
			// console.info("tmpl : " + tmpl);
			// console.info("previews : " + previews, previews);

			// remove any images from previous form usage
			if (images && images.length) {
				for (var i = 0; i < images.length; i++) {
					if (images[i].parentNode == editor) {
						editor.removeChild(images[i]);
					}
				}
			}

      // console.log("rendering : " + form, data);
      editor.innerHTML = Mustache.render(form, data);


      var
      	submit = document.getElementById("form-screen-submit"),
      	titleField = document.getElementById("form-screen-title");

      if (submit && typeof submit.addEventListener == "function") {
	      submit.addEventListener("click", onScreenSave);
      }
      
      enterPreviewMode(tmpl, data);
      console.info("updating previews!!!!");
      updatePreviews(data);

      if (titleField && titleField instanceof HTMLInputElement) {
      	titleField.focus();
      	titleField.select();
      }

			var
				modalForm = document.getElementById("modal-one");

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
	    // console.info("AFTER all the code");

		}


		function updateScreenList() {
			var
				screen, screens,
				screenPreview = document.getElementById("screen-preview"),
				itemTemplate = document.getElementById("screen-list-item-template").innerHTML,
				li, obj,
				ul = document.createElement("ul");

			// console.log("updateTemplateList()!");

			if (window.data && window.data.screens && window.data.screens.length) {
				screens = window.data.screens;
				for (var i = 0; i < screens.length; i++) {
					obj = !!screens[i].json ? screens[i].json : screens[i];
					// console.info("rendering : ", obj);
					li = document.createElement("li");
					li.innerHTML = Mustache.render(itemTemplate, obj);
					ul.appendChild(li);
				}
				screenPreview.appendChild(ul);
				return i;
			}
			else {
				console.error("No screens!");
			}
			return null;
		}



		function onScreenSave(e) {
    	var
    		fields,
    		xhr = new XMLHttpRequest(),
    		fieldset = document.getElementById("form-screen-fieldset").children,
    		formData = new FormData(fieldset),
	      status  = document.getElementById("form-status"),
	      modalForm = document.getElementById("modal-one");


			// console.log("fieldset" + fieldset, fieldset);

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

    	xhr.open("POST", "assets/php/screens.php");
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
	    				preview = document.getElementById("screen-preview");

	    				console.info("reloading screens!");
	    				reloadScreens();
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



		function newFromTemplate(tpl) {

			var
				tmpl = getTemplate(tpl),
				data = getJson(tpl),
				form = getForm(tpl),
    		editor  = document.getElementById("modal-dialog"),
	      images 	= editor.getElementsByTagName("img"),
	      status  = document.getElementById("form-status"),
				image, filename = "";


			if (data.filename) {
				filename = data.filename;
			}
			else {
				filename = data.name;
			}

			// console.info("data : " + JSON.stringify(data));
			// console.info("tmpl : " + tmpl);

			// remove any images from previous form usage
			if (images && images.length) {
				for (var i = 0; i < images.length; i++) {
					if (images[i].parentNode == editor) {
						editor.removeChild(images[i]);
					}
				}
			}

      // console.log("file.name : " + file.name);
      editor.innerHTML = Mustache.render(form, data);

      var
      	submit = document.getElementById("form-screen-submit"),
      	titleField = document.getElementById("form-screen-title");

      if (submit && typeof submit.addEventListener == "function") {
	      submit.addEventListener("click", onScreenSave);    	
      }
      enterPreviewMode(tmpl, data);
      console.info("updating previews!!!!");
      updatePreviews(data);

      if (titleField && titleField instanceof HTMLInputElement) {
      	titleField.focus();
      	titleField.select();
      }

			var
				modalForm = document.getElementById("modal-one");

			if (modalForm.classList.contains("active")) {
				modalForm.classList.remove("showing");
				setTimeout(function(){
					modalForm.classList.remove("active");
				}, 1000);
			}
			else {
				modalForm.classList.add("active");
				setTimeout(function(){
					modalForm.classList.add("showing");
				}, 1);
			}
	    // console.info("AFTER all the code");
		}; // newFromTemplate()


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
					// console.info("rendering : ", obj);
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
					if (template.name && template.name == name || template.filename && template.filename == name) {
						return template.filecontent;
					}
				}
			}
			return null;
		}


		function getJson(name) {
			var
				templates, template,
				name = name || "default";

			if (window.data && window.data.templates && window.data.templates.length) {
				templates = window.data.templates;
				for (var i = 0; i < templates.length; i++) {
					template = templates[i];
					if (template.name && template.name == name || template.filename && template.filename == name) {
						if (template.json) {
							// console.info("returning : " + template.json, template.json);
							return template.json
						}
						else {
							// console.info("returning : " + template, template);
							return template;
						}
					}
				}
			}
			else {
				console.info("No templates!");
			}
			console.info("returning NULL");
			return null;
		}


		function getForm(name) {
			var
				templates, template,
				name = name || "default";

			if (window.data && window.data.templates && window.data.templates.length) {
				templates = window.data.templates;
				for (var i = 0; i < templates.length; i++) {
					template = templates[i];
					if (template.name && template.name == name || template.filename && template.filename == name) {
						if (template.form) {
							// console.info("returning : " + template.form, template.form);
							return template.form
						}
						else {
							console.info("returning empty string");
							return "";
						}
					}
				}
			}
			else {
				console.info("No templates!");
			}
			console.info("returning NULL");
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
							updateTemplateList();
						}
					}
				};

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
					if (images[i].parentNode == editor) {
						editor.removeChild(images[i]);
					}
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
      	// console.log("editor.insertBefore");
      	// editor.appendChild(image);
	      editor.insertBefore(image, editor.firstChild);
      }
      else {
      	console.log("editor.appendChild");
      	editor.appendChild(image);
      }
			var
				modalForm = document.getElementById("modal-one");

			if (modalForm.classList.contains("active")) {
				unblur();
				modalForm.classList.remove("showing");
				setTimeout(function(){
					modalForm.classList.remove("active");
				}, 1000);
			}
			else {
				blurBackground();
				modalForm.classList.add("active");
				setTimeout(function(){
					modalForm.classList.add("showing");
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
					console.log(i + " : " + images[i].uuid);
					if (images[i].uuid == id) {
						
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
					// console.log(i + " : " + images[i].uuid);
					if (images[i].uuid == id) {
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


		function addUploadedImage(resp) {
			var
				resp = resp || null,
				image = {}, 
				images;

			if (!resp) {
				console.error("No param in addUploadedImage()");
				return false;
			}
			if (!window.data) {
				window.data = {};
			}
			if (!window.data.images) {
				window.data.images = {};
			}
			if (resp.uuid && findImage(resp.uuid)) {
				console.error("That image has already been added!");
				return false;
			}
			console.info("adding image now");

			if (resp.name && resp.type && resp.uri && resp.uuid && resp.filename) {
				// copy
				image.name = resp['name'] || "";
				image.type = resp['type'] || "";
				image.uri = resp['uri'] || "";
				image.user = resp['user'] || "";
				image.uuid = resp['uuid'] || "";

				// add
				image.title = resp.title || image.name;
				image.description = resp.description || "";
				image.tags = resp.tags || "";
				console.info("pushing");
				window.data.images.unshift(image);
				if (image.uuid) {
					setScreenImage(image.uuid);
				}
			}
			else {
				console.error("trying to add image without necessary properties");
				return false;
			}

		}


		function setScreenImage(id) {
			var
				image,
				screenForm 	= document.getElementById("form-screen-editor-image"),
				placeholder = document.getElementById("current-screen-image"),
				fieldset = document.getElementById("form-screen-fieldset");

			image = findImage(id);
			if (image) {
				placeholder.src = image.uri;
				console.log("Changing " + screenForm.value + " => ");
				screenForm.value = image.uri;
				console.log("to => " + screenForm.value);
				console.info("found image: " + image, image);
				hideImageSelector();
				console.info("fieldSetChange!!!!");
				if (fieldset) {
					onFieldsetChange.call(fieldset);
				}
				setTimeout(function(){
					placeholder.style.height = "16em";
				}, 1);
			}


			console.info("Setting screen image: " + id);
		}


		function showImageSelector() {
			var
				selector = document.getElementById("imageselector");

			if (imageselector) {
				console.info("showing imageselector");
				imageselector.classList.add("active");
				updateScreenImages();
			}
			else {
				console.error("no imageselector found!");
			}
		}

		function hideImageSelector() {
			var
				selector = document.getElementById("imageselector");

			if (imageselector) {
				imageselector.classList.remove("active");
			}

		}


		function instrumentScreenImageSelector() {
			var
				result = null,
				imageSearch = document.getElementById("screen-image-search"),
				imageList   = document.getElementById("screen-image-preview");


			if (!imageSearch || !imageList) {
				console.error("One or more missing elements!");
				return false;
			}
			else {
				console.info("Escaping from instrumentScreenImageSelector()");
				return false;
			}

			console.info("instrumenting screenImageSelector");
			imageSearch.addEventListener("input", function(e) {
				if (this && this.value) {
					result = fuzzyMatchImages(this.value);
					updateScreenImages(result);
				}
				else {
					// no search term, so show everything
					updateScreenImages();
				}
			});
		}

		/**
		 * global support function
		 * @param  {array|null} images [description]
		 * @return {[type]}        [description]
		 */
		function updateScreenImages(images) {
			var
				image,
				images = images || window.data.images || [],
		    imageSearch = document.getElementById("screen-image-search"),
				template = document.getElementById("screen-imagelist-item-template").innerHTML,
				previews = document.getElementById("screen-image-preview"),
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


		});


		document.addEventListener("DOMContentLoaded", function () {
			var
				result = null,
				imageSearch = document.getElementById("screen-image-search"),
				imageList   = document.getElementById("screen-image-preview");

			imageSearch.addEventListener("input", function(e) {
				if (this && this.value) {
					result = fuzzyMatchImages(this.value);
					updateScreenImages(result);
				}
				else {
					// no search term, so show everything
					updateScreenImages();
					// suppress
				}

			});


		});


		
	</script>

	<script type="text/javascript">


		function onImageSave(e) {
    	var 
    		fields,
    		xhr = new XMLHttpRequest(),
    		fieldset = document.getElementById("form-image-editor-fieldset").children,
    		formData = new FormData(fieldset),
	      status  = document.getElementById("form-status"),
	      modalForm = document.getElementById("modal-one");


			// console.log("fieldset" + fieldset, fieldset);

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
    			console.info("Success!");
    			// console.log("response: " + this.responseText);
    			hideModal();
  				try {
	    			var 
	    				response = JSON.parse(this.responseText),
	    				span = document.createElement("span"),
	    				preview = document.getElementById("image-preview"),
	    				template = document.getElementById("imagelist-item-template").innerHTML;

	    			// new image ?
	    			if (!findImage(response.uuid)) {
	    				console.info("unshifting uploaded Image");
							addUploadedImage(response);
	 	    			// window.data.images.push(response);
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
  			status.textContent = "Error.";
  			status.className = "error";
  			console.error("Error in xhr!");
    	};
    }




		/** @todo  This seems safe enough, no? */
		document.querySelector('#image-upload').addEventListener('change', function(e) {
			  var
			  	file 	= this.files[0],
			  	progress = document.getElementById("form-upload-progress"),
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
					      status  = document.getElementById("form-status"),
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

							console.info("Adding uploaded image");
							// addUploadedImage(resp);

							if (images && images.length) {
								for (var i = 0; i < images.length; i++) {
									if (images[i].parentNode == editor) {
										editor.removeChild(images[i]);
									}
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
				      console.info("updating previews!!!!");
				      updatePreviews(data);

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



		  	if (!file) {
		  		console.error("No file selected!");
		  		return;
		  	}

			  if (file && file.size) {
			  	if (window.data.settings['MAX_UPLOAD_SIZE'] && window.data.settings['MAX_UPLOAD_SIZE'] < file.size) {
			  		alert("File is too large ( > " + window.data.settings['MAX_UPLOAD_SIZE'] + ' bytes');
			  		return false;
			  	}
			  	console.info("Uploading file, " + file.size + " bytes");
			  }

		  	if (progress) {
		  		progress.style.visibility = "visible";
		  	}

			  console.log("file : ", file);
			   // populate formdata
			  data.append("image-upload", file);
			  data.append("username", "<?php echo $_SESSION['user'];?>");
			  data.append("uuid", pi.uuid());

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
			  data.append("uuid", pi.uuid());

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
						// console.log("deactivating toolbar");
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
					// console.log("activating toolbar : " + this, this);
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
					// console.info("Hiding day counters : " + i);
					counters[i].style.display = "none";
				}
			}
		}


		function setNextScene(scene) {
			var
				header1, header2,
				div, otherdiv,
				scene = scene || "amfiet";

			div = document.getElementById(scene);
			if (!div) {
				return false;
			}
			if (scene.toLowerCase() == "amfiet") {
				otherdiv = document.getElementById("vindfruen");
				header1 = document.getElementById("vindfruen-header");
				header2 = document.getElementById("amfiet-header");
			}
			else {
				otherdiv = document.getElementById("amfiet");
				header2 = document.getElementById("vindfruen-header");
				header1 = document.getElementById("amfiet-header");
			}

			div.classList.add("next");
			otherdiv.classList.remove("next");
			header1.textContent = "Next";
			header2.textContent = "Coming Up";


		}


		function updateTime(scene) {
			var
				delay1, delay2 = 0;

			if (typeof scene == "undefined") {
				delay1 = updateTime("amfiet");
				delay2 = updateTime("vindfruen");
				if (delay1 > delay2) {
					setNextScene("vindfruen");
				}
				else {
					setNextScene("amfiet");
				}
				return;
			}

			var
				scene = scene || false,

				// a and b are javascript Date objects
				result 	= "",
				concert = getNextConcert(scene),
				artist  = document.getElementById(scene + "-artist"),

				// set clock forwards by n weeks
				now 		= new Date(Date.now() + (7 * 24 * 60 * 60 * 1000)),
				days, hours, minutes,
				remainingDays 		= scene ? document.getElementById(scene + "-days") : document.getElementById("amfiet-days"),
				remainingHours 		= scene ? document.getElementById(scene + "-hours") : document.getElementById("amfiet-hours"),
				remainingMinutes 	= scene ? document.getElementById(scene + "-minutes") : document.getElementById("amfiet-minutes"),
				remainingSeconds 	= 0,
				dateDiff = function(a, b) {
				  var
				  	utc1 = Date.UTC(a.getFullYear(), a.getMonth(), a.getDate(), a.getUTCHours(), a.getMinutes()),
				  	utc2 = Date.UTC(b.getFullYear(), b.getMonth(), b.getDate(), b.getUTCHours(), b.getMinutes());

				  return (utc2 > utc1) ? (utc2 - utc1) : (utc1 - utc2);
				};


			remainingSeconds = Math.round(dateDiff(now, concert.when)/1000);

			days 		= Math.floor(remainingSeconds / (60 * 60 * 24));
			hours 	= Math.floor((remainingSeconds / (60 * 60)) % 24);
			minutes = Math.floor((remainingSeconds/60) % 60);

			// just to fix display when days are not shown
   		if (days == 1 && hours == 0) {
   			days = 0;
   			hours = 24;
   		}

   		if (artist.textContent != concert.artist) {
   			artist.textContent = concert.artist;
   		}

			// kind of pedantic, but still. don't touch the DOM unless you have to
     	if (remainingDays.textContent !== days.toString()) {
     		remainingDays.textContent = days;
     		if (days == 0 && hours <= 23) {

     			// removes the row displaying days left
     			hideDayCounters();
     		}
     	}
     	if (remainingHours.textContent !== hours.toString()) {
     		// console.info("Setting hours : " + hours + ", remainingHours : " + remainingHours.textContent);
     		remainingHours.textContent = hours;
     	}
     	if (remainingMinutes.textContent !== minutes.toString()) {
     		remainingMinutes.textContent = minutes;
     	}
     	// return time of next concert
     	return concert.when;
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
				dummyDate = dummyDate || new Date(Date.now() + (1 * 7 * 24 * 60 * 60 * 1000)),
				earliest 	= Date(0),
				concert 	= { artist : null, when : null};

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
					concert.artist = concerts[i]["artist"];
					concert.when = earliest;

					// console.log("returning concert : " + concert, concert);
					return concert;
				}
			}
			// console.log("returning concert : " + concert, concert);
			return concert;
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
					if (pi.isNumeric(timeStr)) {
						var
							time = parseFloat(timeStr);
						minutes = Math.floor(time / 60);
						seconds = Math.floor(time % 60);
					}
					else {
						console.error("unable to decipher as time : " + timeStr);
					}
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
	  -webkit-transition: color .1s ease;
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
	  height: auto;
	}

	.modal-dialog {
		color: #000;
	 	position: relative;
	  background: #fefefe;
	  border: #333333 solid 1px;
	  border-radius: 5px;
	  text-align: left;
	  /*position: fixed;*/
	  z-index: 11;
		width: 50em;
    margin: 5% auto;
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
		font-family: Roboto, sans-serif;
		position: fixed;
		display: none;
		opacity: 0;
		min-height: 100%;
		min-width: 100%;
		top: 0;
		right: 0;
		color : #fff;
		background-color: rgba(0, 0, 0, 0.4);
		background: -moz-radial-gradient(center, ellipse cover,  rgba(255,0,59,0.33) 0%, rgba(25,128,0,0.26) 100%);
		background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,rgba(255,0,59,0.33)), color-stop(100%,rgba(25,128,0,0.26)));
		background: -webkit-radial-gradient(center, ellipse cover,  rgba(255,0,59,0.33) 0%,rgba(25,128,0,0.26) 100%);
		background: -o-radial-gradient(center, ellipse cover,  rgba(255,0,59,0.33) 0%,rgba(25,128,0,0.26) 100%);
		background: -ms-radial-gradient(center, ellipse cover,  rgba(255,0,59,0.33) 0%,rgba(25,128,0,0.26) 100%);
		background: radial-gradient(ellipse at center,  rgba(255,0,59,0.33) 0%,rgba(25,128,0,0.26) 100%);

/*rgba(255,0,59,0.4)

		background: -moz-radial-gradient(center, ellipse cover,  rgba(0,128,45,0.5) 0%, rgba(255,0,59,0.25) 100%);
		background: -webkit-gradient(radial, center center, 0px, center center, 100%, color-stop(0%,rgba(0,128,45,0.5)), color-stop(100%,rgba(255,0,59,0.25)));
		background: -webkit-radial-gradient(center, ellipse cover,  rgba(0,128,45,0.5) 0%,rgba(255,0,59,0.25) 100%);
		background: -o-radial-gradient(center, ellipse cover,  rgba(0,128,45,0.5) 0%,rgba(255,0,59,0.25) 100%);
		background: -ms-radial-gradient(center, ellipse cover,  rgba(0,128,45,0.5) 0%,rgba(255,0,59,0.25) 100%);
		background: radial-gradient(ellipse at center,  rgba(0,128,45,0.5) 0%,rgba(255,0,59,0.25) 100%);
*/
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



	#imageselector {
		display: none;
    position:fixed;
		font-family: Roboto, sans-serif;
			/*opacity: 1;*/
    top: 0;
    left: 0;
    width:100%;
    height:100%;
/*    margin-top: -25%;
    margin-left: -25%;
*/    border: 1px solid #ccc;
    background: transparent;
    z-index: 5001;

/*		font-family: Roboto, sans-serif;
		position: fixed;
		display: none;
		opacity: 0;
		min-height: 100%;
		min-width: 100%;
		top: 0;
		right: 0;
		color : #fff;
		background-color: rgba(0, 0, 0, 0.4);
*/
	}

	#imageselector.active {
		display: block;
	}


</style>

	<div id="imageselector" onclick="onModalImageClick()">
		<div class="relative-wrapper">
		  <div class="images modal-dialog">
		  	<div class="modal-header">
			    <h2>Add/Select Image</h2>
			    <span class="btn-close" onclick="hideImageSelector(this);">×&nbsp;</span>
		  	</div>
		  	<div class="modal-body" style="min-height: 600px;">
		  		<input type="file" name="screen-image-upload" id="screen-image-upload" accept="image/*" />
		  		<img src="assets/img/search.svg" height="24" class="searchsymbol">
			  	<input type="text" name="screen-image-search" id="screen-image-search" style="width:50%;" />
			  	<progress id="screen-form-upload-progress" max="100"></progress>
			  	<span id="screen-form-status" class="status"></span>
					<div>
			    	<div id="screen-image-editor" class="editor"></div>
			    	<div id="screen-image-preview" class="preview" style="width: 100%; overflow-y: scroll;"></div>
					</div>
				</div>
		  </div>
		</div>
	</div>

	<div class="modal" id="modal-one" onclick="onModalClick(this);">
		<div class="relative-wrapper">
		  <div id="modal-dialog" class="modal-dialog" onclick="noClick();">
	    </div>
	  </div>
	</div>

<script type="text/javascript">


		document.querySelector('#screen-image-upload').addEventListener('change', function(e) {
			  var
			  	file 	= this.files[0],
			  	progress = document.getElementById("screen-form-upload-progress"),
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
				    		preview = document.getElementById("screen-image-preview"),
					      status  = document.getElementById("screen-form-status"),
								image 	= document.getElementById("screen-image-preview"),
								data 		= {},
								filename = "";

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

							console.log("response : ", JSON.stringify(resp));
							console.log("image[0] : ", JSON.stringify(window.data.images[0]));
							console.info("Adding uploaded image to globals");
							addUploadedImage(resp);

				      image.src 					= resp.dataUri;
				      image.className 		= "zoomInDown";
				      if (window.data && window.data.session) {
				      	console.info("setting currentImage : " + resp.uri);
				      	window.data.session.currentImage = resp.uri;

				      }
				      image.style.display = "inline";
				      image.style.height 	= "16em";
				      // editor.style.width = "auto";

				      // console.log("file.name : " + file.name);
				     // renders the form
				      // editor.innerHTML = Mustache.render(template, {"title" : file.name, "filename" : filename});

				      // submit.addEventListener("click", onImageSave);

				    }
				    else {
				    	// throws to window.onerror, where we trap and redirect to server
				    	throw "Xhr error: " + this.status;
				    }
			    }; // onload

		  	if (!file) {
		  		console.error("No file selected!");
		  		return;
		  	}

			  if (file && file.size) {
			  	if (window.data.settings['MAX_UPLOAD_SIZE'] && window.data.settings['MAX_UPLOAD_SIZE'] < file.size) {
			  		alert("File is too large ( > " + window.data.settings['MAX_UPLOAD_SIZE'] + ' bytes');
			  		return false;
			  	}
			  	console.info("Uploading file, " + file.size + " bytes");
			  }

		  	if (progress) {
		  		progress.style.visibility = "visible";
		  	}

			  console.log("file : ", file);
			   // populate formdata
			  data.append("image-upload", file);
			  data.append("username", "<?php echo $_SESSION['username'];?>");
			  data.append("uuid", pi.uuid());

			  xhr.upload.onprogress = onprogress;
			  xhr.onload = onload;

			  xhr.open('POST', 'admin/image-upload.php', true);
			  xhr.send(data);

			}, false);



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


	function onModalImageClick(e) {
		var
			imageselector = document.getElementById("imageselector");

		if (this == imageselector) {
			console.info("DO HIDE!");
		}
		else {
			// console.log("do NOT hide, this : " + this, this);
		}
	}


	function onModalClick(e) {
		hideModal();
		console.log("Clicked outside modal dialog. This : ", e);
	}
 	

	function blurBackground() {
		// disable
		return;
		var
			toolbar = document.getElementById("toolbar"),
			playqueue = document.getElementById("playqueue");

		if (toolbar && playqueue) {
			console.info("Blurring...");
			toolbar.classList.add("blur");
			playqueue.classList.add("blur");
		}
		else {
			console.error("Not found");
		}
	}

	function unblur() {
		// disable
		return;
		var
			toolbar = document.getElementById("toolbar"),
			playqueue = document.getElementById("playqueue");

		if (toolbar && playqueue) {
			toolbar.classList.remove("blur");
			playqueue.classList.remove("blur");
		}
	}

	function showModal(figure) {
		var
			figure = figure || null,
			image = document.createElement("div"),
			wrapper = document.getElementById("wrapper"),
			modal = document.getElementById("modal-one");

		if (figure) {

			modal.classList.add("active");
			console.info("calling blurBackground()");
			blurBackground();
			setTimeout(function() {
				modal.classList.add("showing");
			}, 1);			
		}
	}


	function hideModal() {
		var
			wrapper = document.getElementById("wrapper"),
			modal = document.getElementById("modal-one");

		exitPreviewMode();
		// console.info("calling unblur()");
		// unblur();
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

				// pi.log("settings : " + data.settings, data.settings);
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
					// console.log("loaded " + data.images.length + " images.");
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
				try {
					screens = JSON.parse(json);
				}
				catch(e) {
					console.error(e);
				}
				if (screens && screens.screens && screens.screens.length) {
					for (var i = 0; i < screens.screens.length; i++) {
						if (typeof screens.screens[i]['data'] == "string") {
							// console.info("new screen : " + screens.screens[i], screens.screens[i]);
							try {
							var
								obj = JSON.parse(screens.screens[i]['data']);
							}
							catch (e) {
								console.error(e);
							}

								for (var key in obj) {
									if (typeof screens.screens[i][key] == "undefined") {
										screens.screens[i][key] = obj[key];
									}
								}
						}
					}
					data.screens = screens.screens;
					updateScreenList();
				}
				// pi.log("loaded screens : " + json);
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

<script type="text/javascript">


	function updateInstagramList() {
		var
			arr, insta,
			instadiv = document.getElementById("instagram");
		if (window.data && window.data.instagram && window.data.instagram.liked && window.data.instagram.liked.length) {
			arr = window.data.instagram.liked;
			instadiv.innerHTML = "";
			for (var i = 0; i < arr.length; i++) {
				if (arr[i]['type'] == "image") {
					insta = document.createElement("img");
					insta.src = arr[i]['images']['standard_resolution']['url'];
					insta.alt = arr[i]['caption']['text'];
					insta.title = insta.alt;
					insta.style.height = "6em";
					instadiv.appendChild(insta);
				}
				else {
					console.info("Skipping type : " + arr[i]['type']);
				}
			}
		}
	}



	function updateInstagram(data) {
		var
			data = data || null;

		if (!window.data) {
			window.data = {};
		}
		if (!window.data.instagram) {
			window.data.instagram = {};
		}
		window.data.instagram.liked = [];
		if (data && data.length) {
			for (var i = 0; i < data.length; i++) {
				window.data.instagram.liked.push(data[i]);
				// console.info("INSTAGRAM : " + data[i].type + ", " +data[i]['images']['standard_resolution']['url']);
			}
			updateInstagramList();
		}
		else {
			console.error("No data in updateInstagram()!");
		}
	}


	document.addEventListener("DOMContentLoaded", function () {
		var
			instaurl = "assets/php/instagram.php",
			instadiv = document.getElementById("instagram");

		pi.xhr.get(instaurl, function (json) {
			var
				data = null,
				json = json || null;
			if (json && json.length) {

				try {
					data = JSON.parse(json);
					if (data && data.meta && data.meta.code) {
						if (data.meta.code == 200) {
							updateInstagram(data.data);
						}
						else {
							console.error("Error loading instagram data, code : " + data.meta.code);
						}
					}
				}
				catch(e) {
					console.error("Error : " + e);
				}
			}
			else {
				console.error("!json &&json.length");
			}

		}, console.error);

	});

</script>

</body>
</html>