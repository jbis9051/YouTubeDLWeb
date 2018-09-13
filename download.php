<?php

/* Config */
$path = 'videos/';
$video_path = '/Library/Server/Web/Data/Sites/Default/youtube/videos/'; // Absolute path of the directory above
$ffmpeg = '/usr/local/bin/ffmpeg'; // Path to ffmpeg; found by running `which ffmpeg`
$youtube_dl =  '/usr/local/bin/youtube-dl';

if ($handle = opendir($path)) {

    while (false !== ($file = readdir($handle))) {
        $filelastmodified = filemtime($path . $file);
        //24 hours in a day * 3600 seconds per hour
        if ((time() - $filelastmodified) > 3600) {
            unlink($path . $file);
        }

    }

    closedir($handle);
}
$url = $_GET["url"];
$format = $_GET["format"];
$parts = parse_url($url);
$rx = '~
  ^(?:https?://)?                           # Optional protocol
   (?:www[.])?                              # Optional sub-domain
   (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
   ([^&]{11})                               # Video id of 11 characters as capture group 1
    ~x';
if ((!preg_match($rx, $url)) || ($parts["host"] != "youtube.com" && $parts["host"] != "www.youtube.com")) {
    die("Error: Invalid URL");
}

if ($format != "mp3" && $format != "mp4") {
    die("Error: Invalid Format");
}
$obj = json_decode(file_get_contents('https://www.youtube.com/oembed?format=json&url='.$url), true);
$title = $obj["title"];
if ($title === null) {
    die("Error: Invalid URL");
}
$code = rand(10000000, 99999999);
if ($_GET["format"] === "mp4") {
    $command = $youtube_dl.' ' . escapeshellarg(filter_var(($url), FILTER_SANITIZE_URL)) . ' --no-check-certificate -f mp4 -o '.$video_path . $code . '.mp4';
    $name = $title . '.mp4';
    $exe = '.mp4';
} else {
    $command = $youtube_dl.' ' . escapeshellarg(filter_var(($url), FILTER_SANITIZE_URL)) . ' --ffmpeg-location '.$ffmpeg.' --no-check-certificate -f mp4 -x --audio-format mp3 -o '.$video_path . $code . '.mp4';
    $name = $title . '.mp3';
    $exe = '.mp3';
}
shell_exec($command);
$touchme = escapeshellcmd('/usr/bin/touch '. $video_path . $code . $exe);
shell_exec($touchme);
?>
<!DOCTYPE>
<html>
<head>
    <link rel="stylesheet" href="downloader.css">
</head>
<body>
<div id="form">
<h1>Thanks for using the Brown Server YouTube Downloader</h1>
<h2>Click below to finish your download</h2>
<a href="videos/<?php echo $code . $exe . '" download="' . trim(preg_replace('/\s+/', ' ', $name)); ?>">
    <button>Download!</button>
</a>
</div>
</body>
</html>
