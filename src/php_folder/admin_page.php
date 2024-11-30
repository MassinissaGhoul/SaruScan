<?php
require_once("header.php");
require_once("admin.php");
require_once 'users.php';

// Configuration de la base de données
$host = 'localhost';
$db = 'saruscan';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    $comicsManager = new ComicsManager($pdo);
    $chapterManager = new ChapterManager($pdo);
    echo "Connexion réussie à la base de données !<br>";
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Traitement de l'ajout d'un comic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comic'])) {
    $title_comics = $_POST['comic'] ?? '';
    $author = $_POST['author'] ?? '';
    $category = $_POST['category'] ?? '';
    $image_path = $_POST['image_path'] ?? '/src/img/default.jpg'; // Valeur par défaut si aucun chemin n'est fourni
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
        try {
            $stmt = $pdo->prepare("SELECT id_comics FROM comics WHERE title_comics = :title_comics");
            $stmt->execute([':title_comics' => $comic_name]);
            $comic = $stmt->fetch();

            if ($comic) {
                $id_comics = $comic['id_comics'];
                $chapterManager->addChapterToDB($id_comics, $title_chapter, $chapter_image_path, $chapter_number, $created_at);
            } else {
                echo "Comic non trouvé. Veuillez vérifier le nom.<br>";
            }
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout du chapitre : " . $e->getMessage() . "<br>";
        }
    } else {
        echo "Tous les champs sont requis pour ajouter un chapitre.<br>";
    }
}

$userManager = new UserManager($bdd);

// Récupérer les données
$comics = $comicsManager->getAllComics();
$users = $userManager->getAllUsers();

?>

<body>
    <div class="container">
        <h2>Liste des Utilisateurs</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Username</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['pseudo']) ?></td>
                    <td>
                        <button onclick="deleteUser(<?= $user['id_user'] ?>)">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="container">
        <h2>Liste des Comics</h2>
        <table border="1">
            <thead>
                <tr>
                    <th>Comic</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($comics as $comic): ?>
                <tr>
                    <td><?= htmlspecialchars($comic['title_comics']) ?></td>
                    <td><?= htmlspecialchars($comic['author']) ?></td>
                    <td><?= htmlspecialchars($comic['category']) ?></td>
                    <td><?= htmlspecialchars($comic['created_at']) ?></td>
                    <td>
                        <button onclick="editComic(<?= $comic['id_comics'] ?>)">Edit</button>
                        <button onclick="deleteComic(<?= $comic['id_comics'] ?>)">Delete</button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="container">
        <h2>Ajouter un Comic</h2>
        <form action="admin_page.php" method="post">
            <input type="hidden" name="add_comic" value="1">
            <label for="comic">Titre</label>
            <input type="text" id="comic" name="comic" required><br>

            <label for="author">Auteur</label>
            <input type="text" id="author" name="author" required><br>

            <label for="category">Catégorie</label>
            <input type="text" id="category" name="category" required><br>

            <label for="image_path">Chemin de l'image</label>
            <input type="text" id="image_path" name="image_path" placeholder="/src/img/default.jpg"><br>

            <button type="submit">Ajouter</button>
        </form>
    </div>

    <div class="container">
        <h2>Ajouter un Chapitre</h2>
        <form action="admin_page.php" method="post">
            <input type="hidden" name="add_chapter" value="1">
            <label for="comic_name">Nom du Comic</label>
            <input type="text" id="comic_name" name="comic_name" required><br>

            <label for="chapter_number">Numéro du Chapitre</label>
            <input type="number" id="chapter_number" name="chapter_number" required><br>

            <label for="title">Titre</label>
            <input type="text" id="title" name="title" required><br>

            <label for="chapter_image_path">Chemin des Images</label>
            <input type="text" id="chapter_image_path" name="chapter_image_path" required><br>

            <button type="submit">Ajouter</button>
        </form>
    </div>
</body>
<script>
function deleteComic(id) {
    if (confirm('Voulez-vous vraiment supprimer ce comic ?')) {
        window.location.href = 'delete_comic.php?id=' + id;
    }
}

function deleteUser(id) {
    if (confirm('Voulez-vous vraiment supprimer cet utilisateur ?')) {
        window.location.href = 'delete_user.php?id=' + id;
    }
}


function editComic(id) {
    window.location.href = 'edit_comic.php?id=' + id;
}
</script>
</html>
