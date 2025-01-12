<?php
require_once("db.php");

function afficher_commentaires_comics($title, $parent_id = null, $niveau = 0)
{
    global $pdo;

    if ($parent_id === null) {
        $query = "SELECT comics.title_comics, comment.comment_id, comment.comment, user.image_path, user.username, comment.parent_id, comment.created_at 
                  FROM comment
                  JOIN comics ON comics.id_comics = comment.comics_id
                  JOIN user ON user.id_user = comment.user_id
                  WHERE comics.title_comics = :t AND comment.parent_id IS NULL";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':t' => $title]);
    } else {
        $query = "SELECT comics.title_comics, comment.comment_id, comment.comment, user.image_path, user.username, comment.parent_id, comment.created_at 
                  FROM comment
                  JOIN comics ON comics.id_comics = comment.comics_id
                  JOIN user ON user.id_user = comment.user_id
                  WHERE comics.title_comics = :t AND comment.parent_id = :p";
        $stmt = $pdo->prepare($query);
        $stmt->execute([':t' => $title, ':p' => $parent_id]);
    }

    while ($row = $stmt->fetch()) {
        echo "<div class='flex flex-col bg-gray-800 p-6 rounded-lg shadow-md mb-4' style='margin-left:" . ($niveau * 20) . "px;'>";
        echo "<div class='flex items-center space-x-4 mb-4'>";
        echo "<img class='w-16 h-16 rounded-full' src='" . $row['image_path'] . "' alt='Image de profil'>";
        echo "<div>";
        echo "<h2 class='text-xl font-semibold text-gray-300'>" . htmlspecialchars($row['username']) . "</h2>";
        echo "<span class='text-sm text-gray-400'>" . date("d.m.Y", strtotime($row['created_at'])) . "</span>";
        echo "</div>";
        echo "</div>";
        echo "<p class='text-gray-200 leading-relaxed'>" . htmlspecialchars($row['comment']) . "</p>";

        // Formulaire pour répondre
        echo ("<form method='POST' action='../methode/add_comment_comics.php?title=" . urlencode($title) . "&parent=" . urlencode($row['comment_id']) . "' class='mt-4'>
                <textarea name='comment' class='w-full px-4 py-2 text-sm bg-gray-700 text-gray-200 rounded-md mb-2' placeholder='Écrire une réponse'></textarea>
                <button type='submit' class='px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500'>Répondre</button>
            </form>");
        if (isset($_SESSION["user"]) && $_SESSION["user"]["is_admin"] == 1){
            echo "<a href=\"../methode/supprimer_commentaire.php?comment_id=". $row["comment_id"] ."\">Supprimer Commentaire</a>" ;
        }
        afficher_commentaires_comics($title, $row['comment_id'], $niveau + 1);
        echo "</div>";
    }
}


function afficher_commentaires_chapter($chapter_path, $parent_id = null, $niveau = 0)
{
    global $pdo;

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

    while ($row = $stmt->fetch()) {
        echo "<div class='flex flex-col pl-" . ($niveau * 8) . " mb-4 border-l border-gray-700'>";
        echo "<div class='flex items-center space-x-4 mb-2'>";
        echo "<img class='w-12 h-12 rounded-full' src='" . htmlspecialchars($row['image_path']) . "' alt=''>";
        echo "<div class='text-sm text-gray-300'><span class='font-bold text-white'>" . htmlspecialchars($row['username']) . "</span>: " . htmlspecialchars($row['comment']) . "</div>";
        echo "</div>";
        echo ("<form method='POST' action='../methode/add_comment_chapter.php?chapter_path=" . urlencode($chapter_path) . "&parent=" . urlencode($row['comment_id']) . "' class='ml-4'>
                <textarea name='comment' class='w-full px-4 py-2 text-sm bg-gray-800 text-gray-200 rounded-md mb-2' placeholder='Écrire une réponse'></textarea>
                <button type='submit' class='px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500'>Répondre</button>
            </form>");

        afficher_commentaires_chapter($chapter_path, $row['comment_id'], $niveau + 1);
        echo "</div>";
    }
}