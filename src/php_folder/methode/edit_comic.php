<?php
require_once("admin.php");
require_once("header.php");
require_once("db.php");
try {
    $comicsManager = new ComicsManager($pdo);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Traitement de la mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_comic'])) {
    $comicId = $_POST['id_comic'];
    $title = $_POST['title'];
    $author = $_POST['author'];
    $category = $_POST['category'];

    if (!empty($title) && !empty($author) && !empty($category)) {
        $comicsManager->updateComic($comicId, $title, $author, $category);
        header("Location: admin_page.php");
        exit();
    } else {
        echo "Tous les champs sont requis pour modifier un comic.";
    }
}

// Chargement des données comic pour l'édition
if (isset($_GET['id'])) {
    $comicId = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM comics WHERE id_comics = :comicId");
    $stmt->execute([':comicId' => $comicId]);
    $comic = $stmt->fetch();

    if (!$comic) {
        die("Comic introuvable.");
    }
} else {
    die("ID comic non spécifié.");
}
?>

<h2>Modifier un Comic</h2>
<form action="edit_comic.php" method="post">
    <input type="hidden" name="edit_comic" value="1">
    <input type="hidden" name="id_comic" value="<?= htmlspecialchars($comic['id_comics']) ?>">
    <label for="title">Titre</label>
    <input type="text" id="title" name="title" value="<?= htmlspecialchars($comic['title_comics']) ?>" required><br>

    <label for="author">Auteur</label>
    <input type="text" id="author" name="author" value="<?= htmlspecialchars($comic['author']) ?>" required><br>

    <label for="category">Catégorie</label>
    <input type="text" id="category" name="category" value="<?= htmlspecialchars($comic['category']) ?>" required><br>

    <button type="submit">Mettre à jour</button>
</form>
