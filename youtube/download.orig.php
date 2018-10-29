<?php

$path = 'videos/';
if ($handle = opendir($path)) {

    while (false !== ($file = readdir($handle))) { 
        $filelastmodified = filemtime($path . $file);
        //24 hours in a day * 3600 seconds per hour
        if((time() - $filelastmodified) > 3600)
        {
           unlink($path . $file);
        }

    }

    closedir($handle); 
}
$url = $_GET["url"];
$obj = json_decode(file_get_contents('https://www.youtube.com/oembed?format=json&url=https://www.youtube.com/watch?v='.$url), true);
$title=$obj["title"];
if ($title===null) {
$z=0;
//echo $obj["title"];
} else {
$z=1;
//echo $obj["title"];
//echo 'true';
}
if ($z===1) {
$code = rand(10000000,99999999);
if ($_GET["format"] === "mp4") {
//echo 'mp4';
//$command = escapeshellcmd ('/usr/local/bin/youtube-dl https://www.youtube.com/watch?v='.$url.' --no-check-certificate -e --get-title -f mp4 -o /Library/Server/Web/Data/Sites/Default/youtube/videos/'.$code.'.mp4');
$command2 = '/usr/local/bin/youtube-dl '.filter_var(('https://www.youtube.com/watch?v='.$url), FILTER_SANITIZE_URL).' --no-check-certificate -f mp4 -o /Library/Server/Web/Data/Sites/Default/youtube/videos/'.$code.'.mp4';
$name = $title.'.mp4';
$exe = '.mp4';
} else {
//echo 'mp3';
//$command = escapeshellcmd ('/usr/local/bin/youtube-dl https://www.youtube.com/watch?v='.$url.' --no-check-certificate -f mp4 --get-title -o /Library/Server/Web/Data/Sites/Default/youtube/videos/'.$code.'.mp4');
$command2 = '/usr/local/bin/youtube-dl \''.filter_var(('https://www.youtube.com/watch?v='.$url), FILTER_SANITIZE_URL).'\' --ffmpeg-location /usr/local/bin/ffmpeg --no-check-certificate -f mp4 -x --audio-format mp3 -o /Library/Server/Web/Data/Sites/Default/youtube/videos/'.$code.'.mp4';
$name = $title.'.mp3';
$exe = '.mp3';
}
//echo $command2;
shell_exec($command2);
$touchme = escapeshellcmd ('/usr/bin/touch /Library/Server/Web/Data/Sites/Default/youtube/videos/'.$code.$exe);
shell_exec($touchme);
} else {
echo 'INVALID URL';
}
?>
<!DOCTYPE>
<html>
<head>
</head>
<body>
<?php echo '<a href="videos/'.$code.$exe.'" download="'.trim(preg_replace('/\s+/', ' ', $name)).'">'; ?> 
  <button>Download!</button>
</a>
</body>
</html>
