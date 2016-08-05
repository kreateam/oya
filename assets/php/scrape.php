<?php

require 'vendor/autoload.php';
use InstagramScraper\Instagram;

$medias = Instagram::getMediasByTag('apøyablikk', 100);
echo json_encode($medias);

?>
