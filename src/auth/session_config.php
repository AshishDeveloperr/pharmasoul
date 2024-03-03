<?php
// session_start();
// if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
//   header("location: ../../auth/login.php");
//   exit;
// }else{  
 
//   date_default_timezone_set('Asia/Kolkata');

//   if (!isset($_SESSION['start'])) {
//       $_SESSION['start'] = time();
//   }

  
//   $today = strtotime('today');
//   $sundayExpiryTime = strtotime('next Sunday', $today); 

//   if (time() > $sundayExpiryTime) {
//       session_unset();
//       session_destroy();

//       header("Location: ../../auth/login.php");
//       exit();
//   }
// }


session_start();

// Check if user is not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] != true) {
  header("location: ../../auth/login.php");
  exit;
} else {  
  date_default_timezone_set('Asia/Kolkata');

  if (!isset($_SESSION['start'])) {
    $_SESSION['start'] = time();
  }


  $expiryTime = $_SESSION['start'] + (10 * 60 * 60); 
  
  if (time() > $expiryTime) {
    session_unset();
    session_destroy();

    header("Location: ../../auth/login.php");
    exit();
  }
}

?>