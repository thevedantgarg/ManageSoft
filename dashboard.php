<?php
require __DIR__.'/includes/config.php';
if (!isset($_SESSION['rep_num'])) {
  header('Location: index.php');
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="assets/styles.css">
  <title>ManageSoft Dashboard</title>
</head>
<body>
  <div class="Header">
<h1>Welcome, <?=htmlspecialchars($_SESSION['rep_name'])?>!</h1>
</div>
  
<h2 id="report1">1. Customers per Representative</h2>
<?php
$sql = "
  SELECT r.RepNum, r.FirstName, r.LastName,
         COUNT(c.CustomerNum) AS num_customers,
         AVG(c.Balance)      AS avg_balance
    FROM Rep r
    LEFT JOIN Customer c ON c.RepNum = r.RepNum
   GROUP BY r.RepNum
";
$rows = $db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>
<table>
  <tr><th>Representative Number</th><th>Name</th><th># of Customers</th><th>Average Balance</th></tr>
  <?php foreach($rows as $r): ?>
    <tr>
      <td><?=$r['RepNum']?></td>
      <td class="custName"><?=htmlspecialchars($r['FirstName'].' '.$r['LastName'])?></td>
      <td><?=$r['num_customers']?></td>
      <td class="red-text"><?=number_format($r['avg_balance'],2)?></td>
    </tr>
  <?php endforeach; ?>
</table>
<div class="report-2">
<h2 id="report2">2. Total Order Value</h2>
<form method="GET" class="custNumForm">
  Customer Number: <input name="custnum" required> <br>
  <button>Go</button>
</form>
<?php if(!empty($_GET['custnum'])):
  $stmt = $db->prepare("
    SELECT SUM(ol.QuotedPrice * ol.NumOrdered) AS total_value
      FROM OrderLine ol
      JOIN Orders o ON o.OrderNum = ol.OrderNum
     WHERE o.CustomerNum = :c
  ");
  $stmt->execute([':c'=>$_GET['custnum']]);
  $tot = $stmt->fetchColumn();
?>
  <p>Total for <?=htmlspecialchars($_GET['custnum'])?>: $<?=number_format($tot,2)?></p>
<?php endif; ?>
</div>
<div class="Add-rep">
<h2 id="addrep">3. Add New Representative</h2>
<form method="POST" action="#addrep" class="addRepForm">
  <!-- inputs: repnum, firstname, lastname, street, city, state, postal, commission, rate, password -->
  <label>Representative Number:   <input type="text"     name="repnum"      required></label><br>
  <label>First Name:   <input type="text"     name="firstname"   required></label><br>
  <label>Last Name:    <input type="text"     name="lastname"    required></label><br>
  <label>Street:       <input type="text"     name="street"      required></label><br>
  <label>City:         <input type="text"     name="city"        required></label><br>
  <label>State:        <input type="text"     name="state"       maxlength="2" required></label><br>
  <label>Postal Code:  <input type="text"     name="postal"      maxlength="5" required></label><br>
  <label>Commission:   <input type="number"   name="commission"  step="0.01"    required></label><br>
  <label>Rate:         <input type="number"   name="rate"        step="0.01"    required></label><br>
  <label>Password:     <input type="password" name="password"    required></label><br>
  <button type="submit">Add Representative</button>
</form>
<?php
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['repnum'])) {
  $pwhash = password_hash($_POST['password'], PASSWORD_BCRYPT);
  $db->prepare("
    INSERT INTO Rep
      (RepNum,FirstName,LastName,Street,City,State,PostalCode,Commission,Rate,Password)
    VALUES (?,?,?,?,?,?,?,?,?,?)
  ")->execute([
    $_POST['repnum'], $_POST['firstname'], $_POST['lastname'],
    $_POST['street'],  $_POST['city'],      $_POST['state'],
    $_POST['postal'],  $_POST['commission'],$_POST['rate'],
    $pwhash
  ]);
  echo "<p>New Representative {$_POST['repnum']} added.</p>";
}
?>
</div>
<div class="upd-credit">
<h2 id="updcredit">4. Update Customer Credit</h2>
<form method="POST" action="#updcredit">
        Customer Number: <input name="custnum" required> <br>
        New Limit:    <input name="newlimit" type="number" step="0.01" required>
  <br> <button>Update</button>
</form>
<?php
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['custnum'])) {
  $db->prepare("
    UPDATE Customer
       SET CreditLimit = :lim
     WHERE CustomerNum = :c
  ")->execute([
    ':lim'=>$_POST['newlimit'],
    ':c'  =>$_POST['custnum']
  ]);
  echo "<p>Credit for Customer {$_POST['custnum']} updated.</p>";
}
?>

</div>
</body>
</html>
