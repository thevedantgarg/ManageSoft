<?php
session_start();
try {
  $db = new PDO(
    'mysql:host=localhost;dbname=CFG;charset=utf8',
    'root',''
  );
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("DB Connection failed: ".$e->getMessage());
}