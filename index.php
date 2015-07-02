<?php

	session_start();

	error_reporting(E_ALL);

	require_once("./assets/php/weather.php");

?>
<!doctype html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Øyafestivalen 2015</title>

	<script type="text/javascript" src="assets/js/pi.core.js"></script>
	<style type="text/css">

		html, body {
			color: #000;
			margin: 0;
			padding: 0;
			font-family: 'ClanOT', sans-serif;

		}



		iframe {
			border: solid 16px rgba(38,188,244,0.5);
			background: none repeat scroll 0% 0% transparent; 
			/*width: 100%;*/
		}

		footer {
			height: 45px;
			font-size: 32px;
			background-color: #000;
		}


	</style>

</head>
<body>
	<header>
	</header>

	<section class="content">
		<iframe scrolling="no" src="weather.php" width="512" height="704"></iframe>
		<iframe scrolling="no" src="weather.php" width="672" height="384"></iframe>

	</section>


	<script type="text/javascript">
	(function () {


	})();

	</script>
</body>
</html>