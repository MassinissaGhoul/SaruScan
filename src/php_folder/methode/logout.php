<?php
require_once("db.php");

session_start();
session_destroy(); // Détruit toutes les données de la session
header('Location: ../page/homepage.php'); // Redirige vers la page d'accueil
exit();
