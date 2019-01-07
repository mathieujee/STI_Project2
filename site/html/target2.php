<?php
session_start();
  if(!isset($_SESSION['username']) or $_SESSION["active"] == 0 or $_SESSION["level"] == 0) {
   header("Location: index.php");
  }
?>
 <html>
   <head>
       <title> Menu</title>
       <meta charset="utf-8" />
   </head>
   <body>
		<h1>
			<br />
		</h1>
		
		<form action="seeEmail.php" method="post">
      <input type="submit" value="see your mails"/>
    </form>
		<form action="sendEmail.php" method="post">
      <input type="submit" value="Write mails"/>
    </form>
		<form action="changePassword.php" method="post">
      <input type="submit" value="Change your password"/>
    </form>
    <form action="deleteUser.php" method="post">              
      <input type="submit" value="Delete user"/>
    </form>
    <form action="updateUser.php" method="post">
      <input type="submit" value="Update user info"/>
    </form>
    <form action="registration.php" method="post">
      <input type="submit" value="Add a new user"/>
    </form>
    <form action="logout.php" method="post">
      <input type="submit" value="Logout"/>
    </form>
  </body>
</html>