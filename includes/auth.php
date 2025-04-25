<?php
// Turn on error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

require __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fn = trim($_POST['firstname']);
    $pw = $_POST['password'];

    // Trim the CHAR(15) field on lookup
    $stmt = $db->prepare("
      SELECT RepNum, FirstName, LastName, Password
        FROM Rep
       WHERE TRIM(FirstName) = :fn
       LIMIT 1
    ");
    $stmt->execute([':fn' => $fn]);
    $rep = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($rep) {
        $hash = $rep['Password'];
        // allow bcrypt hashes OR plain-text passwords
        if ( (strpos($hash, '$2y$') === 0 && password_verify($pw, $hash))
          || ($pw === $hash)
        ) {
            $_SESSION['rep_num']  = $rep['RepNum'];
            $_SESSION['rep_name'] = "{$rep['FirstName']} {$rep['LastName']}";
            header('Location: ../dashboard.php');
            exit;
        }
    }

    echo "<p class='error'>Invalid first name or password.</p>";
    echo "<p><a href=\"../index.php\">← Back to login</a></p>";
    exit;
}
