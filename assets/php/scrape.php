<?php

require 'vendor/autoload.php';
use InstagramScraper\Instagram;

$medias = Instagram::getMediasByTag('apøyablikk', 300);
echo json_encode($medias);

?>
