<?php
include_once("header.php");
require_once("db.php");


// Vérification utilisateur connecté
if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit();
}
