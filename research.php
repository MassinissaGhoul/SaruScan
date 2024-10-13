<?php
session_start();
include_once("./header.php");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>recherche comics</title>
</head>

<body>
    <!-- research comics -->
    <div>
    <form action="" method="post">
        <label for="research">recherche nom du comics</label>
        <input type="text" name="research" id="research">
        <input type="submit" name="submit" value="research">
    </form></div>

    <?php
    //envoie de la recherche
    if (isset($_POST["submit"])) {
        $research = $_POST["research"];
        //recherche les comics
        $research_req = $bdd->prepare('SELECT * from comics WHERE title_comics LIKE :r');
        $research_req->execute(["r" => "%". $research. "%"]);
        if ($research_req->rowCount() == 0) {
            echo("<div class='middle'> Aucun résultat </div><br>");
        }
        $research = $research_req->fetchAll();
        //affichage des résultats
        foreach ($research as $r) {
            echo("<div>");
            echo($r["title_comics"] . " de " . $r["author"]);
            echo("</div>");
        }
    }
    ?>
</body>
</html>