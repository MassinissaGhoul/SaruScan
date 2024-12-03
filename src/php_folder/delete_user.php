<?php
// require_once("admin.php");
// require_once("comics.php");
require_once("users.php");

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
    $userManager = new UserManager($pdo);
} catch (PDOException $e) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'error' => 'Erreur de connexion : ' . $e->getMessage()]);
    exit();
}

// Récupération de l'ID envoyé via JSON
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