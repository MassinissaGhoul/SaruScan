<?php
include_once("header.php");

// Vérification utilisateur connecté
if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit();
}
