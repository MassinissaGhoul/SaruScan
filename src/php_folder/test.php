<?php
require_once 'comics.php'; 

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

$comicsManager = new ComicsManager(); 
$query = $pdo->query("SELECT * FROM comics");

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $comics = new Comics($row['id_comics'], $row['title_comics'], $row['author'], $row['created_at'], $row['category']);
    $comicsManager->add_comics($comics);
}

$comicsManager->display_comics();

$id_chapter = 1;
$title_chapter = "Chapitre 1";
$id_comics = 1;
$view_count = 0;
$comics_path = 'comics/Dr.Stone/Chapter02'; 
$created_at = date('Y-m-d');
$page_number = 1;

$chapter = new Chapter($id_chapter, $title_chapter, $id_comics, $view_count, $comics_path, $created_at, $page_number);

$pagesArray = [];
if (is_dir($comics_path)) {
    echo "Dossier trouvé : $comics_path<br>";
    $files = scandir($comics_path);
    foreach ($files as $file) {
        if (is_file($comics_path . '/' . $file)) {
            $pagesArray[] = $comics_path . '/' . $file;
        }
    }
    if (!empty($pagesArray)) {
        $chapter->addChapterPagesToJson($id_chapter, $pagesArray);
        echo "Pages ajoutées au JSON.<br>";
    } else {
        echo "Aucune page trouvée dans le dossier.<br>";
    }
} else {
    echo "Dossier introuvable : $comics_path<br>";
}

$chapterPages = $chapter->getChapterPagesFromJson($id_chapter);

if ($chapterPages) {
    echo "<div id='chapter-pages'>";
    foreach ($chapterPages as $index => $pagePath) {
        echo "<img src='$pagePath' style='display: none;' id='page-$index' class='chapter-page'>";
    }
    echo "</div>";
} else {
    echo "Aucune page trouvée dans le JSON pour le chapitre ID $id_chapter.<br>";
}
?>


<script>
    let currentPage = 0;
    const pages = document.querySelectorAll('.chapter-page');
    if (pages.length > 0) {
        pages[currentPage].style.display = 'block';
    }

    document.addEventListener('click', () => {
        pages[currentPage].style.display = 'none';
        currentPage = (currentPage + 1) % pages.length;
        pages[currentPage].style.display = 'block';
    });
</script>