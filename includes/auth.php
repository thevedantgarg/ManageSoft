<?php
require __DIR__.'/config.php';

if ($_SERVER['REQUEST_METHOD']==='POST') {
  $repNum = $_POST['repnum'];
  $pw     = $_POST['password'];

  $stmt = $db->prepare("
    SELECT RepNum, FirstName, LastName, Password
      FROM Rep
     WHERE RepNum = :r
  ");
  $stmt->execute([':r'=>$repNum]);
  $rep = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($rep && password_verify($pw, $rep['Password'])) {
    $_SESSION['rep_num']  = $rep['RepNum'];
    $_SESSION['rep_name'] = "{$rep['FirstName']} {$rep['LastName']}";
    header('Location: ../dashboard.php');
    exit;
  }
  $error = "Invalid RepNum or password.";
}
