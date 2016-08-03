<?php


	define('RECENT_FILE',  __DIR__ . '/data/instagram-recent.json');
	define('JSON_FILE',  __DIR__ . '/data/instagram-liked.json');

	$userId = "1819276172";
	$clientId = "3c3f48734ad64fdabf398290304844e4";
	$clientSecret = "7bce280a31744da9b386e521d39b42e3";
	$redirectUri = "https://kreateam.io/html5/oya/admin/instagram-redirect.php";
	$code = "5b9b09f24893480493f096d2e10a0e4c";
	$accessToken = "179286384.3c3f487.ecb6f464967345dfbcca3266d5139094";

// https://instagram.com/oauth/authorize/?client_id=3c3f48734ad64fdabf398290304844e4&redirect_uri=http://localhost&response_type=token&scope=public_content

	$recent = "https://api.instagram.com/v1/media/popular?client_id={$clientId}";

	$nofilter = "https://api.instagram.com/v1/tags/nofilter/media/recent?client_id={$clientId}";

	$recent = "https://api.instagram.com/v1/tags/oyafestivalen/media/recent?access_token={$accessToken}";
	$tags = "https://api.instagram.com/v1/tags/oyafestivalen?access_token={$accessToken}";
	// https://api.instagram.com/v1/users/self/media/liked?access_token=ACCESS-TOKEN


	$liked = "https://api.instagram.com/v1/users/self/media/liked?access_token={$accessToken}";

	$feed = "https://api.instagram.com/v1/users/self/feed?access_token={$accessToken}";

// https://api.instagram.com/v1/tags/oya2015/media/recent?client_id=CLIENT-ID

	$json = file_get_contents("https://api.instagram.com/v1/users/self/media/liked?client_id=3c3f48734ad64fdabf398290304844e4&access_token=179286384.3c3f487.ecb6f464967345dfbcca3266d5139094&count=20");

	$jsonFileSize = 0;

	if (!file_exists(JSON_FILE)) {
		$jsonFileSize = 0;
	}
	else {
		$jsonFileSize = filesize(JSON_FILE);
	}


	if (!$json) {
		if (file_exists(JSON_FILE)) {
			echo file_get_contents(JSON_FILE);
		}
	}
	else {
		if (strlen($json) != $jsonFileSize) {
			$data = json_decode($json, true);
			if ($data['meta']['code'] == 200) {
				// write to cache file
				file_put_contents(JSON_FILE, $json);
			}
			else {

			}
		}

		echo "$json";
	}

?>