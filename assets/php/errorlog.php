<?php

	$fp = fopen("logs/error.log", "a");

	if ($fp) {
		fputs($fp, microtime(true) . ":" . $_SERVER['REQUEST_URI']."\n");
  	fclose($fp);
  }
  else {
    file_put_contents("logs/error.log", "no fp!", FILE_APPEND);
  }

  

?>