<?php
	
	/**
	 * 
	 */


	session_start();

?>
<!doctype html>
<html>
<head>
	<!--script type="text/javascript" src="assets/js/errorhandler.js"></script-->

	<meta charset="utf-8">
	<title>Øya 2015</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="/html5/assets/font/clan.css">
	<script type="text/javascript" src="assets/js/mustache.js"></script>
	<script type="text/javascript" src="assets/js/pi.core.js"></script>
	<script type="text/javascript" src="assets/js/pi.xhr.js"></script>
	<style type="text/css">

	html, body {
		height: 100%;
		min-height: 100%;
	}


	/* template styles */
	.instagram {
		position: relative;
		font-family: "ClanOT", sans-serif;
		font-size : 32px;
		height: 100%;
		min-height: 100%;
	}


	.box {
	  width: 100%;
	  height: 50%;
	  margin: auto;
	  position: absolute;
	  top: 0;
	  left: 0;
	  right: 0;
	  bottom: 0;
	}

	.box > img {
	  position: relative;
	  z-index: -1;
	  top: 50%;
	  left: 50%;
	  width: 100%;
	  -webkit-transform: translate(-50%, -50%);
	      -ms-transform: translate(-50%, -50%);
	          transform: translate(-50%, -50%);
	}

	.box > img.max {
	  width: auto;
	  height: 100%;
	}

	#table {
		width: 100%;
		height: 100%;
		vertical-align: middle;
	}

	.instagram .title {
		background-color: #e6007e;
		padding: 0 10px;
		color: #fff;
		text-align: center;
		font-weight: 900;
	}

	.instagram .score{
		/*color: rgba(38,188,244,1);*/
    font-family : 'AvenirNext-Heavy', 'AvenirNext-Bold ', 'Avenir Black', 'Arial Black', Haettenschweiler, 'Franklin Gothic Bold', Charcoal, 'Helvetica Inserat', 'Bitstream Vera Sans Bold', Roboto, Futura, 'HelveticaNeue', 'Segoe UI', sans-serif;

		position: absolute;
		right: 32px;
		bottom: 32px;
		color: #fff;
		z-index: 5001;
	}

	.instagram .score-medallion {
		color: #fff;
		width: 64px;
		height: 64px;
		text-align: center;
		background: #e6007e;
		font-size: 52px;
		font-weight: 900;
		border-radius: 50%;
		border: none;
		/*padding-top: 18px;*/
	}

	#image {
		position: absolute;
		margin: 0;
		left: 0;
		top: 0;
		width: 100%;
		height: 50%;
		overflow: hidden;
	}

	#text {
		color: #fff;
		position: absolute;
		background-color: #26BCF4;
		/*text-align: center;*/
		right: 0;
		bottom: 0;
		margin: 0;
		width: 100%;
		max-width: 100%;
		height: 50%;
	}

	@media (min-width: 602px) {

		#image {
			width: 50%;
			height: 100%;
			overflow: hidden;
		}
		#text {
			width: 50%;
			height: 100%;
		}
		#table {
		}

	}

	@media (max-width: 601px) {
		#image {
			width: 100%;
			height: 50%;
			overflow: hidden;
		}
		#text {
			width: 100%;
			height: 50%;
		}
		#table {
			font-size: 48px;
		}
	}

	</style>

</head>
<body>
<div class="instagram">
	<div id="image">
			<div class="box">
				<img id="instaimage" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAMAAABOo35HAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAMAUExURQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAALMw9IgAAAEAdFJOU////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////wBT9wclAAAACXBIWXMAAA7DAAAOwwHHb6hkAAAAGHRFWHRTb2Z0d2FyZQBwYWludC5uZXQgNC4wLjVlhTJlAAACTElEQVR4Xu3QMQEAMBADofo3nVr420ECb5zJCmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFss62D9pFRPOZ7V3gAAAAAElFTkSuQmCC" class="image">
			</div>
	</div>
	<div id="text">
		<table id="table">
			<tr>

				<td align="center" valign="middle">DEL DINE<br>#APØYABLIKK</td>
			</tr>
		</table>
	</div>
</div>

	<script type="text/javascript">



	function getCounter() {
		if (typeof(Storage) != "undefined") {

			// returns null if non-existent
			instaCount = localStorage.getItem("instaCount");
			if (instaCount === null) {
				instaCount = 1;
			}
			else if (instaCount > 19) {
				instaCount = 0;
			}
			else {

				// convert to integer, and increment
				instaCount = parseInt(instaCount, 10);
				instaCount++;
			}

			// update localStorage with current value
			localStorage.setItem("instaCount", instaCount);			
			return instaCount;
		}
		else {
			return math.floor(Math.random() * window.data.instagram.liked.length);
		}

	}



	function updateInstagramList() {
		var
			url, arr, insta, item,
			imageCount = 5,
			instaCounter = 0,
			insta = document.getElementById("instaimage");

		if (window.data && window.data.instagram && window.data.instagram.liked && window.data.instagram.liked.length) {
			arr = window.data.instagram.liked;
			instaCounter = getCounter();
			url = arr[instaCounter % window.data.instagram.liked.length]['images']['standard_resolution']['url'];
			if(insta) {
				console.info("Loading insta: " + url);
				insta.src = url;
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
			// setInterval(updateInstagramList, 20000);
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