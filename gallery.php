<?php
require_once 'class.user.php';
session_start();
$user = new USER();
if ($user->is_logged_in()) {
  $userID = $_SESSION['userID'];
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="index.css" type="text/css">
  <link rel="stylesheet" href="home.css" type="text/css">
  <title>Gallery</title>
</head>

<body>
  <header>
    <h1 class='title'>Gallery</h1>
  </header>
  <?php if ($user->is_logged_in()) { ?>

    <nav>
      <a class='btn' href="home.php">Back to home</a>
      <a class='btn' href="logout.php">Logout</a>
    </nav>

  <?php } else { ?>
    <nav>
      <a class='btn' href="index.php">Log in</a>
    </nav>
  <?php } ?>

  <?php
  $stmt = $user->runQuery('SELECT count(distinct pictureID) FROM pictures');
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_UNIQUE);
  $total = $row[0];

  $imagesPerPage = 8;
  $numberOfPages = ceil($total / $imagesPerPage);

  if (isset($_GET['page'])) {
    $currentPage = intval($_GET['page']);
    if ($currentPage > $numberOfPages) {
      $currentPage = $numberOfPages;
    }
  } else {
    $currentPage = 1;
  }
  $firstEntry = ($currentPage - 1) * $imagesPerPage;

  $ret_images = $user->runQuery('SELECT images, pictureID FROM pictures ORDER BY pictureID DESC LIMIT ' . $firstEntry . ', ' . $imagesPerPage . '');
  $ret_images->execute();

  echo '<div class="gallery_img_line">';

  while ($images_datas = $ret_images->fetch(PDO::FETCH_ASSOC)) {
    $src = $images_datas['images'];
    $pictureID = $images_datas['pictureID'];
    $id = $pictureID;

    $numberLikes = 0;

    $count_likes = $user->runQuery('SELECT count(distinct userID) FROM likes WHERE pictureID = :picture_id');
    $count_likes->bindparam(":picture_id", $pictureID);
    $count_likes->execute();
    $numberLikes = $count_likes->fetch(PDO::FETCH_ASSOC);
    $numberLikes = $numberLikes['count(distinct userID)'];

    if (!$numberLikes) {
      $numberLikes = 0;
    }

    $comments_db = $user->runQuery('SELECT * FROM comments WHERE pictureID = :picture_id');
    $comments_db->bindparam(":picture_id", $pictureID);
    $comments_db->execute();

    if (!$user->is_logged_in()) {
      $delete_img = $user->runQuery('SELECT * FROM pictures WHERE pictureID = :picture_id AND userID = :user_id');
      $delete_img->bindparam(":picture_id", $pictureID);
      $delete_img->bindparam(":user_id", $userID);
      $delete_img->execute();
      
      echo '<div class="gallery_img">';
      if ($delete_img->fetch(PDO::FETCH_ASSOC)) {
        echo '<button class="delete" onclick="deleteImage(event)" id=' . $id . ' value="Delete">&#10005; Delete Image &#8595</button>';
      } else {
        echo '<span class="no_delete"></span>';
      }
    } else {
      echo '<div class="gallery_img">';
      echo '<span class="no_delete"></span>';
    }

    echo '<img width=250 height=188 src=' . $src . '>';
    if ($user->is_logged_in()) {
      echo '
              <div class="likes_counter">
                <p class="number_likes">' . $numberLikes . '</p>
                <img class=like_icon onclick="onClickFunction(event);"  id=' . $id . ' width=25 height=30 src="./cat_filters/thumbUp.png">
              </div>
              <div class="sent_comment" id="sentComment_' . $id . '">

            ';
      while ($row = $comments_db->fetch(PDO::FETCH_ASSOC)) {
        echo '<div class="comment" id="sentComment_' . $id . '">' . htmlspecialchars($row['comment']) . '</div>';
      }
      echo '
              </div>
              <textarea class="writeComment" id="comment_' . $id . '" placeholder="Write your comment here....."></textarea>
              <button class="sendCommentBtn" onclick="addComment(event)" id=' . $id . ' value="Post Comment">Send comment</button>
              ';
    }
    echo    '
                </div>
              ';
  }
  echo '</div>';

  if ($numberOfPages > 0)
    echo '<p align="center" class="paginationContainer">Page : ';
  else {
    echo '<p class="noPictureYet" >
            No picture yet !
          </p>';
  }
  for ($i = 1; $i <= $numberOfPages; $i++) {
    if ($i == $currentPage)
      echo ' [ ' . $i . ' ] ';
    else
      echo ' <a href="gallery.php?page=' . $i . '">' . $i . '</a> ';
  }
  echo '</p>';

  ?>
  <script>
    function onClickFunction(event) {
      var saveButtonObject = event.target;
      var targetID = event.target.id;

      var postID = "targetID=" + targetID;
      var ajx = new XMLHttpRequest();
      ajx.onreadystatechange = function() {
        if (ajx.readyState == 4 && ajx.status == 200) {
          location.reload();
        }
      };
      ajx.open("POST", "like.php", true);
      ajx.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      ajx.send(postID);
    }

    function deleteImage(event) {
      var target3ID = event.target.id;
      var creds = "target3ID=" + target3ID;
      var ajx = new XMLHttpRequest();
      ajx.onreadystatechange = function() {
        if (ajx.readyState == 4 && ajx.status == 200) {
          location.reload();
        }
      };
      ajx.open("POST", "delete.php", true);
      ajx.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      ajx.send(creds);

    }

    function addComment(event) {
      var comment = document.getElementById("comment_" + event.target.id).value;
      if (comment) {
        var target2ID = event.target.id;
        var creds = "targetID2=" + target2ID + "&comment=" + comment;
        var ajx = new XMLHttpRequest();
        ajx.onreadystatechange = function() {
          if (ajx.readyState == 4 && ajx.status == 200) {
            document.getElementById("comment_" + target2ID).value = "";
            location.reload();
          }
        };
        ajx.open("POST", "comments.php", true);
        ajx.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajx.send(creds);
      }
      return false;
    }
  </script>

</body>

</html>