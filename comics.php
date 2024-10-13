<?php
class comics
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
        echo $this->id_comics;

    }

    public function get_title_comics()
    {
        echo $this->title_comics;
    }

    public function get_author()
    {
        echo $this->created_at;
    }

    public function get_category()
    {
        echo $this->category;
    }

}


class comics_manager
{
    private $comics = [];
    public function __construct($comics)
    {
        $this->comics = $comics;
    }

    public function get_comics()
    {
        return $this->comics;
    }

    public function add_comics($comics)
    {
        $this->comics[] = $comics;
    }

    public function display_comics()
    {
        foreach ($this->comics as $comics) {
            echo $comics->get_id_comics() . '<br>';
            echo $comics->get_title_comics() . '<br>';
            echo $comics->get_author() . '<br>';
            echo $comics->get_category() . '<br>';
        }
    }
}



class chapter
{

}

?>