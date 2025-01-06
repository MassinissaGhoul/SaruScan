<?php
include_once("header.php");
include_once("../class/comics.php");

// Récupérer le titre depuis la requête GET
$title = $_GET["title"] ?? null;


// Vérifier si le titre est présent
if (!$title) {
    echo "<p class='text-center text-red-500'>Aucun titre spécifié.</p>";
    exit();
}

$like_req = $pdo->prepare("SELECT count(rate) from rate JOIN comics WHERE comics.id_comics = rate.id_comics AND comics.title_comics = :t");
$like_req->execute([":t" => $title]);
$like = $like_req->fetch();

// Préparer les requêtes SQL
$comics_req = $pdo->prepare("SELECT * FROM comics WHERE title_comics = :t");
$chapter_req = $pdo->prepare("
    SELECT title_chapter, comics_path, view_count
    FROM comics 
    INNER JOIN chapter ON comics.id_comics = chapter.id_comics 
    WHERE comics.title_comics = :t
");

// Exécuter les requêtes avec les paramètres
$comics_req->execute([':t' => $title]);
$chapter_req->execute([':t' => $title]);

// Vérifier si le comic existe
if ($comics_req->rowCount() === 0) {
    echo "<p class='text-center text-red-500'>Comics non trouvé.</p>";
    exit();
}

// Récupérer les informations du comic
$comic = $comics_req->fetch();
echo $title;

?>

<body>
    <div class="comic-container bg-gray-900 rounded-lg shadow-lg flex overflow-hidden h-64">
        <!-- Image du comic -->
        <div class="w-2/5">
            <img 
                src="<?php echo htmlspecialchars($comic['image_path']); ?>" 
                alt="Image du Comic" 
                class="comic-image h-full w-full object-cover">
        </div>

        <!-- Informations du comic -->
        <div class="w-3/5 p-6 flex flex-col justify-between">
            <!-- Titre et catégorie -->
            <div>
                <h3 class="text-xl font-bold text-white mb-3">
                    <?php echo htmlspecialchars($comic['title_comics']); ?>
                </h3>
                <h3 class="text-xl font-bold text-white mb-3 flex items-center">
                    <span class="ml-4 flex items-center">
                        <?php echo htmlspecialchars($like["count(rate)"]); ?>
                        <a
                            class="ml-2 text-blue-500 hover:text-blue-300 focus:outline-none" 
                            href="../methode/add_like.php?id_comics= <?php echo $comic["id_comics"] ?>"
                        >
                            👍
                        </a>
                    </span>
                </h3>
                <div class="flex items-center justify-between mb-4">
                    <span class="px-2 py-1 bg-blue-600 text-white text-xs font-medium rounded-full">
                        Manhwa
                    </span>
                    <div class="flex items-center space-x-1 text-yellow-400">
                        <?php echo htmlspecialchars($comic['category']); ?>
                    </div>
                </div>
            </div>

            <!-- Liste des chapitres -->
            <div class="chapter-list mt-4">
                <?php while ($chapter = $chapter_req->fetch()): ?>
                    <div class="chapter-item flex justify-between items-center px-3 py-2 hover:bg-gray-800 transition duration-300 border-b border-gray-700 last:border-b-0">
                        <span class="font-semibold text-white">
                            <?php echo htmlspecialchars($chapter["title_chapter"]) . " (views: " . htmlspecialchars($chapter["view_count"]) . ")"; ?>
                        </span>
                        <div class="flex space-x-2">
                            <!-- Bouton pour voir le chapitre -->
                            <a 
                                href="test.php?chapter_path=<?php echo urlencode($chapter['comics_path']); ?>&manga_id=<?php echo htmlspecialchars($comic['id_comics']); ?>" 
                                class="bg-blue-600 text-white px-3 py-1 rounded-md hover:bg-blue-500">
                                Voir le chapitre
                            </a>
                            <!-- Bouton pour télécharger le chapitre -->
                            <a 
                                href="../methode/download_chapter.php?chapter_path=<?php echo urlencode($chapter['comics_path']); ?>" 
                                class="bg-green-600 text-white px-3 py-1 rounded-md hover:bg-green-500">
                                Télécharger
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
    <?php require_once("comments_comics.php"); ?>
</body>

<!-- Script JS -->
<script>
function redirectToChapter(chapterPath) {
    window.location.href = 'test.php?chapter_path=' + encodeURIComponent(chapterPath);
    console.log(chapterPath);
}
</script>