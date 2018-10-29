<!DOCTYPE html>
<html>
<head>
</head>
<body>
<div id="form">
    <form target="_blank" id="form1" method="GET" action="convert.php">
        <input id="url1" name="url" placeholder="Enter Youtube URL Here" type="text" required>
        <select name="format">
            <option value=mp3>mp3</option>
            <option value=mp4>mp4</option>
        </select>
    </form>
    <button onclick="submit()">Submit</button>
</div>
<div>

</div>
<script>
    function submit() {
        var str = document.getElementById("url1").value;
        var res = str.replace("https://www.youtube.com/watch?v=", "");
        document.getElementById("url1").value = res;
        document.getElementById("form1").submit();
    }
</script>
</body>
</html>