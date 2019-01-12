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
    <form action="target.php" method="post">
                <input type="submit" name ="getBack" value="Back to menu"/>
		</form>

    <?php
  if(isset($_POST['reg_firstname']) and isset($_POST['reg_pass']) and isset($_POST["isActive"]) and isset($_POST["isAdmin"])) {

  class MyDB extends SQLite3 {
    function __construct() {
      $this->open('../databases/database.sqlite');
    }
  }
  
  $username = strip_tags($_POST["reg_firstname"]);
  $pass = hash('sha256', strip_tags($_POST['reg_pass']));
  $active = strip_tags($_POST['isActive']);
  $admin = strip_tags($_POST["isAdmin"]);

   $db = new MyDB();
   if(!$db) {
      echo $db->lastErrorMsg();
   } 

   if(($active == "1" or $active == "0") && ( $admin == "1" or  $admin == "0")){
   $query_update_user = 'UPDATE Users SET hashedPassword = :pass, active = :active, permissionLevel = :permissionLevel WHERE username LIKE :username';
   $preparedStatement_update_user = $db->prepare($query_update_user);
   $preparedStatement_update_user->bindParam(':pass', $pass);
   $preparedStatement_update_user->bindParam(':active', $active);
   $preparedStatement_update_user->bindParam(':permissionLevel', $admin);
   $preparedStatement_update_user->bindParam(':username', $username);

    try{
     $preparedStatement_update_user->execute();
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
