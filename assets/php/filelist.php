<?php

	header('Content-Type: application/json');

	$filelist = glob(__DIR__ . "/data/*.json");

	echo json_encode($filelist);

?>