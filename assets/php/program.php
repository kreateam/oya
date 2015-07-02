<?php


	define('CACHE_FILE', 			__DIR__ . "/data/program.json");
	define('CONTROL_FILE', 		__DIR__ . "/data/program.html");

	// cache for ten minutes between updates from YR.no API
	define('CACHE_TIME', 			600);

	define('PROGRAM_URL', 		"http://oyafestivalen.no/program/");


	function getProgram() {

		$result 	= false;
		$filetime = 0;

		if (file_exists(CACHE_FILE)) {
			$filetime = filemtime(CACHE_FILE);
		}

		$result = getFromServer();

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

		$html 	= file_get_contents(PROGRAM_URL);
		$result = array("debug" => array());
		$collect = false;
		$output = array();


		if ($html === false) {
			// error
			return false;
		}
		elseif (!$html) {
			// empty result
			return false;
		}
		else {
			try {
				file_put_contents(CONTROL_FILE, $html);
				if (false !== ($fp = fopen(CONTROL_FILE, "r"))) {
					while (!feof($fp)) {
						$line = fgets($fp);

						if (trim($line) == "<table>") {
							$collect = true;
						}
						elseif (trim($line) == "</table>") {
							array_push($output, $line);
							$collect = false;
						}
						if ($collect) {
							array_push($output, $line);
						}
					}
				}
		    $result['debug'][] = "Loading HTML : " . strlen($html);
		    $result['debug'][] = "Resulting size : " . sizeof($output);

				$sXml = json_decode(json_encode(simplexml_load_string(implode("", $output))), true);
		    $result['debug'][] = "Resulting XML size : " . sizeof($sXml);
		    // $result['debug'][] = print_r($sXml, true);

				if ($sXml === false) {
			    $result['debug'][] = "Failed loading HTML";
			    foreach(libxml_get_errors() as $error) {
			      $result['debug'][] = "HTML error: " . $error->message;
			    }
			    return false;
				}
				else {
					$result['program'] = $sXml;
		      $result['debug'][] = "Loaded HTML successfully.";
				}

				file_put_contents(CACHE_FILE, json_encode($result, JSON_PRETTY_PRINT));
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


	getProgram();

?>