<?php

	header('Content-Type: application/json');

	define('PLAYLIST', './data/playlist.json');


 	error_reporting(E_ALL);

 	$reply = array();

 	$method = $_SERVER['REQUEST_METHOD'];

	$reply['method'] = $method;	


 	if ($method == "GET") {
 		$request = json_encode($_GET, true);
 	}
 	elseif ($method == "POST") {
 		$request = json_decode(file_get_contents("php://input"), true);
 	}

 	$reply['request'] = $request;


	$json = file_get_contents(PLAYLIST);

	$playlist = json_decode($json, true);

 	$reply['playlist'] = $playlist;

	// echo back current / updated playlist
	echo json_encode($reply, JSON_PRETTY_PRINT);


?>