

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="downloader.css">
</head>
<body>
<div id="form">
    <form target="_none" id="form1" method="GET" action="download.php">
        <h2 class="error" id="error"></h2>
        <h1>Enter The YouTube URL Below</h1>
        <input id="url1" name="url" placeholder="YouTube URL" type="text" required><br>
        <h1>Choose The Format</h1>
        <small>MP3 is audio only, usually used for music. MP4 is video and audio.</small><br><br>
        <select name="format">
            <option value=mp3>mp3</option>
            <option value=mp4>mp4</option>
        </select>
    </form>
    <br>
    <br>
    <button onclick="submit()">Submit</button>
    <h2>Previous Requests (Not Saved):</h2>
    <ul id="past_list">
    </ul>
</div>
<script>
    function submit() {
        if(!document.getElementById("url1").value.length>0){
            document.getElementById("error").innerText = "Error: The URL is Blank";
        } else {
            document.getElementById("error").innerText = "";
            document.getElementById("past_list").innerHTML += "<li>"+document.getElementById("url1").value+"</li>";
            document.getElementById("form1").submit();
        }
    }
</script>
</body>
</html>