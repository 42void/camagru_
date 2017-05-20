<?php
 if (isset($_REQUEST['email']))  {

  $admin_email = "al.vanhoegaerden@gmail.com";
  $email = $_REQUEST['email'];
  $subject = $_REQUEST['subject'];
  $comment = $_REQUEST['comment'];

  mail($admin_email, "$subject", $comment, "From:" . $email);

  echo "Thank you for contacting us!";
}

else  {
  ?>

  <form method="post">
    Email: <input name="email" type="text" /><br />
    Subject: <input name="subject" type="text" /><br />
    Message:<br />
    <textarea name="comment" rows="15" cols="40"></textarea><br />
    <input type="submit" value="Submit" />
  </form>

  <?php
}
?>
