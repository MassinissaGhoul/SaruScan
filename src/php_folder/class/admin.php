<?php

class ComicsManager
{
    private $db; 

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function addComicsToDB($title_comics, $author, $category, $image_path, $created_at)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO comics (title_comics, author, category, image_path, created_at) 
                                        VALUES (:title_comics, :author, :category, :image_path, :created_at)");
            $stmt->execute([
                ':title_comics' => $title_comics,
                ':author' => $author,
                ':category' => $category,
                ':image_path' => $image_path,
                ':created_at' => $created_at,
            ]);
            echo "Comic ajouté avec succès !<br>";
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout du comic : " . $e->getMessage() . "<br>";
        }
    }
    
    public function getAllComics()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM comics");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des comics : " . $e->getMessage() . "<br>";
        }
    }

    public function deleteComic($comicId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM comics WHERE id_comics = :comicId");
            $stmt->execute([':comicId' => $comicId]);
            echo "Comic supprimé avec succès !<br>";
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression du comic : " . $e->getMessage() . "<br>";
        }
    }

    public function updateComic($comicId, $title, $author, $category) {
        try {
            $stmt = $this->db->prepare("UPDATE comics SET title_comics = :title, author = :author, category = :category WHERE id_comics = :comicId");
            $stmt->execute([
                ':title' => $title,
                ':author' => $author,
                ':category' => $category,
                ':comicId' => $comicId,
            ]);
            echo "Comic mis à jour avec succès !<br>";
        } catch (PDOException $e) {
            echo "Erreur lors de la mise à jour du comic : " . $e->getMessage() . "<br>";
        }
    }
}

class ChapterManager
{
    private $db; 

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function addChapterToDB($id_comics, $title_chapter, $comics_path, $page_number, $created_at)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO chapter (id_comics, title_chapter, comics_path, created_at, view_count, page_number) 
                                        VALUES (:id_comics, :title_chapter, :comics_path, :created_at, 0, :page_number)");
            $stmt->execute([
                ':id_comics' => $id_comics,
                ':title_chapter' => $title_chapter,
                ':comics_path' => $comics_path,
                ':created_at' => $created_at,
                ':page_number' => $page_number,
            ]);
            echo "Chapitre ajouté avec succès !<br>";
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout du chapitre : " . $e->getMessage() . "<br>";
        }
    }

    public function getChaptersByComic($id_comics)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM chapter WHERE id_comics = :id_comics");
            $stmt->execute([':id_comics' => $id_comics]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des chapitres : " . $e->getMessage() . "<br>";
        }
    }
}
