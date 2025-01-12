<?php
require_once '../class/comics.php';
require_once '../methode/db.php';
require_once("header.php");

// Récupérer et valider les paramètres
$manga_id = $_GET['manga_id'] ?? null;
$chapter_path = $_GET['chapter_path'] ?? null;


if (!$manga_id || !$chapter_path) {
    echo "<p class='text-center mt-8'>Manga ou chapitre non spécifié.</p>";
    exit();
}

// Charger la liste des mangas pour le menu déroulant
$mangasQuery = $pdo->query("SELECT id_comics, title_comics FROM comics ORDER BY title_comics ASC");
$mangas = $mangasQuery->fetchAll(PDO::FETCH_ASSOC);

// Gérer la sélection du chapitre
if (isset($_GET['chapter_path'])) {
    $_SESSION['chapter_path'] = $_GET['chapter_path'];
}

// Définir le chapitre sélectionné
$chapter_path = $_SESSION['chapter_path'] ?? null;

// Charger les chapitres du manga sélectionné
$chapters = [];
if ($manga_id) {
    $chaptersQuery = $pdo->prepare("SELECT id_chapter, title_chapter, comics_path FROM chapter WHERE id_comics = :id_comics ORDER BY id_chapter ASC");
    $chaptersQuery->execute([':id_comics' => $manga_id]);
    $chapters = $chaptersQuery->fetchAll(PDO::FETCH_ASSOC);
}


$chapter_id = null;
foreach ($chapters as $chapter) {
    if ($chapter['comics_path'] === $chapter_path) {
        $chapter_id = $chapter['id_chapter'];
        break;
    }
}

if ($chapter_id) {
    if (!isset($_SESSION['viewed_chapters'])) {
        $_SESSION['viewed_chapters'] = []; // Initialiser si nécessaire
    }

    if (!in_array($chapter_id, $_SESSION['viewed_chapters'])) {
        $_SESSION['viewed_chapters'][] = $chapter_id;

        // Incrémenter le compteur de vues dans la base de données
        $stmt = $pdo->prepare("UPDATE chapter SET view_count = view_count + 1 WHERE id_chapter = :chapter_id");
        $stmt->execute([':chapter_id' => $chapter_id]);

    }

} else {
    echo "<p class='text-center text-red-500'>Chapitre introuvable.</p>";
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaruScan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-200">
    <div class="container mx-auto px-4">
        <h1 class="text-center text-2xl font-bold mt-6">Choisissez un manga et un CHAPITRE</h1>

        <!-- Menu déroulant pour choisir un manga -->
        <form method="GET" action="" class="mt-4">
            <label for="manga_id" class="block mb-2">Manga :</label>
            <select name="manga_id" id="manga_id" onchange="this.form.submit()" 
                class="w-full bg-gray-800 text-gray-200 border border-gray-600 rounded-md px-4 py-2">
                <option value="">-- Sélectionner un manga --</option>
                <?php foreach ($mangas as $manga): ?>
                    <option value="<?php echo htmlspecialchars($manga['id_comics']); ?>" 
                        <?php echo ($manga_id == $manga['id_comics']) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($manga['title_comics']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>

        <?php if ($manga_id): ?>
            <!-- Menu déroulant pour choisir un chapitre -->
            <form method="GET" action="" class="mt-4">
                <label for="chapter_path" class="block mb-2">Chapitre :</label>
                <input type="hidden" name="manga_id" value="<?php echo htmlspecialchars($manga_id); ?>">
                <select name="chapter_path" id="chapter_path" onchange="this.form.submit()" 
                    class="w-full bg-gray-800 text-gray-200 border border-gray-600 rounded-md px-4 py-2">
                    <option value="">-- Sélectionner un chapitre --</option>
                    <?php foreach ($chapters as $chapter): ?>
                        <option value="<?php echo htmlspecialchars($chapter['comics_path']); ?>" 
                            <?php echo ($chapter_path === $chapter['comics_path']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($chapter['title_chapter']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </form>
        <?php endif; ?>

        <?php if ($chapter_path): ?>
            <div class="flex justify-center mt-8">
                <!-- Bouton pour changer de mode de lecture -->
                <button id="toggle-mode" class="px-6 py-2 bg-blue-600 hover:bg-blue-500 text-white rounded-lg font-semibold">
                    Mode Webtoon
                </button>
            </div>

            <div id="page-number" class="text-center text-xl font-semibold mt-8"></div>

            <?php
            // Charger les pages du chapitre sélectionné
            $pagesArray = [];
            if (is_dir($chapter_path)) {
                $files = scandir($chapter_path);
                foreach ($files as $file) {
                    if (is_file($chapter_path . '/' . $file)) {
                        $pagesArray[] = $chapter_path . '/' . $file;
                    }
                }
            }
            ?>

            <!-- Conteneur des pages (mode Manga - une page à la fois) -->
            <div id="chapter-pages" class="flex flex-col items-center justify-center min-h-screen">
                <?php foreach ($pagesArray as $index => $pagePath): ?>
                    <img src="<?php echo $pagePath; ?>" 
                        style="display: none;" 
                        id="page-<?php echo $index; ?>" 
                        class="chapter-page w-full max-w-3xl max-h-[80vh] object-contain rounded-lg shadow-md">
                <?php endforeach; ?>
            </div>

            <!-- Conteneur des pages pour le mode webtoon (scroll vertical) -->
            <div id="webtoon-mode" class="hidden flex flex-col items-center mt-6 space-y-6">
                <?php foreach ($pagesArray as $pagePath): ?>
                    <img src="<?php echo $pagePath; ?>" class="webtoon-image w-full max-w-3xl max-h-[900px] object-contain rounded-lg shadow-md">
                <?php endforeach; ?>
            </div>

            <div class="flex justify-between items-center mt-6 w-full max-w-3xl mx-auto">
                <!-- Bouton Précédent -->
                <button
                    onclick="loadPreviousChapter()"
                    class="px-6 py-2 bg-gray-800 text-gray-200 border border-gray-600 rounded-md hover:bg-gray-700 hover:border-gray-500 focus:outline-none focus:ring focus:ring-gray-500 focus:ring-opacity-50 transition">
                    PREVIOUS
                </button>

                <!-- Bouton Suivant -->
                <button
                    onclick="loadNextChapter()"
                    class="px-6 py-2 bg-gray-800 text-gray-200 border border-gray-600 rounded-md hover:bg-gray-700 hover:border-gray-500 focus:outline-none focus:ring focus:ring-gray-500 focus:ring-opacity-50 transition">
                    NEXT
                </button>
            </div>

            <script>
                let currentPage = 0;
                const pages = document.querySelectorAll('.chapter-page');
                const totalPages = pages.length;
                const chapterPagesContainer = document.getElementById('chapter-pages');
                const webtoonModeContainer = document.getElementById('webtoon-mode');
                const toggleModeButton = document.getElementById('toggle-mode');
                let isWebtoonMode = false;

                if (pages.length > 0) {
                    pages[currentPage].style.display = 'block';
                }

                function showPage(pageIndex) {
                    if (pageIndex >= 0 && pageIndex < totalPages) {
                        pages[currentPage].style.display = 'none';
                        pages[pageIndex].style.display = 'block';
                        currentPage = pageIndex;
                        document.getElementById('page-number').textContent = `Page ${currentPage + 1} sur ${totalPages}`;
                    }
                }

                if (pages.length > 0) {
                    document.getElementById('page-number').textContent = `Page 1 sur ${totalPages}`;
                }

                function loadNextChapter() {
                    const currentChapterPath = new URLSearchParams(window.location.search).get('chapter_path');
                    const chapterOptions = Array.from(document.querySelectorAll('#chapter_path option'));
                    const currentIndex = chapterOptions.findIndex(option => option.value === currentChapterPath);
                    const nextOption = chapterOptions[currentIndex + 1];

                    if (nextOption) {
                        window.location.href = `?chapter_path=${nextOption.value}&manga_id=<?php echo $manga_id; ?>`;
                    } else {
                        alert('Fin des chapitres !');
                    }
                }

                function loadPreviousChapter() {
                    const currentChapterPath = new URLSearchParams(window.location.search).get('chapter_path');
                    const chapterOptions = Array.from(document.querySelectorAll('#chapter_path option'));
                    const currentIndex = chapterOptions.findIndex(option => option.value === currentChapterPath);
                    const previousOption = chapterOptions[currentIndex - 1];

                    if (previousOption) {
                        window.location.href = `?chapter_path=${previousOption.value}&manga_id=<?php echo $manga_id; ?>`;
                    } else {
                        alert('Début des chapitres !');
                    }
                }

                document.getElementById('chapter-pages').addEventListener('click', (event) => {
                    const pageWidth = window.innerWidth;
                    const clickPosition = event.clientX;

                    if (clickPosition < pageWidth / 2) {
                        if (currentPage > 0) {
                            showPage(currentPage - 1);
                        } else {
                            loadPreviousChapter();
                        }
                    } else {
                        if (currentPage < totalPages - 1) {
                            showPage(currentPage + 1);
                        } else {
                            loadNextChapter();
                        }
                    }
                });

                toggleModeButton.addEventListener('click', () => {
                    isWebtoonMode = !isWebtoonMode;
                    if (isWebtoonMode) {
                        chapterPagesContainer.classList.add('hidden');
                        webtoonModeContainer.classList.remove('hidden');
                        toggleModeButton.textContent = "Mode Manga";
                    } else {
                        chapterPagesContainer.classList.remove('hidden');
                        webtoonModeContainer.classList.add('hidden');
                        toggleModeButton.textContent = "Mode Webtoon";
                    }
                });
            </script>
        <?php endif; ?>
    </div>
    <?php require_once("comments_comics.php"); ?>
</body>
</html>
