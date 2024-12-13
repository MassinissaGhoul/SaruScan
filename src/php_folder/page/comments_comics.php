<?php
include_once("header.php");
require_once("../methode/db.php");
include_once("../methode/repondre.php")

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commentaires</title>
</head>
<body>
    <div>
        <h1>Commentaires :</h1>
        <?php if (isset($title)): ?>
            <form method="POST" action=" <?php echo("../methode/add_comment_comics.php?title=" . $title . "\"") ?>" >
                <textarea name="comment"></textarea>
                <button type="submit">Ajouter un commentaire</button>
            </form>
        <?php endif; ?>
        <?php if (isset($chapter_path)): ?>
            <form method="POST" action=" <?php echo("../methode/add_comment_chapter.php?chapter_path=" . $chapter_path . "\"") ?>" >
                <textarea name="comment"></textarea>
                <button type="submit">Ajouter un commentaire</button>
            </form>
        <?php endif; ?>
    </div>

    <?php
    if(isset($title)){
        afficher_commentaires_comics($title);
    }
    else{
        afficher_commentaires_chapter($chapter_path);
    }
    ?>
</body>