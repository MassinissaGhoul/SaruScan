<?php
include_once("header.php");

if (!isset($_SESSION['user']['id_user'])) {
  echo "<script>alert('Connectez-vous pour voir vos favoris.'); window.location.href = '../page/login.php';</script>";
  exit;
}

$id_user = $_SESSION['user']['id_user'];

// Récupérer les favoris de l'utilisateur
$stmt = $pdo->prepare("
    SELECT comics.id_comics, comics.title_comics, comics.image_path
    FROM favorite
    JOIN comics ON favorite.id_comics = comics.id_comics
    WHERE favorite.id_user = ?
");
$stmt->execute([$id_user]);
$favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Mes Favoris</title>
</head>

<body class="bg-gray-900 text-gray-100 font-sans">
  <header class="bg-gray-800 p-4 shadow-md">
    <h1 class="text-3xl font-bold text-center">Mes Favoris</h1>
  </header>

  <main class="p-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      <?php foreach ($favorites as $comic): ?>
        <a href="comics_page.php?title=<?php echo urlencode($comic['title_comics']); ?>" class="block group">
          <div class="bg-gray-800 rounded-lg overflow-hidden shadow-md hover:shadow-xl transform hover:scale-105 transition duration-300">
            <img src="<?php echo $comic['image_path']; ?>" alt="Comic Image" class="w-full h-56 object-cover">
            <div class="p-4">
              <h2 class="text-lg font-semibold group-hover:text-indigo-400 transition duration-300">
                <?php echo htmlspecialchars($comic['title_comics']); ?>
              </h2>
            </div>
          </div>
        </a>
      <?php endforeach; ?>
    </div>
  </main>


</body>

</html>