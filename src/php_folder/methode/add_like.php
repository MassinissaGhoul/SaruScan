<?php
session_start();
include_once("db.php");

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION["user"])) {
    header("Location: ../page/login.php");
    die();
}

if (!isset($_GET["id_comics"])) {
    die("No title provided.");
}

$id_comics = $_GET["id_comics"];

// Check if the user has already liked the comic
$verif_test = $pdo->prepare("SELECT * FROM rate WHERE id_comics = :id AND id_user = :user");
$verif_test->execute([
    ":user" => $_SESSION["user"]["id_user"],
    ":id" => $id_comics
]);

if ($verif_test->rowCount() != 0) {
    echo "You have already liked this comic.";
    header("Location: " . $_SERVER['HTTP_REFERER']);
    die();
} else {
    // Insert like
    $like_req = $pdo->prepare("INSERT INTO `rate`(`id_user`, `id_comics`, `rate`) VALUES (:user, :comics, :rate)");
    $like_req->execute([
        ":user" => $_SESSION["user"]["id_user"],
        ":comics" => $id_comics,
        ":rate" => 1
    ]);
    header("Location: " . $_SERVER['HTTP_REFERER']);
    die();
}
?>

