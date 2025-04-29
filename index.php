<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><center>BankBridge Login</center></title>
  <link rel="stylesheet" href="assets/indexstyle.css">
</head>
<body>
  <div class="login">
    <h1>Manager Login</h1>
    <form method="POST" action="includes/auth.php">
      <label>
        First Name:
        <input type="text" name="firstname" required>
      </label>
      <br>
      <label>
        Password:
        <input type="password" name="password" required>
      </label>
      <br>  
      <button type="submit">Log In</button>
    </form>
  </div>
</body>
</html>
