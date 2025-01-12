<?php
require_once("../class/admin.php");
require_once("../page/header.php");
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
        header("Location: ../page/admin_page.php");
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

<h2 class="text-2xl font-bold mb-4 text-white">Modifier un Comic</h2>
<form action="edit_comic.php" method="post" class="bg-gray-800 p-6 shadow-md rounded-lg">
    <input type="hidden" name="edit_comic" value="1">
    <input type="hidden" name="id_comic" value="<?= htmlspecialchars($comic['id_comics']) ?>">

    <div class="mb-4">
        <label for="title" class="block text-sm font-medium text-white">Titre</label>
        <input type="text" id="title" name="title" value="<?= htmlspecialchars($comic['title_comics']) ?>" required class="w-full px-4 py-2 border border-gray-600 rounded-lg bg-gray-700 text-white">
    </div>

    <div class="mb-4">
        <label for="author" class="block text-sm font-medium text-white">Auteur</label>
        <input type="text" id="author" name="author" value="<?= htmlspecialchars($comic['author']) ?>" required class="w-full px-4 py-2 border border-gray-600 rounded-lg bg-gray-700 text-white">
    </div>

    <div class="mb-4">
        <label for="category" class="block text-sm font-medium text-white">Catégorie</label>
        <input type="text" id="category" name="category" value="<?= htmlspecialchars($comic['category']) ?>" required class="w-full px-4 py-2 border border-gray-600 rounded-lg bg-gray-700 text-white">
    </div>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Mettre à jour</button>
</form>
