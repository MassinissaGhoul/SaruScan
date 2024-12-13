<?php
session_start();
require_once("../methode/db.php");

if (!isset($_SESSION["user"])) {
    header("Location: ../page/login.php");
    die();
}
$mon_user = $_SESSION["user"]["id_user"];

if (!isset($_GET["chapter_path"])) {
    echo "error";
    die();
}

$chapter_path = $_GET["chapter_path"];
$parent_id = $_GET["parent"] ?? null;
// Récupérer l'ID du comics
$id_req = $pdo->prepare("SELECT id_chapter, id_comics from chapter where comics_path = :p");
$id_req->execute([':p' => $chapter_path]);
$mon_id = $id_req->fetch();
if (!$mon_id) {
    echo "chapter not found";
    die();
}
$id = $mon_id["id_chapter"];

// Récupérer les données du formulaire
$commentaire = $_POST["comment"];

// Insérer le commentaire ou la réponse
$envoie_req = $pdo->prepare("
    INSERT INTO comment (user_id, chapter_id, comment, parent_id) 
    VALUES (:user, :chapter_id, :comment, :parent_id)
");
$envoie_req->execute([
    ':user' => $mon_user,
    ':chapter_id' => $id,
    ':comment' => $commentaire,
    ':parent_id' => $parent_id,
]);

header("Location: ../page/test.php?manga_id=" . $mon_id["id_comics"] . "&chapter_path=" . $chapter_path);
?>
