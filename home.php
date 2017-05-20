<?php
  session_start();
  require_once 'class.user.php';
  $user_home = new USER();

  if(!$user_home->is_logged_in()){
   $user_home->redirect('index.php');
  }

?>
<!DOCTYPE html>
<html>

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="HomeStyle.css" type="text/css">
    </head>

    <body>
      <header>
        <h1>Welcome to Camagru</h1>
      </header>

          <nav>
            <a href="gallery.php">Go to gallery</a>
            <a href="logout.php">Logout</a>
          </nav>

          <!-- CATS -->
          <form name="myForm" class='tinyCats' method="post" >
            <label>
              <input type="radio" name="radio" id="cat1" value="cat1">
              <img src="./stackable_pics/cat1.png" class="vignettas" >
            </label>
            <label>
              <input type="radio" name="radio" id="cat2" value="cat2" />
              <img src="./stackable_pics/cat2.png" class="vignettas">
            </label>
            <label>
              <input type="radio" name="radio" id="cat3" value="cat3" />
              <img src="./stackable_pics/cat3.png" class="vignettas">
            </label>
            <label>
              <input type="radio" name="radio" id="cat4" value="cat4" />
              <img src="./stackable_pics/cat4.png" class="vignettas">
            </label>
          </form>
          <!-- FIN CATS -->
          <p id="mes"></p>

          <section id="main">
              <div>

                  <!-- IMG UPLOADED -->
                  <div id="img_uploaded"></div>
                  <div id="ret_img_uploaded"></div>

                  <!-- VIDEO -->

                  <div id="divPos">
                    <video autoplay="true" id="videoElement" ></video>
                    <img id="ImageKeying" />
                  </div>

                  <!-- UPLOAD -->
                  <div id="upload_part">

                    <div id="iframeContainer">
                      <iframe name="fouloulou" id="home" ></iframe>
                    </div>

                    <form enctype="multipart/form-data" action="upload.php" method="post" target="fouloulou">
                      <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                      <label for="userfile">Upload a file (max. 1 Mo) :</label><br />
                      <input name="userfile" id="userfile" type="file" />
                      <input type="submit" value="OK" />
                    </form>
                  </div>

              </div>
              <hr />
              <button id="b" disabled=true class="takePictureButton" type="button" >Take picture</button>

          </section>

          <aside id="sidebar">
              <!-- <canvas></canvas> -->
          </aside>

    <footer>
      <p class="mentions">© 2016 Copyright AnneLoutre</a></p>
    </footer>
    <script>
        var tmp = 0;
        document.getElementById("ImageKeying").style.visibility = "hidden";
        var rad = document.myForm.radio;
        var prev = null;

        for(var i = 0; i < rad.length; i++) {
            rad[i].onclick = function() {
                // (prev)? console.log(prev.value):null;
                if(this !== prev) {
                    prev = this;
                }
                if(this.value == "cat1"){
                  document.getElementById('b').disabled=false;
                  document.getElementById("ImageKeying").src = "./stackable_pics/cat1.png"
                  document.getElementById("ImageKeying").style.visibility = "visible";
                }
                else if(this.value == "cat2"){
                  document.getElementById('b').disabled=false;
                  document.getElementById("ImageKeying").src = "./stackable_pics/cat2.png"
                  document.getElementById("ImageKeying").style.visibility = "visible";
                }
                else if(this.value == "cat3"){
                  document.getElementById('b').disabled=false;
                  document.getElementById("ImageKeying").src = "./stackable_pics/cat3.png"
                  document.getElementById("ImageKeying").style.visibility = "visible";

                }
                else if(this.value == "cat4"){
                  document.getElementById('b').disabled=false;
                  document.getElementById("ImageKeying").src = "./stackable_pics/cat4.png"
                  document.getElementById("ImageKeying").style.visibility = "visible";

                }
            };
        }

        var video = document.querySelector("#videoElement");
        var button = document.getElementById("b");

        navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia
        || navigator.mozGetUserMedia || navigator.msGetUserMedia || navigator.oGetUserMedia;

        if (navigator.getUserMedia) {
          navigator.getUserMedia({video: true}, handleVideo, videoError);
        }

        function handleVideo(stream) {

          var cat;
          video.src = window.URL.createObjectURL(stream);
          button.onclick = () => {
            var canvas = document.createElement('canvas');
            canvas.className = "canvas"
            canvas.height = 480
            canvas.width = 640
            canvas.getContext("2d").drawImage(video, 0, 0, 640, 480, 0, 0, 640, 480);
            var img = canvas.toDataURL("image/png");

            if(document.getElementById('cat1').checked){
              cat = "cat1";
            }
            else if(document.getElementById('cat2').checked){
              cat = "cat2";
            }
            else if(document.getElementById('cat3').checked){
              cat = "cat3";
            }
            else if(document.getElementById('cat4').checked){
              cat = "cat4";
            }
            else{
              var mes = "Please select a cat before taking a photo";
              document.getElementById("mes").innerHTML = mes;
              return null;
            }

            var creds = "image="+img+"&cat="+cat;

            var ajx = new XMLHttpRequest();
            ajx.onreadystatechange = function () {
                if (ajx.readyState == 4 && ajx.status == 200) {
                    var DOM_img = document.createElement("img");
                    DOM_img.className = "lilImg";
                    DOM_img.src = this.responseText;
                    document.getElementById("sidebar").appendChild(DOM_img);
                }
            };
            ajx.open("POST", "merge.php", true);
            ajx.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajx.send(creds);
          }


        }
        function videoError(e) {
          tmp = 1;

          document.getElementById('divPos').style.height=0;

          document.getElementById('upload_part').style.visibility="visible";
          document.getElementById('upload_part').style.height='100%';

          var DOM_img = document.createElement("img");
          DOM_img.className = "lilImg";

          document.getElementById("img_uploaded").appendChild(DOM_img);
          document.getElementById("videoElement").style.width = 0;


          var cat;
          button.onclick = () => {

             if(document.getElementById('cat1').checked){
              document.getElementById("mes").innerHTML = '';
              cat = "cat1";
            }
            else if(document.getElementById('cat2').checked){
              document.getElementById("mes").innerHTML = '';
              cat = "cat2";
            }
            else if(document.getElementById('cat3').checked){
              document.getElementById("mes").innerHTML = '';
              cat = "cat3";
            }
            else if(document.getElementById('cat4').checked){
              document.getElementById("mes").innerHTML = '';
              cat = "cat4";
            }
            else{
              var mes = "Please select a cat before taking a photo";
              document.getElementById("mes").innerHTML = mes;
              return null;
            }

            var creds = "&cat="+cat;

            var ajx = new XMLHttpRequest();
            ajx.onreadystatechange = function () {
                if (ajx.readyState == 4 && ajx.status == 200) {

                    var upload_img = document.createElement("img");
                    upload_img.className = "lilImg";
                    upload_img.src = this.responseText;
                    document.getElementById("sidebar").appendChild(upload_img);
                }
            };
            ajx.open("POST", "merge2.php", true);
            ajx.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            ajx.send(creds);
          }
        }


    </script>
    </body>

</html>
