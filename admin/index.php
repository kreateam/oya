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
	<base href="../" target="_blank">
	<script type="text/javascript" src="assets/js/errorhandler.js"></script>

	<!-- P22 ... ? -->
	<script src="//use.typekit.net/aue5uth.js"></script>
	<script>try{Typekit.load();}catch(e){}</script>

	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="/html5/assets/font/clan.css">
	<link rel="stylesheet" type="text/css" href="/html5/assets/font/dosis.css">
	<link rel="stylesheet" type="text/css" href="/html5/assets/font/mirror.css">
	<link rel="stylesheet" type="text/css" href="assets/css/contextmenu.css" media="all" />
	<link rel="stylesheet" type="text/css" href="assets/css/tabstrip.css" />

	<link href='http://fonts.googleapis.com/css?family=Kreon:300,400,700' rel='stylesheet' type='text/css'>
	<link href='http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
	<script type="text/javascript" src="assets/js/contextmenu.js"></script>
	<script type="text/javascript" src="assets/js/mustache.js"></script>
	<script type="text/javascript" src="assets/js/pi.core.js"></script>
	<script type="text/javascript" src="assets/js/pi.xhr.js"></script>

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

		span {
			-webkit-transition: color .4s ease-out;
			 				transition: color .4s ease-out;
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

			-webkit-transition: height .4s ease-out;
							transition: height .4s ease-out;
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

			-webkit-transition: border .4s ease-out, opacity .4s ease-out, -webkit-transform .2s ease-out; /* Safari */
			-webkit-transition: border .4s ease-out, opacity .4s ease-out, transform .2s ease-out;
							transition: border .4s ease-out, opacity .4s ease-out, transform .2s ease-out;

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

		.up-arrow:before {
			font-weight: 100;
			content: "<";
		}

		.up-arrow {
			display: inline-block;
			position: relative;
			margin-right: 0.4em;
			margin-left: 0.4em;
			font-weight: 100;
			top:-0.1em;
			-webkit-transform : rotate(90deg) scale(1, 1.8);
							transform : rotate(90deg) scale(1, 1.8);
			cursor: pointer;
		}

		.down-arrow:before {
			font-weight: 100;
			content: ">";
		}

		.down-arrow {
			display: inline-block;
			position: relative;
			margin-right: 0.4em;
			margin-left: 0.4em;
			font-weight: 100;
			top:-0.1em;
			-webkit-transform : rotate(90deg) scale(1, 1.8);
							transform : rotate(90deg) scale(1, 1.8);
			cursor: pointer;
		}

		.down-arrow:hover {
			color: #fff;
		}

		.up-arrow:hover {
			color: #fff;
		}

		.item-menu {
			color: rgba(255,255,255,0.1);
			float: right;
			text-align: left;
			width: 6em;
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
			height: 20em;
			/*text-align: left;*/

			/* doesn't work ... why o whai */
			-webkit-transition: height 1.2s cubic-bezier(0.190, 1.000, 0.220, 1.000); 
			 				transition: height 1.2s cubic-bezier(0.190, 1.000, 0.220, 1.000); /* easeOutExpo */
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

	</style>

<script type="text/javascript">

	// set time from server, so there are no discrepancies in timing
	window.serverTime = <?php print(time()*1000)?>;
	window.serverTimeRaw = "<?php echo (time()*1000)?>";
	
	window.clientTime = new Date().getTime();

	console.log("serverTime : " + serverTime);
	console.log("clientTime : " + clientTime);

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
					console.log("transform : " + screens[1].style.transform);
					screens[0].style.transform = "scale(1, 1)";
					screens[1].style.transform = "scale(1, 1)";
					screens[0].style.border = "2px solid rgba(0,0,0,0.4)";
					screens[1].style.border = "2px solid rgba(0,0,0,0.4)";
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
				break;
			default:
				// nothing, at the moment

    } // switch

    if (key != "M") {
    	// 
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
			console.log("iframe loaded : " + elem.id);
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
		<div class="middle">
			<section class="content">
				<header>
					Play Queue
				</header>

				<section id="current">
					<div class="section-title">Now playing</div>
					<ul>
						<li id="item-0" class="item playing">Weather - today<span class="duration">0:42</span></li>
					</ul>
				</section>

				<section id="queue">
					<div class="section-title">Queued</div>
					<ul>
						<li id="item-1" class="item">Food report #32 
							<span class="duration">0:30</span>
							<span class="item-menu">
								<span class="up-arrow"></span>
								<span class="down-arrow"></span>
							</span>
						</li>
						<li id="item-2" class="item">Instagram 
							<span class="hashtag instagram">#osloby #latest</span>
							<span class="duration">3:00</span>
						</li>
					</ul>
				</section>

				<section id="next-tracks">
					<div class="section-title">Next Items</div>
					<ul>
						<li class="item">Announcement : &lt;TBA&gt;
							<span class="duration">0:30</span>
						</li>
						<li class="item">Tweets 
							<span class="hashtag twitter">#osloby</span>
							<span class="duration">1:30</span>
						</li>
						<li class="item">Tweets 
							<span class="hashtag twitter">#oya</span>
							<span class="duration">1:30</span>
						</li>
						<li class="item">Coming up...
							<span class="duration">2:30</span>
						</li>
					</ul>
				</section>

				<section id="demo"></section>
			</section>

			<footer>
			<div id="toolbar" class="tabstrip selectable">
		    <ul>
	        <li>
            <input type="radio" name="tabstrip-0" checked="checked" id="tabstrip-0-0" />
            <label for="tabstrip-0-0">New...</label>
            <div>
							<select>
							  <option value="volvo">Volvo</option>
							  <option value="saab">Saab</option>
							  <option value="mercedes">Mercedes</option>
							  <option value="audi">Audi</option>
							</select>
            	<select>
						    <optgroup label="groupe1">
						        <option value="alpha">Alpha</option>
						        <option value="beta">Beta</option>
						    </optgroup>
						    <optgroup label="groupe2">
						        <option value="one">One</option>
						        <option value="two">Two</option>
						    </optgroup>
							</select>
              <p>Lorem Ipsum is simply dummy text of the printing and 
                  typesetting industry. Lorem Ipsum has been the industry's 
                  standard dummy text ever since the 1500s, when an unknown 
                  printer took a galley of type and scrambled it to make a 
                  type specimen book. It has survived not only five centuries, 
                  but also the leap into electronic typesetting, remaining 
                  essentially unchanged. It was popularised in the 1960s 
                  with the release of Letraset sheets containing Lorem 
                  Ipsum passages, and more recently with desktop publishing 
                  software like Aldus PageMaker including 
                  versions of Lorem Ipsum.
                </p>
            </div>
	        </li>
	        <li>
            <input type="radio" name="tabstrip-0" id="tabstrip-0-1" />
            <label for="tabstrip-0-1">Images</label>
            <div>
            	<input type="file" name="image-upload" id="image-upload" accept="image/*"/>
            </div>
	        </li>
	        <li>
            <input type="radio" name="tabstrip-0" id="tabstrip-0-2" />
            <label for="tabstrip-0-2">Videos</label>
            <div>
            	<input type="file" name="video-upload" id="video-upload" accept="video/*"/>
              <p>Contrary to popular belief, Lorem Ipsum is not simply 
                  random text. It has roots in a piece of classical 
                  Latin literature from 45 BC, making it over 2000 years 
                  old. Richard McClintock, a Latin professor at Hampden-Sydney 
                  College in Virginia, looked up one of the more obscure 
                  Latin words, consectetur, from a Lorem Ipsum passage, 
                  and going through the cites of the word in classical 
                  literature, discovered the undoubtable source. Lorem 
                  Ipsum comes from sections 1.10.32 and 1.10.33 of "de 
                  Finibus Bonorum et Malorum" (The Extremes of Good and Evil) 
                  by Cicero, written in 45 BC. This book is a treatise on 
                  the theory of ethics, very popular during the Renaissance. 
                  The first line of Lorem Ipsum, "Lorem ipsum dolor sit amet..", 
                  comes from a line in section 1.10.32.

              </p>
            </div>
	        </li>
	        <li>
            <input type="radio" name="tabstrip-0" id="tabstrip-0-3" />
            <label for="tabstrip-0-3">Instagram</label>
            <div>
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

								@media only screen and (-webkit-min-device-pixel-ratio: 2), only screen and (min--moz-device-pixel-ratio: 2), only screen and (-o-min-device-pixel-ratio: 2 / 1), only screen and (min-device-pixel-ratio: 2), only screen and (min-resolution: 192dpi), only screen and (min-resolution: 2dppx) {
									.ig-b-48 { 
										background-image: url(//badges.instagram.com/static/images/ig-badge-sprite-48@2x.png); 
										background-size: 60px 178px; 
									} 
								}
							</style>

							<a href="http://instagram.com/osloby_no?ref=badge" class="ig-b- ig-b-48"><img src="//badges.instagram.com/static/images/ig-badge-48.png" alt="Osloby.no - Instagram" />osloby.no</a>

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

		
	</script>

	<script type="text/javascript">

		/** @todo  This seems safe enough, no? */
		document.querySelector('#image-upload').addEventListener('change', function(e) {
			  var
			  	file 	= this.files[0],
			  	data 	= new FormData(),
			  	xhr 	= new XMLHttpRequest(),

			  	// @todo Maybe some visual feedback on upload progress
			  	onprogress = function(e) {
				    if (e.lengthComputable) {
				      var
				      	percentComplete = (e.loaded / e.total) * 100;

				      console.log(percentComplete + '% uploaded');
				    }
				  },

				  /**
				   * When image is returned from server
				   * @return {void}
				   */
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
				      	// console.log("insertBefore");
					      section.insertBefore(image, section.firstChild);
				      }
				      else {
				      	// console.log("appendChild");
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

		document.querySelector('#video-upload').addEventListener('change', function(e) {
			  var
			  	file 	= this.files[0],
			  	data 	= new FormData(),
			  	xhr 	= new XMLHttpRequest(),

			  	// @todo Maybe some visual feedback on upload progress
			  	onprogress = function(e) {
				    if (e.lengthComputable) {
				      var
				      	percentComplete = (e.loaded / e.total) * 100;

				      console.log(percentComplete + '% uploaded');
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

				console.log("this : " + this, this);

				if (this && this.classList && this.classList.contains("closebutton")) {
					if (toolbar && toolbar.classList.contains("active")) {
						toolbar.classList.remove("active");
						return false;
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
				now 		= new Date(),
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
		function getNextConcert(scene) {
			var
				scene 	= scene || null,
				program = program || null,
				result 	= null,
				earliest = Date(0);

			for (var i in concerts) {
				if (scene && concerts[i]["scene"] != scene) {
					// console.log("skipping: " + concerts[i], concerts[i]);
					continue;
				}
				if (result === null) {
					// console.log("Scene : " + scene + ", earliest : " + concerts[i]["date"]);
					earliest = concerts[i]["date"];
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
</body>
</html>