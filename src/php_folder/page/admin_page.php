<?php
require_once("header.php");
require_once("../class/admin.php");
require_once("../class/users.php");
// Configuration de la base de données

try {
    $comicsManager = new ComicsManager($pdo);
    $chapterManager = new ChapterManager($pdo);
    $userManager = new UserManager($pdo);
    echo "Connexion réussie à la base de données !<br>";
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Traitement de l'ajout d'un comic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comic'])) {
    $title_comics = $_POST['comic'] ?? '';
    $author = $_POST['author'] ?? '';
    $category = $_POST['category'] ?? '';
    $image_path = $_POST['image_path'] ?? '/src/img/default.jpg';
    $created_at = date('Y-m-d');

    if (!empty($title_comics) && !empty($author) && !empty($category)) {
        $comicsManager->addComicsToDB($title_comics, $author, $category, $image_path, $created_at);
    } else {
        echo "Tous les champs sont requis pour ajouter un comic.<br>";
    }
}

// Traitement de l'ajout d'un chapitre
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_chapter'])) {
    $comic_name = $_POST['comic_name'] ?? '';
    $chapter_number = $_POST['chapter_number'] ?? '';
    $title_chapter = $_POST['title'] ?? '';
    $chapter_image_path = $_POST['chapter_image_path'] ?? '';
    $created_at = date('Y-m-d');

    if (!empty($comic_name) && !empty($chapter_number) && !empty($title_chapter) && !empty($chapter_image_path)) {
        $stmt = $pdo->prepare("SELECT id_comics FROM comics WHERE title_comics = :title_comics");
        $stmt->execute([':title_comics' => $comic_name]);
        $comic = $stmt->fetch();

        if ($comic) {
            $id_comics = $comic['id_comics'];
            $chapterManager->addChapterToDB($id_comics, $title_chapter, $chapter_image_path, $chapter_number, $created_at);
        } else {
            echo "Comic non trouvé. Veuillez vérifier le nom.<br>";
        }
    } else {
        echo "Tous les champs sont requis pour ajouter un chapitre.<br>";
    }
}

$comics = $comicsManager->getAllComics();
$users = $userManager->getAllUsers();
?>
<body class="bg-gray-900 text-gray-200">
    <div class="container mx-auto py-10">
        <!-- Liste des Utilisateurs -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Liste des Utilisateurs</h2>
            <table class="table-auto w-full bg-gray-800 shadow-md rounded-lg overflow-hidden">
                <thead class="bg-blue-700">
                    <tr>
                        <th class="px-4 py-2 text-white">Email</th>
                        <th class="px-4 py-2 text-white">Nom d'utilisateur</th>
                        <th class="px-4 py-2 text-white">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr class="border-b border-gray-700">
                                <td class="px-4 py-2"><?= htmlspecialchars($user['email']) ?></td>
                                <td class="px-4 py-2"><?= htmlspecialchars($user['username']) ?></td>
                                <td class="px-4 py-2">
                                    <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" onclick="editUser(<?= $user['id_user'] ?>)">Modifier</button>
                                    <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" onclick="deleteUser(<?= $user['id_user'] ?>)">Supprimer</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center py-4 text-gray-400">Aucun utilisateur trouvé.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Liste des Comics -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Liste des Comics</h2>
            <table class="table-auto w-full bg-gray-800 shadow-md rounded-lg overflow-hidden">
                <thead class="bg-blue-700">
                    <tr>
                        <th class="px-4 py-2 text-white">Comic</th>
                        <th class="px-4 py-2 text-white">Auteur</th>
                        <th class="px-4 py-2 text-white">Catégorie</th>
                        <th class="px-4 py-2 text-white">Date de création</th>
                        <th class="px-4 py-2 text-white">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comics as $comic): ?>
                        <tr class="border-b border-gray-700">
                            <td class="px-4 py-2"><?= htmlspecialchars($comic['title_comics']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($comic['author']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($comic['category']) ?></td>
                            <td class="px-4 py-2"><?= htmlspecialchars($comic['created_at']) ?></td>
                            <td class="px-4 py-2">
                                <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" onclick="editComic(<?= $comic['id_comics'] ?>)">Modifier</button>
                                <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" onclick="deleteComic(<?= $comic['id_comics'] ?>)">Supprimer</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Ajouter un Comic -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold mb-4">Ajouter un Comic</h2>
            <form action="admin_page.php" method="post" class="bg-gray-800 p-6 shadow-md rounded-lg">
                <input type="hidden" name="add_comic" value="1">
                <div class="mb-4">
                    <label for="comic" class="block text-sm font-medium text-white">Titre</label>
                    <input type="text" id="comic" name="comic" required class="w-full px-4 py-2 border border-gray-600 rounded-lg bg-gray-700 text-white">
                </div>
                <div class="mb-4">
                    <label for="author" class="block text-sm font-medium text-white">Auteur</label>
                    <input type="text" id="author" name="author" required class="w-full px-4 py-2 border border-gray-600 rounded-lg bg-gray-700 text-white">
                </div>
                <div class="mb-4">
                    <label for="category" class="block text-sm font-medium text-white">Catégorie</label>
                    <input type="text" id="category" name="category" required class="w-full px-4 py-2 border border-gray-600 rounded-lg bg-gray-700 text-white">
                </div>
                <div class="mb-4">
                    <label for="image_path" class="block text-sm font-medium text-white">Chemin de l'image</label>
                    <input type="text" id="image_path" name="image_path" placeholder="/src/img/default.jpg" class="w-full px-4 py-2 border border-gray-600 rounded-lg bg-gray-700 text-white">
                </div>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Ajouter</button>
            </form>
        </div>

        <!-- Ajouter un Chapitre -->
        <div>
            <h2 class="text-2xl font-bold mb-4">Ajouter un Chapitre</h2>
            <form action="admin_page.php" method="post" class="bg-gray-800 p-6 shadow-md rounded-lg">
                <input type="hidden" name="add_chapter" value="1">
                <div class="mb-4">
                    <label for="comic_name" class="block text-sm font-medium text-white">Nom du Comic</label>
                    <input type="text" id="comic_name" name="comic_name" required class="w-full px-4 py-2 border border-gray-600 rounded-lg bg-gray-700 text-white">
                </div>
                <div class="mb-4">
                    <label for="chapter_number" class="block text-sm font-medium text-white">Numéro du Chapitre</label>
                    <input type="number" id="chapter_number" name="chapter_number" required class="w-full px-4 py-2 border border-gray-600 rounded-lg bg-gray-700 text-white">
                </div>
                <div class="mb-4">
                    <label for="title" class="block text-sm font-medium text-white">Titre</label>
                    <input type="text" id="title" name="title" required class="w-full px-4 py-2 border border-gray-600 rounded-lg bg-gray-700 text-white">
                </div>
                <div class="mb-4">
                    <label for="chapter_image_path" class="block text-sm font-medium text-white">Chemin des Images</label>
                    <input type="text" id="chapter_image_path" name="chapter_image_path" required class="w-full px-4 py-2 border border-gray-600 rounded-lg bg-gray-700 text-white">
                </div>
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Ajouter</button>
            </form>
        </div>
    </div>
</body>


<script>
function deleteUser(id) {
    if (confirm('Voulez-vous vraiment supprimer cet utilisateur ?')) {
        fetch('../methode/delete_user.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        })
        .finally(() => {
            // Recharge toujours la page après la tentative
            location.reload();
        });
    }
}


function deleteComic(id) {
    if (confirm('Voulez-vous vraiment supprimer ce comic ?')) {
        fetch('../methode/delete_comic.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        })
        .finally(() => {
            // Recharge toujours la page après la tentative
            location.reload();
        });
    }
}


function editComic(id) {
    window.location.href = '../methode/edit_comic.php?id=' + id;
}

function editUser(id) {
    window.location.href = '../methode/edit_user.php?id=' + id;
}
</script>
</html>
