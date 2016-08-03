<?php


	define('NOWCAST_CACHE_FILE', 			__DIR__ . "/data/weather.nowcast.json");
	define('NOWCAST_CONTROL_FILE', 		__DIR__ . "/data/weather.nowcast.xml");

	// cache for n seconds between updates from YR.no API
	define('NOWCAST_CACHE_TIME', 			1);

	// path to SVG weather symbols
	define('NOWCAST_ICON_PATH', 			"assets/img/sym/svg/");

	define('NOWCAST_URL', 		"http://api.met.no/weatherapi/nowcast/0.9/?lat=59.9173873;lon=10.7774978");


	function getNowcast() {

		$result 	= false;
		$filetime = 0;

		if (file_exists(NOWCAST_CACHE_FILE)) {
			$filetime = filemtime(NOWCAST_CACHE_FILE);
		}

		$lastupdated = time() - $filetime;

		if ($lastupdated > NOWCAST_CACHE_TIME) {
			$result = getNCFromServer();
			if ($result === false) {

				// return cached if server lookup fails
				$result = getCachedNC();
			}
		}
		else {
			$result = getCachedNC();
		}

		return $result;
	}



	/**
	 * Get weather data from yr.no API
	 * 
	 * @return 	Array|bool 		Boolean FALSE on error, or associative array with weather data for the coming days on success
	 */
	function getNCFromServer() {

		$xml 					= file_get_contents(NOWCAST_URL);
		$result 			= array("debug" => array());

		if ($xml === false) {
			// error
			return false;
		}
		elseif (!$xml) {
			// empty result
			return false;
		}
		else {
			try {
		    $result['debug'][] = "Loading XML : " . strlen($xml);
		    $sJSON = json_encode(simplexml_load_string($xml));
				$sXml = json_decode($sJSON, true);
				$result['debug'][] = "JSON : " . $sJSON;

		    $result['debug'][] = "Resulting size : " . sizeof($sXml);

				if ($sXml === false) {
			    $result['debug'][] = "Failed loading XML";
			    foreach(libxml_get_errors() as $error) {
			      $result['debug'][] = "XML error: " . $error->message;
			    }
			    return false;
				}
				else {
		      $result['debug'][] = "Loaded XML successfully.";
				}

				if ($sXml && isset($sXml['product']['time']) && count($sXml['product']['time'])) {
		      $result['debug'][] = "ADDING nowcast";
					$result['nowcast'] = $sXml['product']['time'];
				}
				else {
		      $result['debug'][] = "NO nowcast FOUND";
				}

				$json = json_encode($sXml, JSON_PRETTY_PRINT);

				if ($sXml && isset($sXml['product']['time']) && count($sXml['product']['time'])) {
		      $result['debug'][] = "ADDING nowcast";
					$result['detailed'] = $sXml['product']['time'];
				}
				else {
		      $result['debug'][] = "NO nowcast FOUND";
				}

				file_put_contents(NOWCAST_CACHE_FILE, json_encode($result, JSON_PRETTY_PRINT));
				file_put_contents(NOWCAST_CONTROL_FILE, $xml);
				file_put_contents(NOWCAST_CONTROL_FILE.".json", $sJSON);
			}
			catch (Exception $e) {
				$result['debug'][] = get_class($e) . ": " . $e->getMessage();
				return false;
			}
			return $result;
		}
	}


	/**
	 * Get weather data from cache
	 * 
	 * @return 	Array 		Associative array with weather data for specified time periods
	 */
	function getCachedNC() {
		$array = json_decode(file_get_contents(NOWCAST_CACHE_FILE), true);
		return $array;
	}


?>