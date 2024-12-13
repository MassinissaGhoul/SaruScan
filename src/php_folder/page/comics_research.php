<?php
require_once 'header.php';
require_once '../class/comics.php';
 
// Récupération des comics dans la base de données
$comicsManager = new ComicsManager();
$query = $pdo->query("SELECT * FROM comics ");


while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $comics = new Comics($row['id_comics'], $row['title_comics'], $row['author'], $row['created_at'], $row['image_path'], $row["category"]);
    $comicsManager->add_comics($comics);
}

// Récupération du terme de recherche depuis le formulaire
$search = isset($_GET['research']) ? $_GET['research'] : '';

?>

<?php
// Si un terme de recherche est présent
if (!empty($search)) {
    // Recherche dans les objets comics
    $results = $comicsManager->search_comics($search);

    if (!empty($results)) {
        echo "<h2>Résultats de recherche :</h2>";
        foreach ($results as $comics) {
            echo '<img src ="' . $comics->get_img() . '" width="200px">';
            echo "Titre : <a href=\"comics_page.php?title=" . urlencode($comics->get_title_comics()) . "\">" . htmlspecialchars($comics->get_title_comics()) . "</a> <br>";
            echo "Auteur : " . $comics->get_author() . "<br>";
            echo "category : " .$comics->get_category() . "<br>";
        }
    } else {
        echo "Aucun résultat trouvé pour '$search'.";
    }
}

?>


