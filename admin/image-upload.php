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

	if (!file_exists(IMAGE_LIBRARY)) {
		file_put_contents(IMAGE_LIBRARY, "[");
	}


	$filecontent = file_get_contents($_FILES['image-upload']['tmp_name']);


	$name 		= $_FILES['image-upload']['name'];
	$filetype = $_FILES['image-upload']['type'];
	$dataUri 	= 'data:' . $filetype . ';base64,' . base64_encode($filecontent);

	$filename =  time() . "_" . $name;

	$uri = "assets/php/data/files/" . $filename;


	if (!move_uploaded_file($_FILES['image-upload']['tmp_name'], IMAGE_PATH . "/" . $filename)) {
		$error = "Unable to move uploaded file into '" . IMAGE_PATH . "'";
	}
	else {
		$status = "Saved as '$filename'";
	}



	$reply = array(
	  'name' => $name,
	  'type' => $filetype,
	  'dataUri' => $dataUri,
	  'uri' 		=> $uri,
	  'filename' => $filename,
	  'user' => $_REQUEST['user'],
	  'uuid' => $_REQUEST['uuid']
	);

	$data['title'] = isset($_REQUEST['title']) ? $_REQUEST['title'] : $name;
	$data['description'] = $_REQUEST['description'];
	$data['tags'] = $_REQUEST['tags'];
  $data['name'] = $name;
  $data['type'] = $filetype;
  $data['uri'] 	= $uri;
  $data['filename'] = $filename;
  $data['user'] = $_REQUEST['user'];
  $data['uuid'] = $_REQUEST['uuid'];

  $reply['tags'] = $data['tags'];
  $reply['title'] = $data['title'];
  $reply['description'] = $data['description'];

  // add object to stunted JSON file
	file_put_contents(IMAGE_LIBRARY, "\n" . json_encode($data) . ",", FILE_APPEND);

	if (isset($error)) {
		$reply['error'] = "ERROR: " . $error;
	}
	else {
		$reply['status'] = $status;
	}

	$json = json_encode($reply);

	echo $json;
?>