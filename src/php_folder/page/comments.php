<?php
include_once("header.php");
require_once("../methode/db.php");

$comics_title = $_GET['title'] ?? null;
if (!$comics_title) {
    die('<p>Erreur : Titre du comics non fourni. <a href="test_steven.php">Revenir à la page principale</a></p>');
}

// Rechercher l'ID du comics
$stmt = $pdo->prepare("SELECT id_comics FROM comics WHERE title_comics = :title");
$stmt->execute(['title' => $comics_title]);
$comics = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$comics) {
    die('<p>Erreur : Comics introuvable. <a href="test_steven.php">Revenir à la page principale</a></p>');
}

$comics_id = $comics['id_comics'];

// Récupérer les commentaires pour ce comics
$stmt = $pdo->prepare("
    SELECT c.comment_id, c.user_id, c.parent_id, c.comment, c.created_at, u.username, u.image_path
    FROM comment c
    JOIN user u ON c.user_id = u.id_user
    WHERE c.comics_id = :comics_id
    ORDER BY c.parent_id ASC, c.created_at ASC
");
$stmt->execute(['comics_id' => $comics_id]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fonction récursive pour organiser les réponses
function render_comments($comments, $parent_id = null) {
    $output = '';
    foreach ($comments as $comment) {
        if ($comment['parent_id'] == $parent_id) {
            $output .= '
            <div class="pl-' . ($parent_id ? 8 : 0) . ' mb-4 border-l border-gray-700">
                <div class="flex items-start space-x-4">
                    <img class="w-12 h-12 rounded-full" src="' . htmlspecialchars($comment['image_path']) . '" alt="Photo de profil">
                    <div>
                        <div class="text-sm text-gray-400">
                            <span class="font-semibold text-gray-200">' . htmlspecialchars($comment['username']) . '</span>
                            <span class="ml-2 text-gray-500">' . date('d.m.Y', strtotime($comment['created_at'])) . '</span>
                        </div>
                        <p class="text-gray-300">' . nl2br(htmlspecialchars($comment['comment'])) . '</p>
                        <form method="POST" action="add_comment.php">
                            <input type="hidden" name="comics_id" value="' . htmlspecialchars($GLOBALS['comics_id']) . '">
                            <input type="hidden" name="parent_id" value="' . htmlspecialchars($comment['comment_id']) . '">
                            <input type="hidden" name="comics_title" value="' . htmlspecialchars($GLOBALS['comics_title']) . '">
                            <textarea name="comment" rows="3" required placeholder="Répondre..."></textarea>
                            <button type="submit">Répondre</button>
                        </form>
                    </div>
                </div>
                ' . render_comments($comments, $comment['comment_id']) . '
            </div>';
        }
    }
    return $output;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commentaires</title>
</head>
<body>
    <div>
        <h1>Commentaires pour <?= htmlspecialchars($comics_title); ?></h1>
        <form method="POST" action="add_comment.php">
            <input type="hidden" name="comics_id" value="<?= htmlspecialchars($comics_id); ?>">
            <input type="hidden" name="comics_title" value="<?= htmlspecialchars($comics_title); ?>">
            <textarea name="comment"></textarea>
            <button type="submit">Ajouter un commentaire</button>
        </form>
        <div>
            <?= render_comments($comments); ?>
        </div>
    </div>
</body>
</html>
