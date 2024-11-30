<?php
require_once 'header.php';
require_once 'admin.php'; // Classe ComicsManager

// Vérifie si un ID est passé en paramètre
if (isset($_GET['id'])) {
    $id_comics = (int)$_GET['id']; // Récupère et sécurise l'ID du comic

    try {
        // Initialise la classe ComicsManager avec votre connexion PDO
        $comicsManager = new ComicsManager($bdd);

        // Appelle la méthode pour supprimer le comic
        $comicsManager->deleteComic($id_comics);

        // Redirige vers la page d'administration après suppression
        header('Location: admin_page.php');
        exit;
    } catch (Exception $e) {
        // Affiche un message d'erreur en cas de problème
        echo "Erreur lors de la suppression du comic : " . $e->getMessage();
    }
} else {
    echo "Aucun comic spécifié pour la suppression.";
}
