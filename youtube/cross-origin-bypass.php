<?php
/**
 * Created by PhpStorm.
 * User: jbis9051
 * Date: 10/25/18
 * Time: 11:57 AM
 */

include_once '/Library/Server/Web/Data/Sites/Default/class/autoloader.php';

$the_video = new Video($_GET['url'],'mp3');
$json = $the_video->getJson();
echo json_encode($json);