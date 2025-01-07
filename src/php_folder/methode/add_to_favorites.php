<?php
session_start();
require_once '../methode/db.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id_user'])) {
  echo "<script>alert('Vous devez être connecté pour ajouter un favori.'); window.location.href = '../page/login.php';</script>";
  exit;
}

$id_user = intval($_SESSION['id_user']);
$id_comics = isset($_POST['comic_id']) && is_numeric($_POST['comic_id']) ? intval($_POST['comic_id']) : null;

// Vérifier que l'ID de l'utilisateur est valide
$stmt = $pdo->prepare("SELECT id_user FROM user WHERE id_user = ?");
$stmt->execute([$id_user]);
if ($stmt->rowCount() === 0) {
  echo "<script>alert('Utilisateur invalide.'); window.history.back();</script>";
  exit;
}

// Vérifier que l'ID du comic est valide
$stmt = $pdo->prepare("SELECT id_comics FROM comics WHERE id_comics = ?");
$stmt->execute([$id_comics]);
if ($stmt->rowCount() === 0) {
  echo "<script>alert('Comic invalide.'); window.history.back();</script>";
  exit;
}

// Vérifier si le comic est déjà en favoris
$stmt = $pdo->prepare("SELECT * FROM favorite WHERE id_user = ? AND id_comics = ?");
$stmt->execute([$id_user, $id_comics]);

if ($stmt->rowCount() > 0) {
  echo "<script>alert('Ce comic est déjà dans vos favoris.'); window.history.back();</script>";
  exit;
}

// Ajouter le comic en favoris
$stmt = $pdo->prepare("INSERT INTO favorite (id_user, id_comics) VALUES (?, ?)");
if ($stmt->execute([$id_user, $id_comics])) {
  echo "<script>alert('Comic ajouté aux favoris !'); window.history.back();</script>";
} else {
  echo "<script>alert('Erreur lors de l\'ajout aux favoris.'); window.history.back();</script>";
}