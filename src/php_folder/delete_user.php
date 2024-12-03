<?php
require_once("users.php");

// Configuration de la base de données
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
    die(json_encode(['success' => false, 'error' => $e->getMessage()]));
}

// Vérifie que l'ID de l'utilisateur est envoyé
$data = json_decode(file_get_contents("php://input"), true);
if (!isset($data['id'])) {
    echo json_encode(['success' => false, 'error' => 'ID utilisateur manquant.']);
    exit();
}

$userId = $data['id'];
try {
    $userManager->deleteUser($userId);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

?>