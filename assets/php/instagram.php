<?php


	define('JSON_FILE',  __DIR__ . '/data/instagram-liked.json');


	$userId = "1819276172";
	$clientId = "833295ad243c4e71aef70011b62084fa";
	$clientSecret = "e074c2379dd7421dbd27f4e699a2f41e";
	$redirectUri = "http://kreateam.io:8080/html5/oya/admin/instagram-redirect.php";
	$code = "5b9b09f24893480493f096d2e10a0e4c";
	$accessToken = "179286384.833295a.9c62384173f74f0fa2b3f13017443a03";


	$recent = "https://api.instagram.com/v1/media/popular?client_id=833295ad243c4e71aef70011b62084fa";

	$nofilter = "https://api.instagram.com/v1/tags/nofilter/media/recent?client_id=833295ad243c4e71aef70011b62084fa";

	$recent = "https://api.instagram.com/v1/tags/oyafestivalen/media/recent?access_token=179286384.833295a.9c62384173f74f0fa2b3f13017443a03";
	$tags = "https://api.instagram.com/v1/tags/oyafestivalen?access_token=179286384.833295a.9c62384173f74f0fa2b3f13017443a03";
	// https://api.instagram.com/v1/users/self/media/liked?access_token=ACCESS-TOKEN


	$liked = "https://api.instagram.com/v1/users/self/media/liked?access_token=179286384.833295a.9c62384173f74f0fa2b3f13017443a03";

	$feed = "https://api.instagram.com/v1/users/self/feed?access_token=179286384.833295a.9c62384173f74f0fa2b3f13017443a03";

// https://api.instagram.com/v1/tags/oya2015/media/recent?client_id=CLIENT-ID

	$json = file_get_contents("https://api.instagram.com/v1/users/self/media/liked?access_token=179286384.833295a.9c62384173f74f0fa2b3f13017443a03");

	if (!$json) {
		echo file_get_contents(JSON_FILE);
	}
	else {
		if (strlen($json) != filesize(JSON_FILE)) {
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