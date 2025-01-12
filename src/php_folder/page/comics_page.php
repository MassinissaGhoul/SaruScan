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

// Requête pour le nombre de likes
$like_req = $pdo->prepare("SELECT COUNT(rate) FROM rate JOIN comics WHERE comics.id_comics = rate.id_comics AND comics.title_comics = :t");
$like_req->execute([":t" => $title]);
$like = $like_req->fetch();

// Récupération des informations du comic
$comics_req = $pdo->prepare("SELECT * FROM comics WHERE title_comics = :t");
$comics_req->execute([':t' => $title]);

if ($comics_req->rowCount() === 0) {
    echo "<p class='text-center text-red-500'>Comics non trouvé.</p>";
    exit();
}

$comic = $comics_req->fetch();

// Requêtes pour le premier et le dernier chapitre
$first_chapter_req = $pdo->prepare("
    SELECT chapter.title_chapter, chapter.comics_path, chapter.view_count, chapter.created_at 
    FROM chapter 
    INNER JOIN comics ON comics.id_comics = chapter.id_comics 
    WHERE comics.title_comics = :t 
    ORDER BY chapter.created_at ASC 
    LIMIT 1
");
$last_chapter_req = $pdo->prepare("
    SELECT chapter.title_chapter, chapter.comics_path, chapter.view_count, chapter.created_at 
    FROM chapter 
    INNER JOIN comics ON comics.id_comics = chapter.id_comics 
    WHERE comics.title_comics = :t 
    ORDER BY chapter.created_at DESC 
    LIMIT 1
");

$first_chapter_req->execute([':t' => $title]);
$last_chapter_req->execute([':t' => $title]);

$first_chapter = $first_chapter_req->fetch(PDO::FETCH_ASSOC);
$last_chapter = $last_chapter_req->fetch(PDO::FETCH_ASSOC);

// Requête pour lister les chapitres
$chapter_req = $pdo->prepare("
    SELECT chapter.title_chapter, chapter.comics_path, chapter.view_count, chapter.created_at 
    FROM chapter 
    INNER JOIN comics ON comics.id_comics = chapter.id_comics 
    WHERE comics.title_comics = :t
");
$chapter_req->execute([':t' => $title]);
?>

<body class="bg-gray-900 text-gray-200">
    <div class="container mx-auto py-10 px-4">
        <!-- Section Comic -->
        <div class="comic-container bg-gray-800 rounded-lg shadow-lg p-8 mb-10">
            <div class="w-full md:w-1/3 mx-auto mb-8">
                <img 
                    src="<?php echo htmlspecialchars($comic['image_path']); ?>" 
                    alt="Image du Comic" 
                    class="comic-image rounded-lg shadow-md w-full object-cover">
            </div>

            <div class="text-center">
                <h1 class="text-3xl font-extrabold text-white mb-4"><?php echo htmlspecialchars($comic['title_comics']); ?></h1>

                <div class="flex justify-center gap-4 items-center mb-6">
                    <!-- Bouton Like -->
                    <div class="flex items-center">
                        <span class="text-blue-300 text-lg font-semibold">
                            <?php echo htmlspecialchars($like["COUNT(rate)"]); ?>
                        </span>
                        <a 
                            class="ml-2 bg-transparent text-blue-500 hover:text-blue-300 text-2xl"
                            href="../methode/add_like.php?id_comics=<?php echo $comic["id_comics"]; ?>"
                        >
                            👍
                        </a>
                    </div>
                </div>

                <div class="bg-gray-700 p-6 rounded-lg shadow-md mb-8">
                    <h2 class="text-2xl font-bold text-white mb-3">Synopsis</h2>
                    <p class="text-gray-300"><?php echo htmlspecialchars($comic['description'] ?? 'Aucun synopsis disponible.'); ?></p>
                </div>

                <!-- Boutons de navigation -->
                <div class="flex justify-center space-x-4 mb-8">
                    <!-- Commencer la lecture -->
                    <?php if ($first_chapter): ?>
                        <?php $first_chapter_path = str_replace("\\", "/", $first_chapter['comics_path']); ?>
                        <a href="test.php?chapter_path=<?php echo urlencode($first_chapter_path); ?>&manga_id=<?php echo htmlspecialchars($comic['id_comics']); ?>" 
 
                           class="bg-blue-600 hover:bg-blue-500 text-white px-5 py-3 rounded-lg font-semibold">
                            Commencer la lecture
                        </a>
                    <?php else: ?>
                        <button disabled class="bg-gray-500 text-white px-5 py-3 rounded-lg font-semibold cursor-not-allowed">
                            Pas de chapitre disponible
                        </button>
                    <?php endif; ?>

                    <!-- Dernier chapitre -->

                </div>
            </div>
        </div>

        <!-- Liste des chapitres -->
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg mb-10">
            <h2 class="text-2xl font-bold text-white mb-4 text-center">Chapitres</h2>
            <div class="chapter-list space-y-3">
                <?php while ($chapter = $chapter_req->fetch()): ?>
                    <div class="chapter-item bg-gray-700 rounded-lg p-4 flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold text-white"><?php echo htmlspecialchars($chapter["title_chapter"]); ?></h3>
                            <span class="text-sm text-gray-400">Ajouté le <?php echo htmlspecialchars($chapter["created_at"]); ?></span>
                        </div>
                        <div class="flex space-x-4">
                            <!-- Corriger le chemin du chapitre -->
                            <?php $chapter_path = str_replace("\\", "/", $chapter['comics_path']); ?>

                            <a 
                                href="test.php?chapter_path=<?php echo urlencode($chapter_path); ?>&manga_id=<?php echo htmlspecialchars($comic['id_comics']); ?>" 
                                class="bg-blue-600 hover:bg-blue-500 text-white px-4 py-2 rounded-md">
                                Lire
                            </a>
                            <a 
                                href="../methode/download_chapter.php?chapter_path=<?php echo urlencode($chapter_path); ?>" 
                                class="bg-green-600 hover:bg-green-500 text-white px-4 py-2 rounded-md">
                                Télécharger
                            </a>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>

        <!-- Section des commentaires -->
        <div class="bg-gray-800 p-6 rounded-lg shadow-lg mb-10">
            <h2 class="text-2xl font-bold text-white mb-4 text-center"></h2>
            <?php include_once("comments_comics.php"); ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-center text-gray-400 py-4 mt-8">
        <p class="animate-scroll">Reda Steven Massi SaruScan</p>
    </footer>
</body>


<!-- Script JS -->
<script>
function redirectToChapter(chapterPath) {
    window.location.href = 'test.php?chapter_path=' + encodeURIComponent(chapterPath);
    console.log(chapterPath);
}
</script>