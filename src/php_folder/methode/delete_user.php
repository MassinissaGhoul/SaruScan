<?php
// require_once("admin.php");
// require_once("comics.php");
require_once("../class/users.php");
require_once("db.php");

try {
    $userManager = new UserManager($pdo);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Erreur de connexion : ' . $e->getMessage()]);
    exit();
}

$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'ID utilisateur manquant.']);
    exit();
}

$userId = (int)$data['id'];

try {
    $userManager->deleteUser($userId);
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Erreur lors de la suppression : ' . $e->getMessage()]);
}