<?php
session_start();
?>

<!DOCTYPE html>
<html>
   <head>
       <title>Login</title>
       <meta charset="utf-8" />
   </head>
   <body>
		<h1>
			Login<br />
		</h1>
		
		<form action="target.php" method="post">
                Username <input type="text" name="username"/>    <br/>
                Password <input type="password" name="password"/> <br/>
                <input type="submit" value="Login"/>
		</form>
    <?php
    if(isset($_SESSION["username"]) and $_SESSION["active"] === 0) {
      echo "Votre compte est désactivé.";
    }
    session_destroy();
  ?>


   </body>
</html>
