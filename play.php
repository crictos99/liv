<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Watch Live | SonyLIV</title>
  <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
  <style>
    body {
      margin: 0;
      background: #000;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }
    video {
      width: 100%;
      max-width: 1000px;
      border-radius: 10px;
      background: #000;
    }
  </style>
</head>
<body>
  <video id="video" controls autoplay></video>

  <script>
    const videoElement = document.getElementById('video');
    const urlParams = new URLSearchParams(window.location.search);
    const contentId = urlParams.get('id');
    const apiUrl = "https://raw.githubusercontent.com/drmlive/sliv-live-events/refs/heads/main/sonyliv.json";

    async function loadVideo() {
      try {
        const response = await fetch(apiUrl);
        const data = await response.json();
        const match = data.matches.find(m => m.contentId == contentId);
        if (!match || !match.dai_url) throw new Error("Stream not found");

        const streamUrl = match.dai_url;

        if (Hls.isSupported()) {
          const hls = new Hls();
          hls.loadSource(streamUrl);
          hls.attachMedia(videoElement);
        } else if (videoElement.canPlayType('application/vnd.apple.mpegurl')) {
          videoElement.src = streamUrl;
        } else {
          videoElement.outerHTML = "<p style='color:white;'>Your browser does not support HLS playback.</p>";
        }
      } catch (err) {
        videoElement.outerHTML = "<p style='color:white;'>Failed to load video stream.</p>";
        console.error(err);
      }
    }

    loadVideo();
  </script>
</body>
</html>
