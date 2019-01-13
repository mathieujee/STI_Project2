<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);

session_start();
?>

<!DOCTYPE html>
<html>
   <head>
       <title>Login</title>
       <meta charset="utf-8" />
       <script src='https://www.google.com/recaptcha/api.js'></script>
   </head>
   <body>
		<h1>
			Login<br />
		</h1>
		
		<form action="target.php" method="post">
                Username <input type="text" name="username"/>    <br/>
                Password <input type="password" name="password"/> <br/>
                <!--<div class="g-recaptcha" data-sitekey="6LflNYkUAAAAAE96kMp9qFlePkKItgub7wgwhveo"></div> <br/>-->
                <input type="submit" value="Login"/>
		</form>
    <?php
    if(isset($_SESSION["username"]) and $_SESSION["active"] === 0) {
      echo "Your account was disabled.";
    }
    session_destroy();
  ?>


   </body>
</html>
