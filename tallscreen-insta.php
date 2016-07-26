<?php

	/**
	 * Narrowscreen Player for big screens - Øyafestivalen 2016
	 */
?>
<!doctype html>
<html>
<head>
<!--  <script type="text/javascript" src="assets/js/errorhandler.js"></script>
 -->	

 	<meta charset="utf-8">

	<title>Øyafestivalen 2016</title>
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
	<style type="text/css">

		* {
			margin  : 0;
			padding : 0;
      /* hide mouse pointer */
      cursor: none;
      pointer-events: none;
		}

		html, body {
			position: absolute;
			background: #000;
			color 			: #fff;
			width: 100%;
			min-width: 100%;
			height: 100%;
			min-height: 100%;
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


		iframe {
			display: block;
			background: #fff;
			border: none;
      width: 448px;
      height: 768px;
			/* fill container */
			-webkit-transform-origin: top center;
				transform-origin: top center;

/*
		for 1920x1080 
	*/

			-webkit-transform: scale(1.40625, 1.40625);
							transform: scale(1.40625, 1.40625);

		}

		#contentframe {
			margin-left: auto;
			margin-right: auto;
			top: 0;
		}

	</style>

</head>
<body>
<iframe src="player-insta.php" id="contentframe" class="contentframe"></iframe>
<script>

  function sendMessage(msg, domain) {
    var
      domain = domain || "*",
      iframe = document.getElementById("contentframe");

    if (domain === null) {
      var 
        l = window.location;
        domain = l.protocol + "//" + l.host;
    }
    if (iframe && iframe.contentWindow && typeof iframe.contentWindow.postMessage == "function") {
      // console.debug("sending msg to iframe: " + msg + ", domain: " + domain);
      iframe.contentWindow.postMessage(msg.data, domain);
    }
  }

  /**
   * Workaround: redirect messages sent to window.top to content frame
   */
  function onFrameMessage(e) {
    sendMessage(e);
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

  window.addEventListener("message", onFrameMessage);
  window.addEventListener('orientationchange', doOnOrientationChange);

  // Initial execution if needed
  doOnOrientationChange();


</script>


</body>
</html>