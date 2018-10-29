<?php
/**
 * Created by PhpStorm.
 * User: jbis9051
 * Date: 8/28/18
 * Time: 4:56 PM
 */
date_default_timezone_set("America/New_York");
function my_autoloader($class) {
    include_once '/Library/Server/Web/Data/Sites/Default/class/' . $class . '.php';
}



spl_autoload_register('my_autoloader');



