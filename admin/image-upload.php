<?php

	header('Content-Type: application/json');
	
	define('IMAGE_PATH', __DIR__ . '/../assets/php/data/files');
	define('IMAGE_LIBRARY', __DIR__ . '/../assets/php/data/images.json');

	define('IMAGE_THUMB', __DIR__ . '/../assets/php/data/thumbnail.jpg');

	define('IMAGE_LOG', __DIR__ . '/../assets/php/data/images.log');

	$status = "ok";

	if (!file_exists(IMAGE_PATH)) {
		if (!mkdir(IMAGE_PATH)) {
			$error = "Unable to create dir '" . IMAGE_PATH . "'";
		}
	}

	if (!file_exists(IMAGE_LIBRARY)) {
		file_put_contents(IMAGE_LIBRARY, "[");
	}


	function logIt($msg, $obj = null) {
		$loglines = "$msg\n";

		if ($obj !== null) {
			$loglines .= print_r($obj, true) . "\n";
		}
		file_put_contents(IMAGE_LOG, $loglines, FILE_APPEND);
	}

	$filecontent = file_get_contents($_FILES['image-upload']['tmp_name']);

	$exifdata = exif_read_data($_FILES['image-upload']['tmp_name'], NULL, false, true);
	$exifthumb = exif_read_data($_FILES['image-upload']['tmp_name'], NULL, false, true);

	$fileinfo = getimagesize($_FILES['image-upload']['tmp_name']);

	if (is_numeric($fileinfo[0]) && is_numeric($fileinfo[1])) {
		$width = $fileinfo[0];
		$height = $fileinfo[1];
	  $size = $width."x".$height;
	  $pixels = $width * $height;
	  logIt("We have fileinfo: ", $fileinfo);
	  if ($exifthumb) {
		  logIt("We have an exif thumbnail: ", $exifdata);
		  file_put_contents(IMAGE_THUMB, $exifdata['THUMBNAIL']['THUMBNAIL']);
	  }
	  if ($exifdata) {
		  logIt("We also have exif: ", $exifdata);
	  }
	  if ($width > 448) {
		  logIt("Should resize image: $size => ?");
	  }
	}



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
	  'user' => isset($_REQUEST['user']) ? $_REQUEST['user'] : "guest",
	  'uuid' => isset($_REQUEST['uuid']) ? $_REQUEST['uuid'] : "unknown-uuid-" . uniqid()
	);

	$data['title'] = isset($_REQUEST['title']) ? $_REQUEST['title'] : $name;
	$data['description'] = isset($_REQUEST['description']) ? $_REQUEST['description'] : "No description.";
	$data['tags'] = isset($_REQUEST['tags']) ? $_REQUEST['tags'] : "";
  $data['name'] = $name;
  $data['type'] = $filetype;
  $data['uri'] 	= $uri;
  $data['filename'] = $filename;
  $data['user'] = $reply['user'];
  $data['uuid'] = $reply['uuid'];

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