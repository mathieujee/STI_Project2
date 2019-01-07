<?php
  session_start();
  if(!isset($_SESSION['username']) or $_SESSION["active"] === 0) {
    header("Location: index.php");
  }
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Message to send</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
  <script src="main.js"></script>
</head>
<body>
<form action ="sendEmail.php" method="post">
        To <input type="text" name="to" value=<?php
        if(isset($_SESSION["emailTo"])){
          echo $_SESSION["emailTo"];
          unset($_SESSION["emailTo"]);
        }else{
          echo "";
        }
        ?> >            <br/>
        Subject <input type="text" name="object" value=<?php
        if(isset($_SESSION["subject"])){
          echo $_SESSION["subject"];
          unset($_SESSION["subject"]);
        }else{
          echo "";
        }
        ?>>     <br/>
         <textarea type="text" name="email_text"></textarea>
         <br/>
       <input type="submit" value="Send"/>
	</form>

  <form action="target.php" method="post">
                <input type="submit" name ="getBack" value="Back to menu"/>
		</form>
 <?php

  if(isset($_POST['to']) and isset($_POST['object']) and isset($_POST["email_text"])) {

  class MyDB extends SQLite3 {
    function __construct() {
      $this->open('../databases/database.sqlite');
    }
  }

  $username=$_SESSION["username"];
  $to = $_POST['to'];
  $object = $_POST['object'];
  $email_text = $_POST["email_text"];
  $now = date('Y-m-d H:i:s');

   $db = new MyDB();

   $query_insert_email =<<<EOF
   INSERT INTO Messages (emailFrom,emailTo,subject,message,timeDate) VALUES ('$username','$to','$object','$email_text','$now');
EOF;


  $db->exec($query_insert_email);
   echo "Email successfully sent.";
  }
  ?>
</body>
</html>