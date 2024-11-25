<?php
require_once 'comics.php';

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
    echo "Connexion réussie à la base de données!<br>";
} catch (PDOException $e) {
    throw new PDOException($e->getMessage(), (int)$e->getCode());
}

// Gérer la sélection du chapitre
if (isset($_GET['chapter_path'])) {
    $_SESSION['comics_path'] = $_GET['chapter_path'];
}

// Récupérer le chemin du chapitre sélectionné
if (isset($_SESSION['comics_path'])) {
    $comics_path = $_SESSION['comics_path'];
} else {
    $comics_path = null;
}

// Charger la liste des chapitres pour le menu déroulant
$chaptersQuery = $pdo->query("SELECT id_chapter, title_chapter, comics_path FROM chapter ORDER BY id_chapter ASC");
$chapters = $chaptersQuery->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SaruScan</title>
</head>
<body>
    <h1>Choisissez un chapitre</h1>

    <!-- Menu déroulant pour choisir un chapitre -->
    <form method="GET" action="">
        <select name="chapter_path" onchange="this.form.submit()">
            <option value="">-- Sélectionner un chapitre --</option>
            <?php foreach ($chapters as $chapter): ?>
                <option value="<?php echo htmlspecialchars($chapter['comics_path']); ?>" 
                    <?php echo ($comics_path === $chapter['comics_path']) ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($chapter['title_chapter']); ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($comics_path): ?>
        <h2>Pages du chapitre : <?php echo htmlspecialchars($comics_path); ?></h2>

        <?php
        // Charger les pages du chapitre sélectionné
        $pagesArray = [];
        if (is_dir($comics_path)) {
            $files = scandir($comics_path);
            foreach ($files as $file) {
                if (is_file($comics_path . '/' . $file)) {
                    $pagesArray[] = $comics_path . '/' . $file;
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
                }
            }

            async function updateChapterPath(newPath) {
                const response = await fetch('update_comics_path.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ new_comics_path: newPath })
                });

                const result = await response.json();
                if (result.success) {
                    location.reload(); // Recharge la page pour afficher le nouveau chapitre
                } else {
                    console.error('Erreur lors de la mise à jour du chemin :', result.error);
                }
            }

            document.getElementById('chapter-pages').addEventListener('click', (event) => {
                const pageWidth = pages[currentPage].offsetWidth;
                const clickPosition = event.clientX;

                if (currentPage === totalPages - 1) {
                    // Fin du chapitre, passer au chapitre suivant
                    alert('Fin du chapitre ! Choisissez un autre chapitre.');
                }

                if (clickPosition < pageWidth / 2) {
                    const prevPage = currentPage - 1 >= 0 ? currentPage - 1 : totalPages - 1;
                    showPage(prevPage);
                } else {
                    const nextPage = (currentPage + 1) % totalPages;
                    showPage(nextPage);
                }
            });
        </script>
    <?php endif; ?>
</body>
</html>
