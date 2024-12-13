<?php
require_once("db.php");

function afficher_commentaires_comics($title, $parent_id = null, $niveau = 0) {
    global $pdo;

    // Adaptez la condition SQL selon la valeur de $parent_id
    if ($parent_id === null) {
        $query = "SELECT comics.title_comics, comment.comment_id, comment.comment, user.image_path, user.username, comment.parent_id 
                  FROM comment
                  JOIN comics ON comics.id_comics = comment.comics_id
                  JOIN user ON user.id_user = comment.user_id
                  WHERE comics.title_comics = :t AND comment.parent_id IS NULL";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':t' => $title]);
    } else {
        $query = "SELECT comics.title_comics, comment.comment_id, comment.comment, user.image_path, user.username, comment.parent_id 
                  FROM comment
                  JOIN comics ON comics.id_comics = comment.comics_id
                  JOIN user ON user.id_user = comment.user_id
                  WHERE comics.title_comics = :t AND comment.parent_id = :p";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':t' => $title, ':p' => $parent_id]);
    }

    // Parcours des résultats
    while ($row = $stmt->fetch()) {
        // Affichage avec indentation
        echo "<div style=\"margin-left: " . ($niveau * 32) . "px; display: flex; align-items: center;\">";
        echo "<img src=\"" . htmlspecialchars($row['image_path']) . "\" alt=\"\" style=\"width: 80px; margin-right: 8px;\">";
        echo htmlspecialchars($row['username']) . ": " . htmlspecialchars($row['comment']);
        echo "</div>";
        echo ("<form method=\"POST\" action=\"../methode/add_comment_comics.php?title=" . urlencode($title) . "&parent=" . urlencode($row['comment_id']) . "\" style=\"margin-left: " . ($niveau * 32) . "px;\">
                <textarea name=\"comment\" style=\"width: 600px; height: 20px;\"></textarea>
                <button type=\"submit\" class=\"reply-button\">Répondre</button>
            </form>") . "<br>";      

        // Appel récursif pour afficher les réponses
        afficher_commentaires_comics($title, $row['comment_id'], $niveau + 1);
    }
}

function afficher_commentaires_chapter($chapter_path, $parent_id = null, $niveau = 0) {
    global $pdo;

    // Adaptez la condition SQL selon la valeur de $parent_id
    if ($parent_id === null) {
        $query = "SELECT chapter.comics_path, comment.comment_id, comment.comment, user.image_path, user.username, comment.parent_id 
                  FROM comment
                  JOIN chapter ON chapter.id_chapter = comment.chapter_id
                  JOIN user ON user.id_user = comment.user_id
                  WHERE chapter.comics_path = :c AND comment.parent_id IS NULL";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':c' => $chapter_path]);
    } else {
        $query = "SELECT chapter.comics_path, comment.comment_id, comment.comment, user.image_path, user.username, comment.parent_id 
                  FROM comment
                  JOIN chapter ON chapter.id_chapter = comment.chapter_id
                  JOIN user ON user.id_user = comment.user_id
                  WHERE chapter.comics_path = :c AND comment.parent_id = :p";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':c' => $chapter_path, ':p' => $parent_id]);
    }

    // Parcours des résultats
    while ($row = $stmt->fetch()) {
        // Affichage avec indentation
        echo "<div style=\"margin-left: " . ($niveau * 32) . "px; display: flex; align-items: center;\">";
        echo "<img src=\"" . htmlspecialchars($row['image_path']) . "\" alt=\"\" style=\"width: 80px; margin-right: 8px;\">";
        echo htmlspecialchars($row['username']) . ": " . htmlspecialchars($row['comment']);
        echo "</div>";
        echo ("<form method=\"POST\" action=\"../methode/add_comment_chapter.php?chapter_path=" . urlencode($chapter_path  ) . "&parent=" . urlencode($row['comment_id']) . "\" style=\"margin-left: " . ($niveau * 32) . "px;\">
                <textarea name=\"comment\" style=\"width: 600px; height: 20px;\"></textarea>
                <button type=\"submit\" class=\"reply-button\">Répondre</button>
            </form>") . "<br>";      

        // Appel récursif pour afficher les réponses
        afficher_commentaires_chapter($chapter_path, $row['comment_id'], $niveau + 1);
    }
}

?>