<?php 
include_once("../page/header.php");
include_once("../methode/db.php");
?>

<?php

class Comics
{
    private $id_comics;
    private $title_comics;
    private $author;
    private $category;
    private $created_at;
    private $img_path;

    public function __construct($id_comics, $title_comics, $author, $created_at, $img_path,$category)
    {
        $this->id_comics = $id_comics;
        $this->title_comics = $title_comics;
        $this->author = $author;
        $this->created_at = $created_at;
        $this->img_path = $img_path;
        $this->category = $category;
    }

    public function get_id_comics()
    {
        return $this->id_comics;
    }

    public function get_title_comics()
    {
        return $this->title_comics;
    }

    public function get_author()
    {
        return $this->author;
    }

    public function get_category()
    {
        return $this->category;
    }

    public function get_created_at()
    {
        return $this->created_at;
    }

    public function get_img()
    {
        return $this->img_path;
    }
}


class ComicsManager
{
    private $pdo;
    private $comics = [];

    public function __construct($pdo)
    {
        $this->pdo = $pdo;  // Initialisation de la connexion PDO

    }

    public function add_comics($comics)
    {
        $this->comics[] = $comics;
    }

    public function get_comics()
    {
        return $this->comics;
    }

    public function display_comics()
    {
        foreach ($this->comics as $comics) {
            echo '<img src="' . htmlspecialchars($comics->get_img()) . '" >';
            echo "ID: " . htmlspecialchars($comics->get_id_comics()) . '<br>';
            echo "Title: " . htmlspecialchars($comics->get_title_comics()) . '<br>';
            echo "Author: " . htmlspecialchars($comics->get_author()) . '<br>';
            echo "Category: " . htmlspecialchars($comics->get_category()) . '<br>';
            echo "Created At: " . htmlspecialchars($comics->get_created_at()) . '<br>';
            echo "Likes: " . $this->getThumbsUpCount($comics->get_id_comics()) . " 👍<br>";
            echo "<hr>";
        }
    }

    // Méthode pour récupérer le nombre total de pouces levés pour un comic spécifique
    public function getThumbsUpCount($comicId)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT COUNT(rate) AS thumbs_up 
            FROM rate 
            WHERE id_comics = :comic_id AND rate = 1
        ");
        $stmt->execute([':comic_id' => $comicId]);
        $result = $stmt->fetch();

        return $result['thumbs_up'] ?? 0;  // Retourne 0 si aucun like n'est trouvé
    }

    // Méthode pour récupérer tous les comics avec leurs pouces levés
    public function getAllComicsWithRatings()
    {
        global $pdo;
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
        $comicsWithRatings = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $comicsList = [];
        foreach ($comicsWithRatings as $comicData) {
            $comic = new Comics(
                $comicData['id_comics'],
                $comicData['title_comics'],
                $comicData['author'],
                $comicData['created_at'],
                $comicData['image_path'],
                $comicData['category']
            );
            $comic->thumbs_up = $comicData['thumbs_up']; // Ajout d'un champ dynamique pour les likes
            $comicsList[] = $comic;
        }

        return $comicsList;
    }

    public function search_comics($search)
    {
        $results = [];

        foreach ($this->comics as $comics) {
            if (stripos($comics->get_title_comics(), $search) !== false) {
                $results[] = $comics;
            }
        }

        return $results;
    }

    public function rateComic($userId, $comicId, $rate)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            INSERT INTO rate (id_user, id_comics, rate) 
            VALUES (:user_id, :comic_id, :rate)
            ON DUPLICATE KEY UPDATE rate = :rate
        ");
        $stmt->execute([
            ':user_id' => $userId,
            ':comic_id' => $comicId,
            ':rate' => $rate
        ]);
    }

    public function getAverageRating($comicId)
    {
        global $pdo;
        $stmt = $pdo->prepare("
            SELECT AVG(rate) as average_rating 
            FROM rate 
            WHERE id_comics = :comic_id
        ");
        $stmt->execute([':comic_id' => $comicId]);
        $average = $stmt->fetchColumn();

        return $average !== null ? round($average, 1) : 0;
    }
    public function getPopularComics()
    {
        $stmt = $this->pdo->query("
            SELECT 
                c.id_comics,
                c.title_comics,
                c.image_path,
                c.author,
                c.created_at,
                c.category,
                c.description,
                SUM(ch.view_count) AS total_views,
                COUNT(DISTINCT r.id_user) AS notation
            FROM comics c
            LEFT JOIN chapter ch ON c.id_comics = ch.id_comics
            LEFT JOIN rate r ON c.id_comics = r.id_comics
            GROUP BY c.id_comics, c.title_comics, c.image_path, c.author, c.created_at, c.category, c.description
            ORDER BY total_views DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRecentComics()
    {
        $stmt = $this->pdo->query("
            SELECT 
                c.id_comics, 
                c.title_comics, 
                c.image_path, 
                c.author, 
                c.created_at, 
                c.category, 
                c.description, 
                SUM(ch.view_count) AS total_views,
                COUNT(DISTINCT r.id_user) AS notation
            FROM comics c
            LEFT JOIN chapter ch ON c.id_comics = ch.id_comics
            LEFT JOIN rate r ON c.id_comics = r.id_comics
            GROUP BY c.id_comics
            ORDER BY c.created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBestComic($comicId)
    {
        $stmt = $this->pdo->prepare("
            SELECT 
                c.*,
                COUNT(DISTINCT r.id_user) AS notation
            FROM comics c
            LEFT JOIN rate r ON c.id_comics = r.id_comics
            WHERE c.id_comics = :comicId
            GROUP BY c.id_comics
        ");
        $stmt->execute([':comicId' => $comicId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

class Chapter
{
    private $id_chapter;
    private $title_chapter;
    private $id_comics;
    private $view_count;
    private $comics_path;
    private $created_at;
    private $page_number;

    public function __construct($id_chapter, $title_chapter, $id_comics, $view_count, $comics_path, $created_at, $page_number)
    {
        $this->id_chapter = $id_chapter;
        $this->title_chapter = $title_chapter;
        $this->id_comics = $id_comics;
        $this->view_count = $view_count;
        $this->comics_path = $comics_path;
        $this->created_at = $created_at;
        $this->page_number = $page_number;
    }

    public function get_id_chapter()
    {
        return $this->id_chapter;
    }

    public function get_title_chapter()
    {
        return $this->title_chapter;
    }

    public function get_id_comics()
    {
        return $this->id_comics;
    }

    public function get_view_count()
    {
        return $this->view_count;
    }

    public function get_comics_path()
    {
        return $this->comics_path;
    }

    public function get_created_at()
    {
        return $this->created_at;
    }

    public function get_page_number()
    {
        return $this->page_number;
    }


    function addChapterPagesToJson($chapterId, $pagesArray) {
        $jsonFilePath = 'chapter_pages.json';

        $jsonData = [];
        if (file_exists($jsonFilePath)) {
            $jsonContent = file_get_contents($jsonFilePath);
            $jsonData = json_decode($jsonContent, true);
        }

        $jsonData[$chapterId] = $pagesArray;

        file_put_contents($jsonFilePath, json_encode($jsonData, JSON_PRETTY_PRINT));
    }

    function getChapterPagesFromJson($chapterId) {
        $jsonFilePath = 'chapter_pages.json';

        if (file_exists($jsonFilePath)) {
            $jsonContent = file_get_contents($jsonFilePath);
            $jsonData = json_decode($jsonContent, true);

            if (isset($jsonData[$chapterId])) {
                return $jsonData[$chapterId];
            }
        }
        return null;
    }
    function addViewCount($chapterId, $pdo) {

        $stmt = $pdo->prepare("UPDATE chapter SET view_count = view_count + 1 WHERE id_chapter = :chapter");
        $stmt->execute([':chapter' => $chapterId]);

}

}

?>
