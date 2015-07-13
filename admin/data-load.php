<?php

	/**
	 * Load stunted json
	 */
	header('Content-Type: application/json');


	$filelist = glob(__DIR__ . "/data/*.json");

	// echo json_encode($filelist);


	define('DATA_PATH', __DIR__ . '/../assets/php/data');
	define('IMAGE_PATH', __DIR__ . '/../assets/php/data/files');
	define('IMAGE_LIBRARY', __DIR__ . '/../assets/php/data/images.json');

	define('DEFAULT_FILE', DATA_PATH . '/settings.json');

	$status = "ok";

	echo "OK";

	$filename =  DATA_PATH . $_REQUEST['file'];

	if (!file_exists($filename)) {
		$error = "File not found: '$filename'";
	}


	function loadStuntedJson($filename) {
		if (!file_exists($filename)) {
			return false;
		}

		return trim(file_get_contents($filename), ","). "]";
	}

	if (isset($error)) {
		$reply = json_encode(array('error' => "ERROR: " . $error));
	}
	else {
		$reply = $status;
	}

	$reply = loadStuntedJson(IMAGE_LIBRARY);

	echo $reply;
?>