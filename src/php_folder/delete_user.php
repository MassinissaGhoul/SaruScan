<?php
require_once 'header.php';
require_once 'users.php'; // Classe UserManager

if (isset($_GET['id'])) {
    $id_user = (int)$_GET['id'];
    $userManager = new UserManager($bdd); // Initialiser UserManager avec votre connexion PDO.

    try {
        $userManager->deleteUser($id_user); // Appel à la méthode de suppression.
        header('Location: admin_page.php'); // Rediriger vers la page d'administration.
        exit;
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    echo "Aucun utilisateur spécifié pour suppression.";
}
