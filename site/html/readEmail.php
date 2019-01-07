<?php
    session_start();
    if(!isset($_SESSION['username']) or $_SESSION["active"] === 0 and !isset($_SESSION['readEmailFrom']) and
       !isset($_SESSION['readEmailTo']) and !isset($_SESSION['readEmailSubject']) and !isset($_SESSION['readEmailMessage']) and
       !isset($_SESSION['readEmailTime'])) {
        header("Location: index.php");
    }
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <title>Emails</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" type="text/css" media="screen" href="main.css" />
  <script src="main.js"></script>
</head>
<body>

    <?php
    echo '<h3 style="display: inline">FROM: </h3>' . $_SESSION['readEmailFrom'];
    echo '</br>';
    echo '<h3 style="display: inline">TO: </h3>' . $_SESSION['readEmailTo'];
    echo '</br>';
    echo '<h4 style="display: inline">Time: </h4>' . $_SESSION['readEmailTime'];
    echo '</br>';
    echo '<h4 style="display: inline">Subject: </h4>' . $_SESSION['readEmailSubject'];
    echo '</br>';
    echo '<h4 style="margin-top:0">Content:</h4>' . $_SESSION['readEmailMessage'];
    ?>

    <form action="target.php" method="post">
        <input type="submit" name ="getBack" value="Back to menu"/>
	</form>
    <form action="seeEmail.php" method="post">
        <input type="submit" name ="getBackToEmails" value="Back to emails"/>
	</form>
</body>
</html>