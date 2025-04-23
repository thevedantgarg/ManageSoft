<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="assets/style.css">
  <title>BankBridge Login</title>
</head>
<body>
  <h1>Manager Login</h1>
  <form method="POST" action="includes/auth.php">
    <label>RepNum:<input name="repnum" required></label><br>
    <label>Password:<input type="password" name="password" required></label><br>
    <button type="submit">Log In</button>
  </form>
  <?php if(!empty($error)) echo "<p class='error'>$error</p>"; ?>
</body>
</html>
