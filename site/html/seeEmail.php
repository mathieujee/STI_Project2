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
  session_start();
  if(!isset($_SESSION['username'])) {
    header("Location: index.php");
  }
?>

<?php

  $emailsPerPage = 4;

  if(isset($_GET['page'])) {
    $page = strip_tags(trim($_GET['page']));
    
    if($page < 1 OR !is_numeric($page)) {
      $page = 1;
    }
  }
  else {
    $page = 1;
  }

  $startFrom = ($page-1) * $emailsPerPage;

  class MyDB extends SQLite3 {
    function __construct() {
      $this->open('../databases/database.sqlite');
    }
  }

   $db = new MyDB();
   $db->busyTimeout(100);

   $username = $_SESSION['username'];
   $emailsID = array();

   
   //// SQLite queries ////
   $query_email_user = 'SELECT rowid, * FROM Messages WHERE emailTo LIKE :username ORDER BY timeDate DESC LIMIT :startFrom, :emailsPerPage';
   $query_count_rows = 'SELECT COUNT(*) as count FROM Messages WHERE emailTo LIKE :username';
  /////////////////////////

  $preparedStatement_email_user = $db->prepare($query_email_user);
  $preparedStatement_email_user->bindParam(':username', $username);
  $preparedStatement_email_user->bindParam(':startFrom', $startFrom);
  $preparedStatement_email_user->bindParam(':emailsPerPage', $emailsPerPage);

  $preparedStatement_count_rows = $db->prepare($query_count_rows);
  $preparedStatement_count_rows->bindParam(':username', $username);

   $ret_email_user = $preparedStatement_email_user->execute();

   while($data = $ret_email_user->fetchArray(SQLITE3_ASSOC)){
    $id = $data['rowid'];
    array_push($emailsID, $id);
    echo 'FROM : ' . $data['emailFrom'] . '</br>';
    echo 'TO : ' . $data['emailTo'] . '</br>';
    echo 'Timestamp : ' . $data['timeDate'] . '</br>';
    echo 'Subject : ' . $data['subject'] . '</br>';
    echo "<form method='POST' action=''>";
    echo '<input type="submit" name="read'.$id.'" value="Read"/>'; 
    echo '<input type="submit" name="answer'.$id.'" value="Answer"/>'; 
    echo '<input type="submit" name="delete'.$id.'" value="Delete"/>';
    echo "</form>";
    echo '</br></br>';
  } 
  

  // Pagination (10 emails per page)
  $ret_count_rows = $preparedStatement_count_rows->execute();
  $totalRows = $ret_count_rows->fetchArray(SQLITE3_ASSOC);
  $totalRows = $totalRows['count'];
  $totalPages = ceil($totalRows / $emailsPerPage);
  $pageLink = "<div class='pagination'>";
  for($i = 1; $i <= $totalPages; $i++) {
    $pageLink .= "<a href='seeEmail.php?page=" . $i . "'>" . $i . "</a> ";
  }
  echo $pageLink . "</div>";

  // handle buttons
  for($i = 0; $i < count($emailsID); $i++) {
    $emailID = $emailsID[$i];

    /* handle 'read' */
    if(isset($_POST["read$emailID"])) { 
      $query_select_email_by_id = 'SELECT * FROM Messages WHERE rowid = :emailID';
      $preparedStatement_select_email = $db->prepare($query_select_email_by_id);
      $preparedStatement_select_email->bindParam(':emailID', $emailID);
      $ret_select_email = $preparedStatement_select_email->execute();
      $data = $ret_select_email->fetchArray(SQLITE3_ASSOC);

      $_SESSION['readEmailFrom'] = $data['emailFrom'];
      $_SESSION['readEmailTo'] = $data['emailTo'];
      $_SESSION['readEmailSubject'] = $data['subject'];
      $_SESSION['readEmailMessage'] = $data['message'];
      $_SESSION['readEmailTime'] = $data['timeDate'];
      header("Location: readEmail.php");
    }

    /* handle 'answer' */
    else if(isset($_POST["answer$emailID"])) { 
      $query_from_email = 'SELECT emailTo, subject FROM Messages WHERE rowid = :emailID';
      $preparedStatement_select_emailTo = $db->prepare($query_from_email);
      $preparedStatement_select_emailTo->bindParam(':emailID', $emailID);

      $ret_select_emailTo = $preparedStatement_select_emailTo->execute();
      $data = $ret_select_emailTo->fetchArray(SQLITE3_ASSOC);

      $_SESSION["emailTo"] = $data["emailTo"];
      $_SESSION["subject"] = "RE:".$data["subject"];     
      header("Location: sendEmail.php");
    }

    /* handle 'delete' */
    else if(isset($_POST["delete$emailID"])) {
      $query_delete_email = 'DELETE FROM Messages WHERE rowid = :emailID';
      $preparedStatement_delete_email = $db->prepare($query_delete_email);
      $preparedStatement_delete_email->bindParam(':emailID', $emailID);
      $preparedStatement_delete_email->execute();

     echo "<meta http-equiv='refresh' content='0'>"; // refresh the page
  }
}

  $db->close();
?>
  <form action="target.php" method="post">
        <input type="submit" name ="getBack" value="Back to menu"/>
	</form>
</body>
</html>