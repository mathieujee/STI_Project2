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
  <title>Change password</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
  <script src="main.js"></script>
</head>

<body>
  	<form action="changePassword.php" method="post">
                Old Password <input type="password" name="old_password"/><br/>
                New Password <input type="password" name="new_password"/> <br/>
                <input type="submit" value="Change password"/>
		</form>

    <form action="target.php" method="post">
                <input type="submit" name ="getBack" value="Back to menu"/>
		</form>
<?php

if(isset($_POST['old_password']) and isset($_POST['new_password'])){

  class MyDB extends SQLite3 {
    function __construct() {
      $this->open('../databases/database.sqlite');
    }
  }

  $db = new MyDB();

  $newpassword = hash('sha256', strip_tags($_POST["new_password"]));
  $username = $_SESSION["username"];

  $query_change_password_user = 'UPDATE Users SET hashedPassword = :newPassword WHERE username LIKE :username';
  $query_verify_login = 'SELECT hashedPassword FROM Users WHERE username LIKE :username';
  
  $preparedStatement1 = $db->prepare($query_change_password_user);
  $preparedStatement2 = $db->prepare($query_verify_login);

  $preparedStatement1->bindParam(':newPassword', $newpassword);
  $preparedStatement1->bindParam(':username', $username);

  $preparedStatement2->bindParam('username', $username);

  $result = $preparedStatement2->execute();
  $data = $result->fetchArray();

  if(hash('sha256', strip_tags($_POST["old_password"])) === $data['hashedPassword'] ) {
    /* session is started if you don't write this line can't use $_Session  global variable */
    $preparedStatement1->execute();
    echo "Password changed";
  } else {
    echo "Wrong inputs";
  }
}
?>

</body>
</html>