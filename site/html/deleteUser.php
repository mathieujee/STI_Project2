<?php
session_start();
  if(!isset($_SESSION['username']) or $_SESSION["active"] == 0 or $_SESSION["level"] == 0) {
   header("Location: index.php");
  }
?>



<!DOCTYPE html>
<html>
   <head>
       <title>Registration</title>
       <meta charset="utf-8" />
   </head>
   <body>
		<h1>
			Update an account<br />
		</h1>
		
		<form action="deleteUser.php" method="post">
				Firstname <input type="text" name="del_firstname"/><br/>
        <input type="submit" value="Delete User"/>
		</form>
    <form action="target2.php" method="post">
                <input type="submit" name ="getBack" value="Back to menu"/>
		</form>

    <?php
  if(isset($_POST['del_firstname'])) {

  class MyDB extends SQLite3 {
    function __construct() {
      $this->open('../databases/database.sqlite');
    }
  }
  
  $username=$_POST["del_firstname"];
   $db = new MyDB();

   $query_del_user =<<<EOF
   DELETE FROM Users WHERE username like '$username';
EOF;

    try{
     $db->exec($query_del_user);
    }catch(Exception $e){
      echo "Wrong input";
    }
  }
    ?>
   </body>
</html>