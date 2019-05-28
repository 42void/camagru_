<?php

class USER{
 private $pdo;

 public function __construct(){
   require(__DIR__.'/config/database.php');
   $this->pdo = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
 }

 public function runQuery($sql){
  $stmt = $this->pdo->prepare($sql);
  return $stmt;
 }

 public function lastID(){
  $stmt = $this->pdo->lastInsertId();
  return $stmt;
 }

 public function register($uname,$email,$upass,$code){
    try{
     $password = hash('whirlpool', $upass);
     $stmt = $this->runQuery("INSERT INTO tbl_users(userName,userEmail,userPass,tokenCode)
                                                  VALUES(:user_name, :user_mail, :user_pass, :active_code)");
     $stmt->bindparam(":user_name",$uname);
     $stmt->bindparam(":user_mail",trim(strtolower($email)));
     $stmt->bindparam(":user_pass",$password);
     $stmt->bindparam(":active_code",$code);
     $stmt->execute();
     return $stmt;
    }
    catch(PDOException $ex){
     echo $ex->getMessage();
    }
 }

 public function recordPicture($ret, $userID){
   try{
     $stmt = $this->runQuery("INSERT INTO pictures(images, userID) VALUES(:image_url, :user_id)");
     $stmt->bindparam(":image_url", $ret);
     $stmt->bindparam(":user_id", $userID);
     $stmt->execute();
  }
  catch(PDOException $ex){
     echo $ex->getMessage();
  }
 }

 public function recordLikes($pictureID, $userID){
   try{
    $stmt = $this->runQuery("SELECT * FROM likes WHERE pictureID = :picture_id AND userID = :user_id ");
    $stmt->bindparam(":picture_id",$pictureID);
    $stmt->bindparam(":user_id",$userID);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row){
        $stmt2 = $this->runQuery("INSERT INTO likes(pictureID, userID) VALUES(:picture__id, :user__id)");
        $stmt2->bindparam(":picture__id", $pictureID);
        $stmt2->bindparam(":user__id", $userID);
        $stmt2->execute();
    }
  }
  catch(PDOException $ex){
     echo $ex->getMessage();
  }
 }

 public function recordComments($pictureID, $userID, $comment){
    try{
        $stmt3 = $this->runQuery("INSERT INTO comments(pictureID, userID, comment) VALUES(:picture_id, :user_id, :comment)");
        $stmt3->bindparam(":picture_id", $pictureID);
        $stmt3->bindparam(":user_id", $userID);
        $stmt3->bindparam(":comment", $comment);
        $stmt3->execute();
    }
    catch(PDOException $ex){
       echo $ex->getMessage();
    }
 }

 public function delete($pictureID, $userID){
   try{
       $stmt3 = $this->runQuery("DELETE FROM pictures WHERE pictureID=:picture_id AND userID=:user_id");
       $stmt3->bindparam(":picture_id", $pictureID);
       $stmt3->bindparam(":user_id", $userID);
       $stmt3->execute();
   }
   catch(PDOException $ex){
      echo $ex->getMessage();
   }
 }

 public function login($email,$upass){
  try{
   $stmt = $this->runQuery("SELECT * FROM tbl_users WHERE userEmail=:email_id");
   $stmt->bindparam(":email_id", trim(strtolower($email)));
   $stmt->execute();
   $userRow=$stmt->fetch(PDO::FETCH_ASSOC);
   $result = $stmt->fetchAll();
   $error = "";
   if($userRow){
      if($userRow['userStatus']=="Y"){
         if($userRow['userPass']==hash('whirlpool',$upass)){
          $_SESSION['userSession'] = $userRow['userID'];
          return true;
         }
         else{
          header("Location: index.php?error");
          exit;
         }
      }
      else{
       header("Location: index.php?inactive");
       exit;
      }
   }
   else{
     header("Location: index.php?error");
     exit;
   }
  }
  catch(PDOException $ex){
   echo $ex->getMessage();
  }
 }

 public function is_logged_in(){
  if(isset($_SESSION['userSession'])){
   return true;
  }
 }

 public function redirect($url){
  header("Location: $url");
 }

 public function logout(){
  session_destroy();
  $_SESSION['userSession'] = false;
 }

 function send_mail($email,$message,$subject){
      try {
			// $message = 'Hello';
			// $headers  = 'MIME-Version: 1.0' . "\r\n";
			// $headers .= 'Content-Type: text/plain; charset="iso-8859-1"'."\n";
			// $headers .='Content-Transfer-Encoding: 8bit';
			mail($email, $subject, $message);
         // mail($email, $subject, $message, null, '-fal.vanhoegaerden@gmail.com');
         echo "<script>console.log('email " .$email. "' );</script>";
         echo "<script>console.log('subject " .$subject. "' );</script>";
         echo "<script>console.log('message " .$message. "' );</script>";
         // echo "<script>console.log('retour email " .mail($email, $subject, $message). "' );</script>";

      } catch (PDOException $ex) {
         echo $ex->getMessage();
      }
   }
}
