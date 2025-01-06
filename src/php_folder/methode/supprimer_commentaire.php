<?php
session_start();
require_once("../methode/db.php");

if (!isset($_SESSION["user"]) || $_SESSION["user"]["is_admin"] != 1) {
    header("Location: ../page/login.php");
    die();
}

if (!isset($_GET["comment_id"])) {
    echo "error";
    die();
}

$comment_id = $_GET["comment_id"];

$suppr_comm_req = $pdo->prepare("DELETE FROM comment WHERE comment_id = :id");
$suppr_comm_req->execute([':id' => $comment_id]);
header("Location:". $_SERVER['HTTP_REFERER']);
?>