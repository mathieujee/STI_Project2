<?php
  error_reporting(0);
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
			Create an account<br />
		</h1>
		<form action="registration.php" method="post">
				Firstname <input type="text" name="reg_firstname"/><br/>
        Password <input type="text" name="reg_pass"/><br/>
        isActive <input type="radio" name="isActive"value="0">No
                 <input type="radio" name="isActive"value="1">Yes       <br/>
       isAdmin <input type="radio" name="isAdmin"value="0">No
                 <input type="radio" name="isAdmin"value="1">Yes       <br/>
        <input type="submit" value="Register"/>
		</form>
    <form action="target.php" method="post">
                <input type="submit" name ="getBack" value="Back to menu"/>
		</form>

    <?php
      if(isset($_POST['reg_firstname']) and isset($_POST['reg_pass']) and isset($_POST["isActive"]) and isset($_POST["isAdmin"])) {
        $password = strip_tags(trim($_POST['reg_pass']));
        
        if(strlen($password) < 8 ) {
          echo 'The password is to short: 8 chars min !';
        } 
        else if(!preg_match("#[0-9]+#", $password)) {
          echo 'The password must contain at least 1 number !"';
        }
        else if(!preg_match("#[A-Z]+#", $password)) {
          echo 'The password must contain as least 1 capital letter !';
        }
        else if(!preg_match("#[a-z]+#", $password)) {
          echo 'The password must contain at least 1 lowercase letter !';
        }
        
        else {
          class MyDB extends SQLite3 {
            function __construct() {
              $this->open('../databases/database.sqlite');
            }
          }
          
          $username= strip_tags($_POST["reg_firstname"]);
          $pass = hash('sha256', $password);
          $active = strip_tags($_POST['isActive']);
          $admin = strip_tags($_POST["isAdmin"]);

          $db = new MyDB();
          if(!$db) {
              echo $db->lastErrorMsg();
          } 

          if(($active == "1" or $active == "0") && ( $admin == "1" or  $admin == "0")) {
          
            $query_insert_user = 'INSERT INTO Users(username, hashedPassword, active, permissionLevel) VALUES (:username, :password, :active, :permissionLevel)';
            $preparedStatement = $db->prepare($query_insert_user);
            $preparedStatement->bindParam(':username', $username);
            $preparedStatement->bindParam(':password', $pass);
            $preparedStatement->bindParam(':active', $active);
            $preparedStatement->bindParam(':permissionLevel', $admin);
          
            try{
              $data = $preparedStatement->execute();
              echo "User created ";
            }catch(Exception $e){
              echo "wrong input(s)";
            }
          
          }
          
        }
      }
      else {
        echo "wrong input(s)";
      }
    ?>
   </body>
</html>