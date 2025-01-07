<?php
require_once 'header.php';
require_once '../class/comics.php';

// Récupération des comics dans la base de données
$comicsManager = new ComicsManager();
$query = $pdo->query("SELECT * FROM comics ");

while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
    $comics = new Comics(
        $row['id_comics'],
        $row['title_comics'],
        $row['author'],
        $row['created_at'],
        $row['image_path'],
        $row["category"]
    );
    $comicsManager->add_comics($comics);
}

// Récupération du terme de recherche depuis le formulaire
$search = isset($_GET['research']) ? $_GET['research'] : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Recherche de Comics</title>
    <!-- Lien CDN Tailwind (à adapter au besoin) -->
    <link rel="stylesheet" href="../../style.css">
    <link rel="stylesheet" href="../../input.css">
</head>
<body class="min-h-screen bg-gray-900 text-gray-100 text-xs">
    <div class="container mx-auto py-4 px-2">
        <!-- Titre principal, plus petit -->
        <h1 class="text-sm font-semibold text-center mb-4">Liste des Comics</h1>

        <?php
        // Si on veut gérer une recherche (GET) sans barre de recherche visible
        if (!empty($search)) {
            $results = $comicsManager->search_comics($search);

            if (!empty($results)) {
                echo "<h2 class='text-xs font-medium mb-3'>Résultats pour : <span class='text-blue-400'>" 
                     . htmlspecialchars($search) . "</span></h2>";
                
                // Grid des résultats
                echo "<div class='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4'>";
                
                foreach ($results as $comics) {
                    echo "<div class='bg-gray-800 rounded shadow p-3 flex flex-col items-center'>";
                        
                        // Conteneur fixe pour uniformiser la taille
                        echo "<div class='w-36 h-48 mb-2 overflow-hidden flex items-center justify-center'>";
                            echo "<img 
                                    src='" . $comics->get_img() . "' 
                                    alt='Couverture' 
                                    class='object-cover w-full h-full'
                                  />";
                        echo "</div>";

                        // Titre
                        echo "<h3 class='text-xs font-semibold mb-1'>";
                            echo "<a href='comics_page.php?title=" . urlencode($comics->get_title_comics()) . "'>";
                            echo htmlspecialchars($comics->get_title_comics());
                            echo "</a>";
                        echo "</h3>";

                        // Auteur
                        echo "<p class='mb-1'>Auteur : <span class='font-normal'>"
                             . htmlspecialchars($comics->get_author()) 
                             . "</span></p>";

                        // Catégorie
                        echo "<p class='mb-1'>Catégorie : <span class='font-normal'>"
                             . htmlspecialchars($comics->get_category()) 
                             . "</span></p>";

                    echo "</div>";
                }
                echo "</div>";
            } else {
                echo "<p class='text-red-500'>Aucun résultat trouvé pour '" 
                     . htmlspecialchars($search) . "'.</p>";
            }

        } else {
            // Affichage de tous les comics si aucun terme de recherche
            echo "<div class='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4'>";
            
            foreach ($comicsManager->get_all_comics() as $comics) {
                echo "<div class='bg-gray-800 rounded shadow p-3 flex flex-col items-center'>";
                    
                    // Conteneur fixe pour uniformiser la taille
                    echo "<div class='w-36 h-48 mb-2 overflow-hidden flex items-center justify-center'>";
                        echo "<img 
                                src='" . $comics->get_img() . "' 
                                alt='Couverture' 
                                class='object-cover w-full h-full'
                              />";
                    echo "</div>";

                    // Titre
                    echo "<h3 class='text-xs font-semibold mb-1'>";
                        echo "<a href='comics_page.php?title=" . urlencode($comics->get_title_comics()) . "'>";
                        echo htmlspecialchars($comics->get_title_comics());
                        echo "</a>";
                    echo "</h3>";

                    // Auteur
                    echo "<p class='mb-1'>Auteur : <span class='font-normal'>"
                         . htmlspecialchars($comics->get_author()) 
                         . "</span></p>";

                    // Catégorie
                    echo "<p class='mb-1'>Catégorie : <span class='font-normal'>"
                         . htmlspecialchars($comics->get_category()) 
                         . "</span></p>";

                echo "</div>";
            }
            echo "</div>";
        }
        ?>
    </div>
</body>

</html>