<?php
require_once 'class.user.php';
session_start();
unset($_SESSION['success']);
$user = new USER();

if (!$user->is_logged_in()) {
  $user->redirect('index.php');
}

?>
<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="index.css" type="text/css">
  <link rel="stylesheet" href="home.css" type="text/css">
  <title>Camagru</title>
</head>

<body>
  <header>
    <h1 class="title">Welcome to Camagru</h1>
    <nav>
      <a class='btn' href="account.php">Account</a>
      <a class='btn' href="gallery.php">Go to gallery</a>
      <a class='btn' href="logout.php">Logout</a>
    </nav>
  </header>

  <form name="myForm" class='catRow' method="post">
    <label>
      <input type="radio" name="radio" id="cat1" value="cat1">
      <img src="./cat_filters/cat1.png" class="vignettas">
    </label>
    <label>
      <input type="radio" name="radio" id="cat2" value="cat2" />
      <img src="./cat_filters/cat2.png" class="vignettas">
    </label>
    <label>
      <input type="radio" name="radio" id="cat3" value="cat3" />
      <img src="./cat_filters/cat3.png" class="vignettas">
    </label>
    <label>
      <input type="radio" name="radio" id="cat4" value="cat4" />
      <img src="./cat_filters/cat4.png" class="vignettas">
    </label>
  </form>

  <div class="mainPlusSide">
    <section id="main">

      <div>

        <div id="img_uploaded"></div>
        <div id="ret_img_uploaded"></div>

        <div id="divPos">
          <video autoplay="true" id="videoElement"></video>
          <img id="filter" />
        </div>

        <div id="upload_part">
          <div id="divPos2">
            <img id="filter2" />
            <iframe name="frame" class="iframe_upload" id="home"></iframe>
            <?php if(file_exists("upload/gerard.png")) { ?>
              <script>
              window.setTimeout(() => {
              document.getElementById('home').contentWindow.document.write(
              "<html><body><img width='100%' height='auto' src='./upload/gerard.png' /></body></html>"
              ); }, 250);
              </script>
            <?php } ?>
            <form enctype="multipart/form-data" action="upload.php" method="POST" target="frame">
              <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
              <label for="userfile">Upload a file (max. 1 Mo) :</label><br />
              <input name="userfile" id="userfile" type="file" />
              <input type="submit" onclick="setTimeout(is_gege_exist(), 300);" value="Upload" />
            </form>
          </div>
        </div>

      </div>
      <button id="b" disabled class="btn cameraBtn" type="button">Take picture</button>
    </section>
    <aside id="sidebar"></aside>
  </div>
  <p id="mes"></p>

  <footer>
    <p class="mentions">© 2019 Copyright AnneLoutre</a></p>
  </footer>
  <script>
    var gege;

    function is_gege_exist() {
      var ajx = new XMLHttpRequest();
      ajx.onreadystatechange = function() {
          gege = this.responseText
          if (document.getElementById("filter2").style.visibility === "visible") {
            document.getElementById('b').disabled = false;
          } else document.getElementById('b').disabled = true;
      };
      ajx.open("POST", "is_gege_exist.php", true);
      ajx.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    }
    var tmp = 0;
    document.getElementById("filter").style.visibility = "hidden";
    document.getElementById("filter2").style.visibility = "hidden";

    var rad = document.myForm.radio;
    var prev = null;

    for (var i = 0; i < rad.length; i++) {
      rad[i].onclick = function() {
        if (this !== prev) {
          prev = this;
        }
        if (this.value == "cat1") {
          "<?php echo file_exists('./upload/gerard.png'); ?>" && true ? document.getElementById('b').disabled = false : '';
          document.getElementById("filter").src = "./cat_filters/cat1.png"
          document.getElementById("filter").style.visibility = "visible";
          document.getElementById("filter2") ? document.getElementById("filter2").src = "./cat_filters/cat1.png" : '';
          document.getElementById("filter2") ? document.getElementById("filter2").style.visibility = "visible" : '';
        } else if (this.value == "cat2") {
          document.getElementById('b').disabled = false;
          document.getElementById("filter").src = "./cat_filters/cat2.png"
          document.getElementById("filter").style.visibility = "visible";
          document.getElementById("filter2") ? document.getElementById("filter2").src = "./cat_filters/cat2.png" : '';
          document.getElementById("filter2") ? document.getElementById("filter2").style.visibility = "visible" : '';
        } else if (this.value == "cat3") {
          document.getElementById('b').disabled = false;
          document.getElementById("filter").src = "./cat_filters/cat3.png"
          document.getElementById("filter").style.visibility = "visible";
          document.getElementById("filter2") ? document.getElementById("filter2").src = "./cat_filters/cat3.png" : '';
          document.getElementById("filter2") ? document.getElementById("filter2").style.visibility = "visible" : '';
        } else if (this.value == "cat4") {
          document.getElementById('b').disabled = false;
          document.getElementById("filter").src = "./cat_filters/cat4.png"
          document.getElementById("filter").style.visibility = "visible";
          document.getElementById("filter2") ? document.getElementById("filter2").src = "./cat_filters/cat4.png" : '';
          document.getElementById("filter2") ? document.getElementById("filter2").style.visibility = "visible" : '';
        }
      };
    }

    var video = document.querySelector("#videoElement");
    var button = document.getElementById("b");

    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia ||
      navigator.mozGetUserMedia || navigator.msGetUserMedia || navigator.oGetUserMedia;

    if (navigator.getUserMedia) {
      navigator.getUserMedia({
        video: true
      }, handleVideo, videoError);
    }

    function handleVideo(stream) {
      document.getElementById("upload_part").remove()
      var cat;
      // video.src = window.URL.createObjectURL(stream);
      video.srcObject = stream;

      button.onclick = () => {
        var canvas = document.createElement('canvas');
        canvas.className = "canvas"
        canvas.height = 480
        canvas.width = 640
        canvas.getContext("2d").drawImage(video, 0, 0, 640, 480, 0, 0, 640, 480);
        var img = canvas.toDataURL("image/png");
        var mes = '';
        if (document.getElementById('cat1').checked) {
          cat = "cat1";
          document.getElementById("mes").innerHTML = '';
        } else if (document.getElementById('cat2').checked) {
          cat = "cat2";
          document.getElementById("mes").innerHTML = '';
        } else if (document.getElementById('cat3').checked) {
          cat = "cat3";
          document.getElementById("mes").innerHTML = '';
        } else if (document.getElementById('cat4').checked) {
          cat = "cat4";
          document.getElementById("mes").innerHTML = '';
        } else {
          mes = "Please select a cat before taking a photo";
          document.getElementById("mes").innerHTML = mes;
          return null;
        }

        var creds = "image=" + img + "&cat=" + cat;

        var ajx = new XMLHttpRequest();
        ajx.onreadystatechange = function() {
          if (ajx.readyState == 4 && ajx.status == 200) {
            var DOM_img = document.createElement("img");
            DOM_img.className = "lilImg";
            DOM_img.src = this.responseText;
            // document.getElementById("sidebar").appendChild(DOM_img);
            document.getElementById("sidebar").insertBefore(DOM_img, document.getElementById("sidebar").firstChild)
          }
        };
        ajx.open("POST", "merge.php", true);
        ajx.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajx.send(creds);
      }
    }

    function videoError(e) {
      tmp = 1;

      document.getElementById('divPos').style.height = 0;

      document.getElementById('upload_part').style.visibility = "visible";
      document.getElementById('upload_part').style.height = '100%';

      var DOM_img = document.createElement("img");
      DOM_img.className = "lilImg";

      document.getElementById("img_uploaded").appendChild(DOM_img);
      document.getElementById("videoElement").style.width = 0;


      var cat;
      button.onclick = () => {

        if (document.getElementById('cat1').checked) {
          document.getElementById("mes").innerHTML = '';
          cat = "cat1";
        } else if (document.getElementById('cat2').checked) {
          document.getElementById("mes").innerHTML = '';
          cat = "cat2";
        } else if (document.getElementById('cat3').checked) {
          document.getElementById("mes").innerHTML = '';
          cat = "cat3";
        } else if (document.getElementById('cat4').checked) {
          document.getElementById("mes").innerHTML = '';
          cat = "cat4";
        } else {
          var mes = "Please select a cat before taking a photo";
          document.getElementById("mes").innerHTML = mes;
          return null;
        }

        var creds = "&cat=" + cat;

        var ajx = new XMLHttpRequest();
        ajx.onreadystatechange = function() {
          if (ajx.readyState == 4 && ajx.status == 200) {
            var upload_img = document.createElement("img");
            upload_img.className = "lilImg";
            upload_img.src = this.responseText;
            document.getElementById("sidebar").appendChild(upload_img);
          }
        };
        ajx.open("POST", "merge_upload.php", true);
        ajx.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajx.send(creds);
      }
    }
  </script>
</body>

</html>