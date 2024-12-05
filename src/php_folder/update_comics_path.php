<?php
require_once("db.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    if (isset($data['new_comics_path'])) {
        $_SESSION['comics_path'] = $data['new_comics_path']; // Mettre à jour le chemin dans la session
        echo json_encode(['success' => true, 'new_path' => $_SESSION['comics_path']]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Chemin non fourni']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Requête invalide']);
}
