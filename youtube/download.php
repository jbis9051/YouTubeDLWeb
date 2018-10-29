<?php
/**
 * Created by PhpStorm.
 * User: joshuabrown3
 * Date: 10/25/18
 * Time: 10:25 AM
 */


include_once '/Library/Server/Web/Data/Sites/Default/class/autoloader.php';


ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


$target = CheckDir::DoIt('/Library/Server/Web/Data/Sites/Default/youtube/videos/',  $_GET['code'] . '.' . $_GET['exe']);
    header('Content-Type: application/octet-stream');
  /*  header("Content-Transfer-Encoding: Binary"); */
    header("Content-disposition: attachment; filename=\"" . $_GET['name'] . '.' . $_GET['exe'] . "\"");
    header('Pragma: no-cache');
    header('Expires: 0');
    readfile($target);