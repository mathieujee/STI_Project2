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
       <title>Delete User</title>
       <meta charset="utf-8" />
   </head>
   <body>
		<h1>
			Delete a User<br />
		</h1>
		
		<form action="deleteUser.php" method="post">
				Username <input type="text" name="del_firstname"/><br/>
        <input type="submit" value="Delete User"/>
		</form>
    <form action="target.php" method="post">
                <input type="submit" name ="getBack" value="Back to menu"/>
		</form>

    <?php
  if(isset($_POST['del_firstname']) and !empty($_POST['del_firstname'])) {

  class MyDB extends SQLite3 {
    function __construct() {
      $this->open('../databases/database.sqlite');
    }
  }
  
  $username= htmlspecialchars($_POST["del_firstname"]);
  $db = new MyDB();

  $query_del_user = 'DELETE FROM Users WHERE username like :username';
  $preparedStatement = $db->prepare($query_del_user);
  $preparedStatement->bindParam(':username', $username);

  try{
    $preparedStatement->execute();
    } catch(Exception $e){
      echo "Wrong input";
    }
  }
    ?>
   </body>
</html>