<?php

	header('Content-Type: application/json');

	define('IMAGE_PATH', __DIR__ . '/../assets/php/data/files');
	define('IMAGE_LIBRARY', __DIR__ . '/../assets/php/data/images.json');

	$status = "ok";
	$result = false;

	$DEBUG = array();

	if (!file_exists(IMAGE_PATH)) {
		if (!mkdir(IMAGE_PATH)) {
			$error = "Unable to create dir '" . IMAGE_PATH . "'";
		}
	}

	$reply = $_REQUEST;

	// $json = file_get_contents("php://input");

	// $request = json_decode($json);

	$title = $_REQUEST['title'];
	$filename =  $_REQUEST['filename'];
	$uri = "assets/php/data/files/" . $filename;
	$tags = $_REQUEST['tags'];
	$description = $_REQUEST['description'];
	$uuid =  $_REQUEST['uuid'];

	$DEBUG[] = "POST data:";
	$DEBUG[] = "Title : $title";
	$DEBUG[] = "filename : $filename";
	$DEBUG[] = "uri : $uri";
	$DEBUG[] = "uuid : $uuid";
	$DEBUG[] = "tags : $tags";
	$DEBUG[] = "description : $description";

	// {"title":"20130624-120321.jpg","tags":"","description":"","filename":"1436880181_20130624-120321.jpg","submit":"Save"}


	function loadStuntedJson($filename) {
		if (!file_exists($filename)) {
			return false;
		}

		return trim(file_get_contents($filename), "\n\t,"). "]";
	}

	function saveStuntedJson($filename, $data) {
		return file_put_contents($filename, rtrim(json_encode($data, JSON_PRETTY_PRINT), "\n\t]") . ",");
	}


	$data = json_decode(loadStuntedJson(IMAGE_LIBRARY), true);

	foreach ($data as &$image) {
		if ($image['filename'] == $filename) {
				$result = true;
				$image['title'] = $title;
				// $image['filename'] =  $filename;
				$image['uri'] = $uri;
				$image['tags'] = $tags;
				$image['description'] = $description;
				if (isset($image['uuid'])) {
					$reply['uuid'] = $image['uuid'];
					$DEBUG[] = "FOUND THE uuid: ". $image['uuid'];
				}
				$DEBUG[] = "Found it: $filename";
		}
	}

	if ($result) {
		$DEBUG[] = "saving stunted JSON";
		saveStuntedJson(IMAGE_LIBRARY, $data);
	}
	else {
		$DEBUG[] = "error: no result. '$result'";
	}


	if (isset($error)) {
		$reply['error'] = "ERROR: " . $error;
		$DEBUG[] = "Error : " . $error;
	}
	else {
		$reply['status'] = $status;
		$reply['uri'] = $uri;
		$DEBUG[] = "Status : " . $status;
	}

	$reply['DEBUG'] = $DEBUG;


	// addDebugInfo(IMAGE_LIBRARY);

	$json = json_encode($reply);

	echo $json;
?>