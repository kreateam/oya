<?php

	define('IMAGE_PATH', __DIR__ . '/../assets/php/data/files');
	define('IMAGE_LIBRARY', __DIR__ . '/../assets/php/data/images.json');

	$status = "ok";

	$filecontent = file_get_contents($_FILES['image-upload']['tmp_name']);


	$dataUri 	= 'data:' . $filetype . ';base64,' . base64_encode($filecontent);
	$name 		= $_FILES['image-upload']['name'];
	$filetype = $_FILES['image-upload']['type'];

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
	  'username' => $_REQUEST['username'],
	  'uuid' => $_REQUEST['uuid']
	);

	$data['title'] = $_REQUEST['title'];
	$data['message'] = $_REQUEST['message'];
	$data['description'] = $_REQUEST['description'];
	$data['tags'] = $_REQUEST['tags'];
  $data['name'] = $name;
  $data['type'] = $filetype;
  $data['uri'] 		= $uri;
  $data['filename'] = $filename;
  $data['username'] = $_REQUEST['username'];
  $data['uuid'] = $_REQUEST['uuid'];

	file_put_contents(IMAGE_LIBRARY, "\n\t" . json_encode($data) . ",\n", FILE_APPEND);

	if (isset($error)) {
		$reply['error'] = "ERROR: " . $error;
	}
	else {
		$reply['status'] = $status;
	}


	$json = json_encode($reply);

	echo $json;
?>