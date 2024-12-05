<?php
require_once '../class/comics.php';
require_once '../methode/db.php';

// Gérer la sélection du manga
if (isset($_GET['manga_id'])) {
    $_SESSION['manga_id'] = $_GET['manga_id'];
}

// Définir le manga sélectionné
$manga_id = $_SESSION['manga_id'] ?? null;

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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaruScan</title>
</head>
<body>
    <h1>Choisissez un manga et un chapitre</h1>

    <!-- Menu déroulant pour choisir un manga -->
    <form method="GET" action="">
        <label for="manga_id">Manga :</label>
        <select name="manga_id" id="manga_id" onchange="this.form.submit()">
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
        <form method="GET" action="">
            <label for="chapter_path">Chapitre :</label>
            <input type="hidden" name="manga_id" value="<?php echo htmlspecialchars($manga_id); ?>">
            <select name="chapter_path" id="chapter_path" onchange="this.form.submit()">
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
        <h2>Pages du chapitre : <?php echo htmlspecialchars($chapter_path); ?></h2>

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

        <div id="chapter-pages">
            <?php foreach ($pagesArray as $index => $pagePath): ?>
                <img src="<?php echo $pagePath; ?>" style="display: none; max-width: 600px; max-height: 800px;" id="page-<?php echo $index; ?>" class="chapter-page">
            <?php endforeach; ?>
        </div>

        <script>
            let currentPage = 0;
            const pages = document.querySelectorAll('.chapter-page');
            const totalPages = pages.length;

            if (pages.length > 0) {
                pages[currentPage].style.display = 'block';
            }

            function showPage(pageIndex) {
                if (pageIndex >= 0 && pageIndex < totalPages) {
                    pages[currentPage].style.display = 'none';
                    pages[pageIndex].style.display = 'block';
                    currentPage = pageIndex;
                } else if (pageIndex >= totalPages) {
                    loadNextChapter();
                }
            }

            function loadNextChapter() {
                const currentChapterPath = new URLSearchParams(window.location.search).get('chapter_path');
                const chapterOptions = Array.from(document.querySelectorAll('#chapter_path option'));
                const currentIndex = chapterOptions.findIndex(option => option.value === currentChapterPath);
                const nextOption = chapterOptions[currentIndex + 1];

                if (nextOption) {
                    // Rediriger vers le chapitre suivant
                    window.location.href = `?chapter_path=${nextOption.value}&manga_id=<?php echo $manga_id; ?>`;
                } else {
                    alert('Fin des chapitres !');
                }
            }

            document.getElementById('chapter-pages').addEventListener('click', (event) => {
                const pageWidth = pages[currentPage].offsetWidth;
                const clickPosition = event.clientX;

                if (clickPosition < pageWidth / 2) {
                    const prevPage = currentPage - 1 >= 0 ? currentPage - 1 : 0;
                    showPage(prevPage);
                } else {
                    const nextPage = currentPage + 1;
                    showPage(nextPage);
                }
            });
        </script>
    <?php endif; ?>
</body>
</html>
