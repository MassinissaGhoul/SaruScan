<?php
require_once("admin.php");
require_once("db.php");

try {
    $comicsManager = new ComicsManager($pdo);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Erreur de connexion']);
    exit();
}

// Récupération de l'ID envoyé via JSON
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'ID comic manquant']);
    exit();
}

$comicId = (int)$data['id'];

try {
    $comicsManager->deleteComic($comicId);
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Erreur lors de la suppression']);
}
?>