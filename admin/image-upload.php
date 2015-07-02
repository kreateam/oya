<?php

	$fileName = $_FILES['image-upload']['name'];
	$fileType = $_FILES['image-upload']['type'];

	$fileContent = file_get_contents($_FILES['image-upload']['tmp_name']);

	$dataUri = 'data:' . $fileType . ';base64,' . base64_encode($fileContent);

	$json = json_encode(array(
	  'name' => $fileName,
	  'type' => $fileType,
	  'dataUri' => $dataUri,
	  'username' => $_REQUEST['username'],
	  'postId' => $_REQUEST['postId']
	));

	echo $json;
?>