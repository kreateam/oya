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
			position: relative;
			background: #000;
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


		.container {
			position: relative;
			text-align: left;
			width: 100%;
			height: 224px;
		}


		.image {
	    display: inline-block;
	    background: red;
	    margin-left: auto;
	    margin-right: auto;
	    width: 224px;
	    height: 224px;
		}

	</style>

</head>
<body>
	<section id="instagram" class="content">
		<div class="container">
			<div class="message"></div>
			<div class="image"></div>
		</div>
		<div class="container">
			<div class="image"></div>
			<div class="image"></div>
		</div>
		
	</section>

	<script type="text/javascript">


	function updateInstagramList() {
		var
			arr, insta, item, 
			imageCount = 5,
			instas = document.getElementsByClassName("image");

		imageCount = instas.length;
		if (window.data && window.data.instagram && window.data.instagram.liked && window.data.instagram.liked.length) {
			arr = window.data.instagram.liked;
			for (var i = 0; (i < arr.length && i < imageCount); i++) {
				if (arr[i]['type'] == "image") {
					// instadiv.appendChild(insta);
					item = instas[i];
					if (item) {
						console.info("found one: " + i);
						console.info("url " + "url('" + arr[i]['images']['standard_resolution']['url'] + "');");
						item.style.backgroundImage = arr[i]['images']['standard_resolution']['url'];
					}
					else {
						console.info("not found: " + i);
					}
				}
				else {
					// console.info("Skipping type : " + arr[i]['type']);
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