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
    die("Erreur de connexion : " . $e->getMessage());
}

// Traitement de la mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_user'])) {
    $userId = $_POST['id_user'];
    $email = $_POST['email'];
    $username = $_POST['username'];
    $is_admin = isset($_POST['is_admin']) ? 1 : 0;

    if (!empty($email) && !empty($username)) {
        $userManager->updateUser($userId, $email, $username, $is_admin);
        header("Location: admin_page.php");
        exit();
    } else {
        echo "Tous les champs sont requis pour modifier un utilisateur.";
    }
}

// Chargement des données utilisateur pour l'édition
if (isset($_GET['id'])) {
    $userId = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM user WHERE id_user = :userId");
    $stmt->execute([':userId' => $userId]);
    $user = $stmt->fetch();

    if (!$user) {
        die("Utilisateur introuvable.");
    }
} else {
    die("ID utilisateur non spécifié.");
}
?>

<h2>Modifier un Utilisateur</h2>
<form action="edit_user.php" method="post">
    <input type="hidden" name="edit_user" value="1">
    <input type="hidden" name="id_user" value="<?= htmlspecialchars($user['id_user']) ?>">
    <label for="email">Email</label>
    <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required><br>

    <label for="username">Nom d'utilisateur</label>
    <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required><br>

    <label for="is_admin">Administrateur</label>
    <input type="checkbox" id="is_admin" name="is_admin" <?= $user['is_admin'] ? 'checked' : '' ?>><br>

    <button type="submit">Mettre à jour</button>
</form>
