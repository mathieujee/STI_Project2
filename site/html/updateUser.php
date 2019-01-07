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
		
		<form action="updateUser.php" method="post">
				Firstname <input type="text" name="reg_firstname"/><br/>
        Password <input type="text" name="reg_pass"/><br/>
        isActive <input type="radio" name="isActive"value="0">No
                 <input type="radio" name="isActive"value="1">Yes       <br/>
  isAdmin <input type="radio" name="isAdmin"value="0">No
    <input type="radio" name="isAdmin"value="1">Yes       <br/>
        <input type="submit" value="Update"/>
		</form>
    <form action="target2.php" method="post">
                <input type="submit" name ="getBack" value="Back to menu"/>
		</form>

    <?php
  if(isset($_POST['reg_firstname']) and isset($_POST['reg_pass']) and isset($_POST["isActive"]) and isset($_POST["isAdmin"])) {

  class MyDB extends SQLite3 {
    function __construct() {
      $this->open('../databases/database.sqlite');
    }
  }
  
  $username=$_POST["reg_firstname"];
  $pass = $_POST['reg_pass'];
  $active = $_POST['isActive'];
  $admin = $_POST["isAdmin"];

  echo $active;
  echo $admin;
   $db = new MyDB();
   if(!$db) {
      echo $db->lastErrorMsg();
   } else {
      echo "Opened database successfully\n" . '</br>';
   }

   if(($active == "1" or $active == "0") && ( $admin == "1" or  $admin == "0")){
   $query_update_user =<<<EOF
   UPDATE Users SET hashedPassword='$pass', active='$active', permissionLevel='$admin' WHERE username like '$username';
EOF;

    try{
     $db->exec($query_update_user);
    }catch(Exception $e){
      echo "Wrong input";
    }

   }else{
    echo "Wrong input ";
   }
  }
    ?>
   </body>
</html>
