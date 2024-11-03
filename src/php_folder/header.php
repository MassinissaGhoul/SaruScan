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
    <link rel="stylesheet" href="../output.css">
</head>
<!-- Header -->
<header class="header_container">
    <div>
        <nav>
            <a href="comics.php">Comics</a>
            <a href="category.php">Category</a>
            <a href="favorite.php">Favorite</a>
            <a href="signin.php">Sign in</a>
        </nav>
    </div>
</header>

<!-- Search Bar -->
<div class="search_container">
    <form action="" method="get">
        <label for="research"> research the name of the comic</label>
        <input type="text" name="research" id="research" placeholder="Search Comics...">
    </form>
</div>

<?php
if (isset($_GET["research"]) && basename($_SERVER['PHP_SELF']) != 'comics_research.php') {
    header("Location: comics_research.php?research=" . $_GET["research"]);
}
?>