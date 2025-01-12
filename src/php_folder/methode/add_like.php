<?php
session_start();
include_once("db.php");

if (!isset($_SESSION["user"])) {
    header("Location: ../page/login.php");
    die();
}

if (!isset($_GET["id_comics"])) {
    die("No title provided.");
}

$id_comics = $_GET["id_comics"];
$id_user = $_SESSION["user"]["id_user"];

$verif_test = $pdo->prepare("SELECT * FROM rate WHERE id_comics = :id AND id_user = :user");
$verif_test->execute([
    ":user" => $id_user,
    ":id" => $id_comics
]);

if ($verif_test->rowCount() != 0) {
    $delete_like = $pdo->prepare("DELETE FROM rate WHERE id_comics = :id AND id_user = :user");
    $delete_like->execute([
        ":user" => $id_user,
        ":id" => $id_comics
    ]);
    $_SESSION["message"] = "Like retiré avec succès.";
} else {
    //on insère un like
    $like_req = $pdo->prepare("INSERT INTO rate (id_user, id_comics, rate) VALUES (:user, :comics, :rate)");
    $like_req->execute([
        ":user" => $id_user,
        ":comics" => $id_comics,
        ":rate" => 1
    ]);
    $_SESSION["message"] = "Comic liké avec succès.";
}

header("Location: " . $_SERVER['HTTP_REFERER']);
die();
?>
