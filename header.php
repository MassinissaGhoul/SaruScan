<?php try {
$bdd = new PDO('mysql:host=localhost;dbname=saruscan;charset=utf8', 'root', '', [PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
die('Erreur : ' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<header> 
    <div>
        <nav> <a href="comics.php">Comics</a> <a href="favorite.php">Favorite</a> <a href="signin.php">Sign in</a></nav>
        <div>    
            <form action="" method="get">
                <label for="research">recherche nom du comics</label>
                <input type="text" name="research" id="research">
            </form>
        </div>
    </div>
</header>

<?php 
    if (isset($_GET["research"]) && basename($_SERVER['PHP_SELF']) != 'comics_research.php') {
        header("Location: comics_research.php?research=". $_GET["research"]);
    }
?>