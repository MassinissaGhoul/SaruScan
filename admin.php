class ComicsManager
{
    private $comics = [];
    private $db; // PDO instance

    public function __construct($db, $comics = [])
    {
        $this->db = $db; // Pass PDO connection on instantiation
        $this->comics = $comics;
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
            echo "ID: " . $comics->get_id_comics() . '<br>';
            echo "Title: " . $comics->get_title_comics() . '<br>';
            echo "Author: " . $comics->get_author() . '<br>';
            echo "Category: " . $comics->get_category() . '<br>';
            echo "Created At: " . $comics->get_created_at() . '<br>';
            echo "<hr>";
        }
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

    // Method to add a comic to the database
    public function addComicsToDB($title_comics, $author, $category, $created_at)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO comics (title_comics, author, category, created_at) VALUES (:title_comics, :author, :category, :created_at)");
            $stmt->bindParam(':title_comics', $title_comics);
            $stmt->bindParam(':author', $author);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':created_at', $created_at);

            if ($stmt->execute()) {
                echo "Comics successfully added!";
            } else {
                echo "Failed to add comics.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // Method to update an existing comic in the database
    public function updateComicsInDB($id_comics, $title_comics, $author, $category, $created_at)
    {
        try {
            $stmt = $this->db->prepare("UPDATE comics SET title_comics = :title_comics, author = :author, category = :category, created_at = :created_at WHERE id_comics = :id_comics");
            $stmt->bindParam(':id_comics', $id_comics);
            $stmt->bindParam(':title_comics', $title_comics);
            $stmt->bindParam(':author', $author);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':created_at', $created_at);

            if ($stmt->execute()) {
                echo "Comics successfully updated!";
            } else {
                echo "Failed to update comics.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
