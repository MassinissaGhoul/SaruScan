<?php
include_once("header.php");
require_once("../methode/db.php");
include_once("../methode/repondre.php");
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commentaires</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white">

    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Commentaires</h1>

        <?php if (isset($title)): ?>
            <form method="POST" action="<?php echo ("../methode/add_comment_comics.php?title=" . $title) ?>" class="mb-6">
                <textarea name="comment" class="w-full p-4 bg-gray-800 text-white rounded-md" placeholder="Ajouter un commentaire"></textarea>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500 mt-4">Ajouter</button>
            </form>
        <?php endif; ?>

        <?php if (isset($chapter_path)): ?>
            <form method="POST" action="<?php echo ("../methode/add_comment_chapter.php?chapter_path=" . $chapter_path) ?>" class="mb-6">
                <textarea name="comment" class="w-full p-4 bg-gray-800 text-white rounded-md" placeholder="Ajouter un commentaire"></textarea>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-500 mt-4">Ajouter</button>
            </form>
        <?php endif; ?>

        <div class="comments-section">
            <?php
            if (isset($title)) {
                afficher_commentaires_comics($title);
            } else {
                afficher_commentaires_chapter($chapter_path);
            }
            ?>
        </div>
    </div>

</body>

</html>