<?php
session_start();
include_once("./header.php");
if (isset($_GET["research"])) {
    $research = $_GET["research"];
} else {
    echo "Aucune recherche effectuée.";
    exit(); // Sortir si aucune recherche n'est fournie
}
?>

<body>
    <?php
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
    ?>
</body>
</html>