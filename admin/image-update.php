<?php

	header('Content-Type: application/json');

	define('IMAGE_PATH', __DIR__ . '/../assets/php/data/files');
	define('IMAGE_LIBRARY', __DIR__ . '/../assets/php/data/images.json');

	$status = "ok";

	if (!file_exists(IMAGE_PATH)) {
		if (!mkdir(IMAGE_PATH)) {
			$error = "Unable to create dir '" . IMAGE_PATH . "'";
		}
	}


	$name = $_REQUEST['name'];
	$filename =  $_REQUEST['filename'];
	$uri = "assets/php/data/files/" . $filename;




	function loadStuntedJson($filename) {
		if (!file_exists($filename)) {
			return false;
		}

		return trim(file_get_contents($filename), "\n\t,"). "]";
	}

	// file_put_contents(IMAGE_LIBRARY, "\n\t" . json_encode($data) . ",\n", FILE_APPEND);

	if (isset($error)) {
		$reply['error'] = "ERROR: " . $error;
	}
	else {
		$reply['status'] = $status;
	}


	$json = json_encode($_REQUEST);

	echo $json;
?>