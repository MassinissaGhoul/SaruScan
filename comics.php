<?php
class Comics
{
    private $id_comics;
    private $title_comics;
    private $author;
    private $created_at;
    private $category;

    public function __construct($id_comics, $title_comics, $author, $created_at, $category)
    {
        $this->id_comics = $id_comics;
        $this->title_comics = $title_comics;
        $this->author = $author;
        $this->created_at = $created_at;
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

    public function get_created_at()
    {
        return $this->created_at;
    }

    public function get_category()
    {
        return $this->category;
    }
}


class ComicsManager
{
    private $comics = [];

    public function __construct($comics = [])
    {
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
    
}

?>
