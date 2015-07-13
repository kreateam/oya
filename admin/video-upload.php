<?php

	$fileName = $_FILES['video-upload']['name'];
	$fileType = $_FILES['video-upload']['type'];

	// $fileContent = file_get_contents($_FILES['video-upload']['tmp_name']);

	$json = json_encode(array(
	  'name' => $fileName,
	  'type' => $fileType,
	  'dataUri' => $dataUri,
	  'filesize' => filesize($_FILES['video-upload']['tmp_name']),
	  'username' => $_REQUEST['username'],
	  'postId' => $_REQUEST['postId']
	));

	echo $json;
?>