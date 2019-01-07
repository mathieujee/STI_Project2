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
    $page = $_GET['page'];
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
   $query_email_user =<<<EOF
   SELECT rowid, * FROM  Messages WHERE emailTo LIKE '$username' ORDER BY timeDate DESC LIMIT '$startFrom', '$emailsPerPage';
EOF;

  $query_count_rows =<<<EOF
  SELECT COUNT(*) as count FROM Messages WHERE emailTo LIKE '$username';
EOF;
  /////////////////////////
   $ret = $db->query($query_email_user);

   while($data = $ret->fetchArray(SQLITE3_ASSOC)){
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
  $totalRows = $db->query($query_count_rows);
  $totalRows = $totalRows->fetchArray(SQLITE3_ASSOC);
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
      $query_select_email_by_id =<<<EOF
      SELECT * FROM Messages WHERE rowid = $emailID;
EOF;
      $ret = $db->query($query_select_email_by_id);
      $data = $ret->fetchArray(SQLITE3_ASSOC);

      $_SESSION['readEmailFrom'] = $data['emailFrom'];
      $_SESSION['readEmailTo'] = $data['emailTo'];
      $_SESSION['readEmailSubject'] = $data['subject'];
      $_SESSION['readEmailMessage'] = $data['message'];
      $_SESSION['readEmailTime'] = $data['timeDate'];
      header("Location: readEmail.php");
    }

    /* handle 'answer' */
    else if(isset($_POST["answer$emailID"])) { 
      $query_from_email =<<<EOF
      SELECT emailTo, subject FROM Messages WHERE rowid = $emailID;
EOF;
      $ret = $db->query($query_from_email);
      $data = $ret->fetchArray(SQLITE3_ASSOC);

      $_SESSION["emailTo"] = $data["emailTo"];
      $_SESSION["subject"] = "RE:".$data["subject"];     
      header("Location: sendEmail.php");
    }

    /* handle 'delete' */
    else if(isset($_POST["delete$emailID"])) {
      $query_delete_email =<<<EOF
      DELETE FROM Messages WHERE rowid = $emailID;
EOF;
   $db->exec($query_delete_email);
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