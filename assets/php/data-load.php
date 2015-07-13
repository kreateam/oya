<?php

	/**
	 * Load stunted json
	 */
	header('Content-Type: application/json');


	$filelist = glob(__DIR__ . "/data/*.json");

	// echo json_encode($filelist);


	define('DATA_PATH', __DIR__ . '/data');
	define('IMAGE_PATH', __DIR__ . '/data/files');
	define('IMAGE_LIBRARY', __DIR__ . '/data/images.json');

	define('DEFAULT_FILE', DATA_PATH . '/settings.json');

	$status = "ok";



	if (!isset($_REQUEST['file'])) {
		$error = "No file specified";
		$filename = DEFAULT_FILE;
	}
	elseif (file_exists($filename = (DATA_PATH . "/" . $_REQUEST['file']))) {
		// $filename = DATA_PATH . "/" . $_REQUEST['file'];
	}
	else {
		die("File not found: " . $filename . "\n" . print_r($_REQUEST, true));
		$filename = DEFAULT_FILE;
	}


	if (!file_exists($filename)) {
		$error = "File not found: '$filename'";
	}


	function loadStuntedJson($filename) {
		if (!file_exists($filename)) {
			return false;
		}

		return trim(file_get_contents($filename), "\n\t,"). "]";
	}

	if (isset($error)) {
		$reply = json_encode(array('error' => "ERROR: " . $error));
	}
	else {
		$reply = loadStuntedJson($filename);
	}


	echo $reply;
?>