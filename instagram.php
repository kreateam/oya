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
	<link rel="stylesheet" type="text/css" href="/assets/font/clan.css">
	<link rel="stylesheet" type="text/css" href="/assets/font/publico.css">
	<script type="text/javascript" src="/assets/js/lib/mustache.min.js"></script>
	<script type="text/javascript" src="/assets/js/pi.core.js"></script>
	<script type="text/javascript" src="/assets/js/pi.xhr.js"></script>
	<style type="text/css">

	html, body {
		height: 100%;
		min-height: 100%;
		/*background-color: rgb(227, 96, 24);*/

		margin: 0;
		padding: 0;
	}


	/* template styles */
	.instagram {
		position: relative;
		font-family: 'ClanOT', sans-serif;
		font-size : 32px;
		height: 100%;
		min-height: 100%;
		text-shadow: 0px 1px 4px rgba(0,0,0,.33);
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
	  /*z-index: -1;*/
	  top: -50%;
	  left: 50%;
	  width: 100%;
	  -webkit-transform: translateX(-50%);
	      -ms-transform: translateX(-50%);
	          transform: translateX(-50%);
	}

	.box > img.max {
	  width: auto;
	  height: 100%;
	}

	.box > video {
	  position: absolute;
	  /*z-index: -1;*/
	  bottom: -25%;
	  left: 0;
	  width: 100%;
	  margin: 0 auto;
	}

	.box > video.max {
		position: absolute;
		top: -100%;
		left: 0;
		right: 0;
		bottom: 100%;
	}


	#table {
		margin-top: 20px;
		width: 100%;
		height: 100%;
		vertical-align: middle;
		font-family: PublicoHeadline, Publico, serif;
		padding: 0;
	 	border-spacing: 0;
    border-collapse: separate;	
  }

	.instagram .heavy {
		font-weight: 900;
	}

	.instagram .title {
		background-color: rgba(255, 255, 255, .7);
		/*padding: 0 10px;*/
		color: rgb(94, 22, 0);
		text-align: center;
		font-weight: 400;
		font-size: 44px;
		line-height: 46px;
		height: 108px;
		text-shadow: none;
	}

	.instagram #caption {
		/* 
		position: relative;
		top: -6px;
		background-color: rgba(30,130,163,1);
		*/
		color: #fff;
		font-family: ClanOT, sans-serif;
		font-size: 28px;
		line-height: 32px;
		font-weight: 400;
		max-height: 128px;
		padding: 0 6px;
		/*font-style: italic;*/
		/*background-blend-mode: multiply;*/
	}


	.instagram .score {
		/*color: rgba(38,188,244,1);*/
    /*font-family : 'AvenirNext-Heavy', 'AvenirNext-Bold ', 'Avenir Black', 'Arial Black', Haettenschweiler, 'Franklin Gothic Bold', Charcoal, 'Helvetica Inserat', 'Bitstream Vera Sans Bold', ClanOT, Futura, 'HelveticaNeue', 'Segoe UI', sans-serif;*/
    font-family : ClanOT, sans-serif;



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
		background: rgb(227, 96, 24);
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
		top: -10%;
		width: 100%;
		height: 65%;
		overflow: visible;
	}

	#text {
		color: rgb(94, 22, 0);
		position: absolute;
		background-color: rgb(227, 96, 24);
		/*background-color: #26BCF4;*/
		/*text-align: center;*/
		right: 0;
		bottom: 0;
		margin: 0;
		width: 100%;
		max-width: 100%;
		height: 45%;
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
			width: 90%;
			height: 65%;
			margin: 0 5%;
			overflow: hidden;
		}
		#text {
			width: 100%;
			height: 45%;
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
<blockquote class="instagram-media" data-instgrm-captioned data-instgrm-version="7" style=" background:transparent; border:0; border-radius:0px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:658px; padding:0; width:100%; width:-webkit-calc(100%); width:calc(100%);"><div style="padding:8px;"> <div style="line-height:0; margin-top:40px; padding:50.0% 0; text-align:center; width:100%;"> <div style=" background:transparent; display:block; height:44px; margin:0 auto -44px; position:relative; top:-22px; width:44px;"></div></div> <p style=" margin:8px 0 0 0; padding:0 4px;"> <a href="https://www.instagram.com/p/6db7FIpO9e/" style=" color:#000; font-family:sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none; word-wrap:break-word;" target="_blank">TAKK FOR I ÅR 🙏🏽 #apøyablikk #øyablikk #øya2015 #øya15 #øyafrivillig</a></p> <p style=" color:#c9c8cd; font-family:sans-serif; font-size:14px; line-height:17px; margin-bottom:0;overflow:hidden; text-align:center; text-overflow:ellipsis; white-space:nowrap;">A photo posted by @shantinela on <time style=" font-family:sans-serif; font-size:14px; line-height:17px;" datetime="2015-08-16T21:49:51+00:00">Aug 16, 2015 at 2:49pm PDT</time></p></div></blockquote>
<script async defer src="//platform.instagram.com/en_US/embeds.js"></script>
<!-- 			<div class="box">
				<img id="instaimage" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAASwAAAEsCAMAAABOo35HAAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAMAUExURQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAALMw9IgAAAEAdFJOU////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////wBT9wclAAAACXBIWXMAAA7DAAAOwwHHb6hkAAAAGHRFWHRTb2Z0d2FyZQBwYWludC5uZXQgNC4wLjVlhTJlAAACTElEQVR4Xu3QMQEAMBADofo3nVr420ECb5zJCmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFsgJZgaxAViArkBXICmQFss62D9pFRPOZ7V3gAAAAAElFTkSuQmCC" class="image">
			</div>
 -->
</div>
	<div id="text">
		<table id="table" cellspacing="0" cellpadding="0">
			<tr>
				<td align="center" valign="middle" class="title">Del dine<br><span class="heavy">#apøyablikk</span></td>
			</tr>
			<tr>
				<td id="caption" align="center" valign="middle"></td>
			</tr>
		</table>
	</div>
</div>

	<script type="text/javascript">

	var instaEmbedCode = "<blockquote class=\"instagram-media\" data-instgrm-captioned data-instgrm-version=\"7\" style=\" background:#FFF; border:0; border-radius:3px; box-shadow:0 0 1px 0 rgba(0,0,0,0.5),0 1px 10px 0 rgba(0,0,0,0.15); margin: 1px; max-width:658px; padding:0; width:99.375%; width:-webkit-calc(100% - 2px); width:calc(100% - 2px);\"><div style=\"padding:8px;\"> <div style=\" background:#F8F8F8; line-height:0; margin-top:40px; padding:50.0% 0; text-align:center; width:100%;\"> <div style=\" background:url(data:image\/png;base64,iVBORw0KGgoAAAANSUhEUgAAACwAAAAsCAMAAAApWqozAAAABGdBTUEAALGPC\/xhBQAAAAFzUkdCAK7OHOkAAAAMUExURczMzPf399fX1+bm5mzY9AMAAADiSURBVDjLvZXbEsMgCES5\/P8\/t9FuRVCRmU73JWlzosgSIIZURCjo\/ad+EQJJB4Hv8BFt+IDpQoCx1wjOSBFhh2XssxEIYn3ulI\/6MNReE07UIWJEv8UEOWDS88LY97kqyTliJKKtuYBbruAyVh5wOHiXmpi5we58Ek028czwyuQdLKPG1Bkb4NnM+VeAnfHqn1k4+GPT6uGQcvu2h2OVuIf\/gWUFyy8OWEpdyZSa3aVCqpVoVvzZZ2VTnn2wU8qzVjDDetO90GSy9mVLqtgYSy231MxrY6I2gGqjrTY0L8fxCxfCBbhWrsYYAAAAAElFTkSuQmCC); display:block; height:44px; margin:0 auto -44px; position:relative; top:-22px; width:44px;\"><\/div><\/div> <p style=\" margin:8px 0 0 0; padding:0 4px;\"> <a href=\"https:\/\/www.instagram.com\/p\/6YB9_ar9wn\/\" style=\" color:#000; font-family:sans-serif; font-size:14px; font-style:normal; font-weight:normal; line-height:17px; text-decoration:none; word-wrap:break-word;\" target=\"_blank\">På Øyafestivalen og gleder oss  til Lars Vaular #apøyablikk<\/a><\/p> <p style=\" color:#c9c8cd; font-family:sans-serif; font-size:14px; line-height:17px; margin-bottom:0; margin-top:8px; overflow:hidden; padding:8px 0 7px; text-align:center; text-overflow:ellipsis; white-space:nowrap;\">A photo posted by Erna Solberg (@erna_solberg) on <time style=\" font-family:Arial,sans-serif; font-size:14px; line-height:17px;\" datetime=\"2015-08-14T19:26:51+00:00\">Aug 14, 2015 at 12:26pm PDT<\/time><\/p><\/div><\/blockquote><script async defer src=\"\/\/platform.instagram.com\/en_US\/embeds.js\"><\/script>";



	document.addEventListener("DOMContentLoaded", function(e) {
		var
			msg = {}, 
			bgColor, fgColor, title, style;

		title = document.querySelector(".title");
		if (title) {
			style = getComputedStyle(title, null);
			msg.fgColor = style.getPropertyValue("color");
			style = getComputedStyle(document.body, null);
			msg.bgColor = style.getPropertyValue("background-color");
			window.parent.postMessage(msg, "*");
		}
	});


	function getCounter() {
		if (typeof(Storage) != "undefined") {

			// returns null if non-existent
			var
				instaLength = localStorage.getItem("instaLength"),
				instaCount = localStorage.getItem("instaCount");
// window.data.instagram.liked.length
			if (instaCount === null) {
				instaCount = 1;
			}
			else if (instaCount > 19) {
				// roll over
				instaCount = 0;
			}
			else {

				// convert to integer, and increment
				instaCount = parseInt(instaCount, 10);
				instaCount++;
			}

			if (instaLength != window.data.instagram.liked.length) {
				console.debug("Instagram was updated! Length was " + instaLength + ", now it's " + window.data.instagram.liked.length + " - counter is at " + instaCount);
				instaLength = window.data.instagram.liked.length;
				localStorage.setItem("instaLength", instaLength);
				console.debug("We should now show #1 from insta-list");
			}
			else {
				console.debug("Instagram was NOT updated! Length is still " + instaLength);
			}


			// update localStorage with current value
			localStorage.setItem("instaCount", instaCount);			
			return instaCount;
		}
		else {
			return math.floor(Math.random() * window.data.instagram.liked.length);
		}

	}

	function removeTrailingHashtags(text) {
		var
			arr,
			token = " #",
			trailing = true,
			text = text || null;
		if (!text) {
			return false;
		}

		arr = text.split(token);
		if (arr && arr.length) {
			for (var i = arr.length - 1; i >= 1; i--) {
				if (trailing && arr[i].trim().indexOf(" ") == -1) {
					// remove from end of array
					// console.info("Removing: " + arr[i]);
					arr.pop();
				}
				else {
					trailing = false;
				}
			};
			if (arr.length == 1) {
				// console.info("Returning: " + arr[0] + " from '" + text + "'");
				return arr[0].trim();
			}
			else {
				// console.info("Returning join from " + arr.length + " rows");
				return arr.join(token).trim();
			}
		}

		return text.trim();
	}

	/**
	 * Event handler for video.ended event
	 */
	function onInstagramVideoEnded(e) {
		console.debug("That's the end of the video: " + e, e);

		 // loop
		this.play();
	}


	function playInstagramVideo(url) {
		var
			container = document.querySelector(".box"),
			v = document.createElement("video");

		v.src = url;
		v.setAttribute("autoplay", true);
		// v.setAttribute("loop", true);
		v.volume = 0;
		v.className = "max";
		// clear current content
		container.innerHTML = "";

		console.debug("Adding video element to box");
		container.appendChild(v);
		console.debug("Adding event listener to video");
		v.addEventListener("ended", onInstagramVideoEnded);

	}

	function updateInstagramList() {
		var
			videourl,
			url, arr, insta, item,
			imageCount = 5,
			instaCounter = 0,
			caption = document.getElementById("caption"),
			captionText,
			container = document.getElementById("image"),
			insta = document.getElementById("instaimage");

		if (window.data && window.data.instagram && window.data.instagram.liked && window.data.instagram.liked.length) {
			arr = window.data.instagram.liked;
			instaCounter = getCounter();
			url = arr[instaCounter % window.data.instagram.liked.length]['images']['standard_resolution']['url'];
			if(insta) {
				// console.info("Loading insta: " + url, arr[instaCounter % window.data.instagram.liked.length]);
				// console.info("Type: " + arr[instaCounter % window.data.instagram.liked.length].type);
				if (arr[instaCounter % window.data.instagram.liked.length].type == "video") {
					videourl = arr[instaCounter % window.data.instagram.liked.length]['videos']['standard_resolution']['url'];
					console.debug("That's an Instagram video: " + videourl);
					playInstagramVideo(videourl);
					/** @todo embed instaVideo  **/
				}
				else {
					// container.innerHTML = instaEmbedCode;
					// insta.src = url;
				}
				captionText = arr[instaCounter % window.data.instagram.liked.length]['caption']['text'];
				if (caption && captionText && captionText.length > 20) {
					// caption.textContent = removeTrailingHashtags(captionText);
					// console.info(captionText + " => ");
					// console.info(caption.textContent);
				}
				else {
					// caption.textContent = captionText;
				}
			}
			else {
				console.error("No insta found!");
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
			instaurl = "assets/php/instagram.php?cb=" + Math.random() * 10000, // cache-buster is needed
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
							// console.debug("received instagram list: " + data.data, data.data);
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