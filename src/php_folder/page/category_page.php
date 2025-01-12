<?php
require_once 'header.php';
require_once '../methode/db.php';  // Connexion à la base de données
require_once '../class/comics.php';  // Inclusion de la classe ComicsManager

// Récupération de la catégorie sélectionnée
$choice_category = $_GET["category"] ?? null;

// Initialisation du gestionnaire de comics
$comicsManager = new ComicsManager($pdo);

// Requête pour récupérer les comics selon la catégorie
if ($choice_category) {
    $stmt = $pdo->prepare("
        SELECT 
            c.id_comics,
            c.title_comics,
            c.author,
            c.created_at,
            c.image_path,
            c.category,
            COUNT(r.rate) AS thumbs_up
        FROM comics c
        LEFT JOIN rate r ON c.id_comics = r.id_comics AND r.rate = 1
        WHERE c.category = :category
        GROUP BY c.id_comics, c.title_comics, c.author, c.created_at, c.image_path, c.category
    ");
    $stmt->execute([':category' => $choice_category]);
} else {
    $stmt = $pdo->query("
        SELECT 
            c.id_comics,
            c.title_comics,
            c.author,
            c.created_at,
            c.image_path,
            c.category,
            COUNT(r.rate) AS thumbs_up
        FROM comics c
        LEFT JOIN rate r ON c.id_comics = r.id_comics AND r.rate = 1
        GROUP BY c.id_comics, c.title_comics, c.author, c.created_at, c.image_path, c.category
    ");
}

$comicsData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Ajout des comics dans le ComicsManager
foreach ($comicsData as $comic) {
    $newComic = new Comics(
        $comic['id_comics'],
        $comic['title_comics'],
        $comic['author'],
        $comic['created_at'],
        $comic['image_path'],
        $comic['category']
    );
    $newComic->thumbs_up = $comic['thumbs_up'];  // Ajout du nombre de likes
    $comicsManager->add_comics($newComic);
}

// Récupération des catégories
$category_req = $pdo->query("SELECT DISTINCT category FROM comics");
?>

<body class="bg-gray-900 text-gray-300">
    <div class="container mx-auto px-4 py-8">
        <!-- Navigation des Catégories -->
        <div class="flex flex-wrap justify-center gap-2 mb-6">
            <?php while ($category = $category_req->fetch()): ?>
                <a href="category_page.php?category=<?= htmlspecialchars($category["category"]) ?>"
                   class="px-4 py-2 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition">
                    <?= htmlspecialchars($category["category"]) ?>
                </a>
            <?php endwhile; ?>
        </div>

        <!-- Liste des Comics filtrés -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($comicsManager->get_comics() as $comic): ?>
                <div class="comic-container bg-gray-800 rounded-lg shadow-lg overflow-hidden h-auto">
                    <div class="w-full">
                        <img src="<?= htmlspecialchars($comic->get_img()) ?>" alt="Comic Image" class="h-64 w-full object-cover">
                    </div>

                    <div class="p-6 flex flex-col justify-between">
                        <div>
                            <a href="comics_page.php?title=<?= htmlspecialchars($comic->get_title_comics()) ?>">
                                <h3 class="text-xl font-bold text-white mb-3"><?= htmlspecialchars($comic->get_title_comics()) ?></h3>
                            </a>
                            <div class="flex items-center justify-between mb-4">
                                <span class="px-2 py-1 bg-blue-600 text-white text-xs font-medium rounded-full">
                                    <?= htmlspecialchars($comic->get_category()) ?>
                                </span>
                                <div class="text-yellow-400 flex items-center space-x-1">
                                    <span class="text-base">👍</span>
                                    <span class="text-base font-semibold"><?= $comic->thumbs_up ?> votes</span>
                                </div>
                            </div>
                        </div>

                        <!-- Bouton ajouter aux favoris -->
                        <form method="POST" action="../methode/add_to_favorites.php" class="mt-4">
                            <input type="hidden" name="comic_id" value="<?= htmlspecialchars($comic->get_id_comics()) ?>">
                            <button type="submit" class="w-full text-center px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                                Ajouter aux Favoris
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-center text-gray-400 py-4 mt-8">
        <p class="animate-scroll">Reda Steven Massi SaruScan</p>
    </footer>
</body>
