<?php
/**
 * Created by PhpStorm.
 * User: jbis9051
 * Date: 10/25/18
 * Time: 7:58 AM
 */

final class Video
{
    private $url;
    private $format;
    private $title;
    private $code;
    private $json;

    private $path = 'videos/';
    private $video_path = '/Library/Server/Web/Data/Sites/Default/youtube/videos/'; // Absolute path of the directory above
    private $ffmpeg = '/usr/local/bin/ffmpeg'; // Path to ffmpeg; found by running `which ffmpeg`
    private $youtube_dl = '/usr/local/bin/youtube-dl';


    public function __construct($url, $format)
    {
        $data[0] = $url;
        $data[1] = $format;
        if(!Validator::youtube_url($data)){
            $theExport["success"] = false;
            $theExport["downloadURL"] = null;
            die(json_encode($theExport));
        }
        $this->format = $format;
        $this->url = $url;
        $this->code = bin2hex(random_bytes(6));
        $this->json = $this->retrieve_video_info($this->url);
        $this->title= $this->json["title"];
    }
    private function retrieve_video_info($url){
        $youtube_json_data = json_decode(file_get_contents('https://www.youtube.com/oembed?format=json&url=' . $url), true);
        return $youtube_json_data;
    }

    /**
     * @return mixed
     */
    public function getJson()
    {
        return $this->json;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->code;
    }

    public function download()
    {
        self::cleanVidDir();
        $command = "";
        if ($this->format == "mp4") {
            $command = $this->youtube_dl . ' ' . escapeshellarg(filter_var(($this->url), FILTER_SANITIZE_URL)) . ' --no-check-certificate -f mp4 -o ' . $this->video_path . $this->code . '.mp4';
        } else {
            $command = $this->youtube_dl . ' ' . escapeshellarg(filter_var(($this->url), FILTER_SANITIZE_URL)) . ' --ffmpeg-location ' . $this->ffmpeg . ' --no-check-certificate -f mp4 -x --audio-format mp3 -o ' . $this->video_path . $this->code . '.mp4';
        }
        shell_exec($command);
        $touchme = escapeshellcmd('/usr/bin/touch '. $this->video_path . $this->code .'.'. $this->format);
        shell_exec($touchme);
        $theExport["success"] = true;
        $theExport["downloadURL"] = 'download.php?code='.$this->code.'&exe='.urlencode($this->format).'&name='.urlencode(trim(preg_replace('/\s+/', ' ', $this->title)));
      return json_encode($theExport);
    }
    static public function cleanVidDir(){
        $path = 'videos/';
        if ($handle = opendir($path)) {
            while (!($file = readdir($handle))) {
                $filelastmodified = filemtime($path . $file);
                //24 hours in a day * 3600 seconds per hour
                if ((time() - $filelastmodified) > 3600) {
                    unlink($path . $file);
                }
            }
            closedir($handle);
        }
    }
}