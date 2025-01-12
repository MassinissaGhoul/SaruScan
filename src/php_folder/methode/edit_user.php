<?php

require_once("../class/users.php");
require_once("../page/header.php");
require_once("db.php");

try {
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
        header("Location: ../page/admin_page.php");
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

<h2 class="text-2xl font-bold mb-4 text-white">Modifier un Utilisateur</h2>
<form action="edit_user.php" method="post" class="bg-gray-800 p-6 shadow-md rounded-lg">
    <input type="hidden" name="edit_user" value="1">
    <input type="hidden" name="id_user" value="<?= htmlspecialchars($user['id_user']) ?>">

    <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-white">Email</label>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required class="w-full px-4 py-2 border border-gray-600 rounded-lg bg-gray-700 text-white">
    </div>

    <div class="mb-4">
        <label for="username" class="block text-sm font-medium text-white">Nom d'utilisateur</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required class="w-full px-4 py-2 border border-gray-600 rounded-lg bg-gray-700 text-white">
    </div>

    <div class="mb-4 flex items-center">
        <input type="checkbox" id="is_admin" name="is_admin" <?= $user['is_admin'] ? 'checked' : '' ?> class="mr-2 rounded border-gray-600 bg-gray-700">
        <label for="is_admin" class="text-white">Administrateur</label>
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Mettre à jour</button>
</form>
