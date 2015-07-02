<?php


	define('CACHE_FILE', 			__DIR__ . "/data/weather.json");
	define('CONTROL_FILE', 		__DIR__ . "/data/weather.xml");

	// cache for ten minutes between updates from YR.no API
	define('CACHE_TIME', 			600);

	// path to SVG weather symbols
	define('ICON_PATH', 			"assets/img/sym/svg/");

	define('WEATHER_URL', 		"http://www.yr.no/sted/Norge/Oslo/Oslo/Oslo/varsel.xml");
	define('HOURBYHOUR_URL', 	"http://www.yr.no/sted/Norge/Oslo/Oslo/Oslo/varsel_time_for_time.xml");


	function getWeather() {

		$result 	= false;
		$filetime = 0;

		if (file_exists(CACHE_FILE)) {
			$filetime = filemtime(CACHE_FILE);
		}

		$lastupdated = time() - $filetime;

		if ($lastupdated > CACHE_TIME) {
			$result = getFromServer();
			if ($result === false) {

				// return cached if server lookup fails
				$result = getCached();
			}
		}
		else {
			$result = getCached();
		}

		return $result;
	}



	/**
	 * Get weather data from yr.no API
	 * 
	 * @return 	Array|bool 		Boolean FALSE on error, or associative array with weather data for the coming days on success
	 */
	function getFromServer() {

		$xml 					= file_get_contents(WEATHER_URL);
		$xmlDetailed 	= file_get_contents(HOURBYHOUR_URL);
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

				$sXml = json_decode(json_encode(simplexml_load_string($xml)), true);
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

				if ($sXml && isset($sXml['forecast']['tabular']['time']) && count($sXml['forecast']['tabular']['time'])) {
		      $result['debug'][] = "ADDING forecast";
					$result['forecast'] = $sXml['forecast']['tabular']['time'];
				}
				else {
		      $result['debug'][] = "NO forecast FOUND";
				}

				$json = json_encode($sXml, JSON_PRETTY_PRINT);

				$sXml = json_decode(json_encode(simplexml_load_string($xmlDetailed)), true);
				if ($sXml && isset($sXml['forecast']['tabular']['time']) && count($sXml['forecast']['tabular']['time'])) {
		      $result['debug'][] = "ADDING detailed forecast";
					$result['detailed'] = $sXml['forecast']['tabular']['time'];
				}
				else {
		      $result['debug'][] = "NO detailed forecast FOUND";
				}

				file_put_contents(CACHE_FILE, json_encode($result, JSON_PRETTY_PRINT));
				file_put_contents(CONTROL_FILE, $xml);
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
	function getCached() {
		$array = json_decode(file_get_contents(CACHE_FILE), true);
		return $array;
	}


?>