<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once '/Library/Server/Web/Data/Sites/Default/class/autoloader.php';

Video::cleanVidDir();
$the_video = new Video($_POST['url'],$_POST['format']);
echo($the_video->download());