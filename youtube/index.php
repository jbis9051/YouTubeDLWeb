<!DOCTYPE html>
<html>
<head>
    <title>YouTube Converter</title>
    <link rel="stylesheet" href="downloader.css">
    <script type="application/javascript" src="https://code.jquery.com/jquery-3.3.1.js"></script>
</head>
<body>
<div id="wrapper">
    <span class="title">Enter a valid URL</span>
    <span class="error" id="error"></span>
    <form target="_none" id="youtube_video_form">
        <div class="fakeInput">
            <input  id="url" class="urlInput" name="url" placeholder="YouTube URL" type="text" autocomplete="none">
            <div class="format_select">
                <div id="mp3" onclick="format_selector(this); this.setAttribute('data-selected','true')">mp3</div>
                <div id="mp4" onclick="format_selector(this); this.setAttribute('data-selected','true')">mp4</div>
            </div>
        </div>
        <input class="standard_button" type="submit" value="Convert">
        <input class="standard_button" type="button" onclick="download_all_new()" value="Download All New">
        <input type="hidden" name="format" id="format_hidden">
    </form>
    <div id="download_list" class="download_list_wrapper">
    </div>
</div>
<script>
    "use strict";
    let num_video = 0;
    let download_list = document.getElementById('download_list');

    function download_all_new() {
        for( const node of document.querySelectorAll('.download_item_wrapper') ) {
            if( node.dataset.progress === 'ready' ) {
                download(node);
            }
        }
    }

    function format_selector(format) {
        document.getElementById("format_hidden").value = format.innerText;
        if (format.innerText === "mp3") {
            document.getElementById("mp4").setAttribute('data-selected', 'false');
        } else {
            document.getElementById("mp3").setAttribute('data-selected', 'false');
        }
    }

    function download(element) {
        element.querySelector('.download_button').click();
    }

    /*
    <div class="download_item_wrapper">
            <span class="youtube_url">https://youtube.com/watch?v=skfjksd</span>
            <div class="loading_bar"></div>
            <button class="download_button" onclick="download(this.getAttribute('data-download_url'))">Download</button>
        </div>
     */

    $(function () {
        $('form').on('submit', function (e) {

            e.preventDefault();
            const this_vid_num = num_video++;
            const theURL = document.getElementById('url').value;
            let theTitle = theURL;
            let theThumbNail = "";
            $.getJSON( "cross-origin-bypass.php?url="+theURL, function( data ) {
                let this_element = document.getElementById(this_vid_num.toString());
                if(data.title!=null){
                    theTitle = data.title;
                    theThumbNail = data.thumbnail_url;
                    this_element.getElementsByClassName("youtube_url")[0].innerText = theTitle;
                    this_element.getElementsByClassName("thumbnail")[0].src = theThumbNail;
                    this_element.getElementsByClassName("thumbnail")[0].style.display = "block";
                }
            });
            const new_element = '' +
                '<div id="'+ this_vid_num +'" data-progress="loading" class="download_item_wrapper">\n' +
                '            <span class="youtube_url">' + theTitle + '</span><br>\n' +
                '<img style="display: none" class="thumbnail" height="125" src="'+theThumbNail+'">' +
                '            <div class="loading_bar"></div>\n' +
                '            <span style="display: none" class="error"></span>  ' +
                '<button class="download_button" data-youtube-url="'+theURL+'" onclick="this.parentElement.querySelector(\'.hidden_download\').click();this.parentElement.setAttribute(\'data-progress\',\'done\');">Download</button>\n' +
                '<a class="hidden_download" href="" download="'+theTitle+'"/>' +
                '</div>';
            let current_html = download_list.innerHTML;
            current_html+=new_element;
            download_list.innerHTML=current_html;
            $.ajax({
                type: 'post',
                url: 'convert.php',
                data: $('form').serialize(),
                success: function (data) {
                    data = JSON.parse(data);
                    let this_element = document.getElementById(this_vid_num.toString());
                    if(data.success){
                        this_element.setAttribute('data-download-url',data.downloadURL);
                        this_element.querySelector('.hidden_download').href = data.downloadURL;
                        this_element.getElementsByClassName("loading_bar")[0].style.display = "none";
                        this_element.getElementsByClassName("download_button")[0].style.display = "block";
                        this_element.setAttribute('data-progress','ready');
                    } else {
                        this_element.getElementsByClassName("loading_bar")[0].style.display = "none";
                        this_element.getElementsByClassName("error")[0].style.display = "inline-block";
                        this_element.getElementsByClassName("error")[0].innerText = "Error Downloading Video";
                        this_element.setAttribute('data-progress','fail');
                    }

                }
            });
            document.getElementById('url').value = "";
        });
    });
</script>
</body>
</html>