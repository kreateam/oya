<?php

	$fp = fopen("logs/errorlog", "a");

	if ($fp) {
		fputs($fp, microtime(true) . ":" . $_SERVER['REQUEST_URI']."\n");
	}
	else {
		file_put_contents("logs/errorlogerror", "no fp!", FILE_APPPEND);
	}

	fclose($fp);

?>