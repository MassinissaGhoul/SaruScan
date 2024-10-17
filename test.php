<?php
require_once 'comics.php'; 

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
    echo "Connexion réussie à la base de données!<br>";
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

$comicsManager = new ComicsManager(); 
$query = $pdo->query("SELECT * FROM comics");

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $comics = new Comics($row['id_comics'], $row['title_comics'], $row['author'], $row['created_at'], $row['category']);
    $comicsManager->add_comics($comics);
}

$comicsManager->display_comics();

?>
