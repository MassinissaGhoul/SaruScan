<?php
session_start();
require_once("../methode/db.php");

if (!isset($_SESSION["user"])) {
    header("Location: ../page/login.php");
    die();
}
$mon_user = $_SESSION["user"]["id_user"];

if (!isset($_GET["title"])) {
    echo "error";
    die();
}

$title = $_GET["title"];
$parent_id = $_GET["parent"] ?? null;
// Récupérer l'ID du comics
$id_req = $pdo->prepare("SELECT id_comics FROM comics WHERE title_comics = :t");
$id_req->execute([':t' => $title]);
$mon_id = $id_req->fetch();
if (!$mon_id) {
    echo "Comics not found";
    die();
}
$id = $mon_id["id_comics"];

// Récupérer les données du formulaire
$commentaire = $_POST["comment"];

// Insérer le commentaire ou la réponse
$envoie_req = $pdo->prepare("
    INSERT INTO comment (user_id, comics_id, comment, parent_id) 
    VALUES (:user, :comics_id, :comment, :parent_id)
");
$envoie_req->execute([
    ':user' => $mon_user,
    ':comics_id' => $id,
    ':comment' => $commentaire,
    ':parent_id' => $parent_id,
]);

header("Location: ../page/comics_page.php?title=" . $title);
?>
