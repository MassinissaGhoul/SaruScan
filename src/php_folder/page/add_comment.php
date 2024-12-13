<?php
session_start();
require_once("../methode/db.php");


// Débogage : Affichez la session
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
die('Debug : Session affichée');


// Vérification de l'utilisateur connecté
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user'])) {
        die('Erreur : Utilisateur non connecté.');
    }

    // Utilisez la clé correcte pour récupérer l'ID utilisateur
    $user_id = $_SESSION['user']['id_user'] ?? null;

    // Vérifications
    $comics_id = filter_var($_POST['comics_id'] ?? null, FILTER_VALIDATE_INT);
    $parent_id = filter_var($_POST['parent_id'] ?? null, FILTER_VALIDATE_INT) ?: null;
    $comment = trim($_POST['comment'] ?? '');
    $comics_title = $_POST['comics_title'] ?? null;

    if (!$user_id) {
        die('Erreur : ID utilisateur manquant.');
    }

    if (!$comics_id) {
        die('Erreur : ID du comics invalide.');
    }

    if (!$comment) {
        die('Erreur : Commentaire vide.');
    }

    // Vérifiez si l'utilisateur existe dans la table `user`
    try {
        $stmt = $pdo->prepare("SELECT * FROM user WHERE id_user = :user_id");
        $stmt->execute(['user_id' => $user_id]);
    
        // Débogage : Affichez l'ID envoyé à la requête
        if (!$stmt->fetch()) {
            throw new Exception('Utilisateur introuvable avec ID : ' . $user_id);
        }
    
        $stmt = $pdo->prepare("
            INSERT INTO comment (user_id, comics_id, parent_id, comment, created_at) 
            VALUES (:user_id, :comics_id, :parent_id, :comment, NOW())
        ");
        $stmt->execute([
            'user_id' => $user_id,
            'comics_id' => $comics_id,
            'parent_id' => $parent_id,
            'comment' => $comment
        ]);
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
    
    

    // Redirection après ajout
    header("Location: comments.php?title=" . urlencode($comics_title));
    exit;
}
?>
