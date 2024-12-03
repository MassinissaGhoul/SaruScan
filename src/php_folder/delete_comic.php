<?php
require_once("admin.php");


$host = 'localhost';
$db = 'saruscan';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
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