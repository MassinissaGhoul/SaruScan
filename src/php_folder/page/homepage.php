<?php
include_once("header.php");
include_once("../methode/db.php");
require_once("../class/comics.php");
require_once("../class/users.php");

// Initialisation du ComicsManager
$comicsManager = new ComicsManager($pdo);

// Récupération des données
$popularComics = $comicsManager->getPopularComics();  // Comics populaires
$recentComics = $comicsManager->getRecentComics();  // Comics récents
$bestComic = $comicsManager->getBestComic(2);  // Meilleur comic pour le carrousel
?>

<body class="bg-gray-900 text-gray-300">

  <!-- Main Content -->
  <main class="container mx-auto px-4 py-8">

    <!-- Recommendations and Popular Section -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-8">

      <!-- Recommendations (Carousel) -->
      <div id="carousel" class="relative bg-gray-800 rounded-lg shadow-lg overflow-hidden w-full mx-auto h-auto">
        <div class="carousel-item block absolute inset-0">
          <div class="w-full h-4/5"> 
            <img src="<?php echo $bestComic['image_path']; ?>" alt="Best Comic Image" class="w-full h-full object-cover aspect-square">
          </div>
          <div class="p-4 h-1/5 flex flex-col justify-center">
            <div class="flex items-center justify-between text-yellow-400">
              <span><?php echo $bestComic["notation"] ?> 👍</span>
            </div>
            <h3 class="text-lg font-bold mt-2 text-center"><?php echo $bestComic['title_comics']; ?></h3>
            <p class="text-sm text-gray-400 text-center"><?php echo $bestComic['description']; ?></p>
          </div>
        </div>
      </div>

      <!-- Popular Comics -->
      <div>
        <div class="bg-gray-800 rounded shadow-lg p-4">
          <div class="flex justify-between mb-4">
            <button class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded">Weekly</button>
            <button class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded">Monthly</button>
            <button class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded">All</button>
          </div>

          <div class="space-y-4">
            <?php 
            $limitedComics = array_slice($popularComics, 0, 10);  // Prendre seulement les 10 premiers
            $compt = 1;
            foreach ($limitedComics as $comic):
            ?>
              <div class="flex items-center space-x-4">
                <span class="text-gray-400">#<?php echo $compt; ?></span>
                <img src="<?php echo $comic["image_path"]; ?>" alt="Comic" class="w-12 h-12 rounded">
                <div>
                  <a href="comics_page.php?title=<?php echo $comic["title_comics"]; ?>">
                    <h4 class="font-semibold"><?php echo $comic["title_comics"]; ?></h4>
                  </a>
                  <div class="text-yellow-400"><?php echo $comic["notation"]; ?> 👍</div>
                </div>
              </div>
            <?php 
              $compt++;
            endforeach;
            ?>
          </div>
        </div>
      </div>
    </section>

    <!-- All Comics Section -->
    <section class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6">
      <?php foreach ($recentComics as $comic): ?>
        <div class="comic-container bg-gray-900 rounded-lg shadow-lg flex overflow-hidden h-64">
          <div class="w-2/5">
            <img src="<?php echo $comic["image_path"]; ?>" alt="Comic Image" class="comic-image h-full w-full object-cover">
          </div>
          <div class="w-3/5 p-6 flex flex-col justify-between">
            <div>
              <a href="comics_page.php?title=<?php echo $comic["title_comics"]; ?>">
                <h3 class="text-xl font-bold text-white mb-3"><?php echo $comic["title_comics"]; ?></h3>
              </a>
              <?php if(isset($_SESSION["user"])): ?>
                <form method="POST" action="../methode/add_to_favorites.php" class="ml-4">
                  <input type="hidden" name="comic_id" value="<?php echo $comic['id_comics']; ?>">
                  <button
                    type="submit"
                    class="text-xs px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-700 transition">
                    Ajouter aux Favoris
                  </button>
                </form>
              <?php endif ?>
              <div class="flex items-center justify-between mb-4">
                <span class="px-2 py-1 bg-blue-600 text-white text-xs font-medium rounded-full"><?php echo $comic["category"]; ?></span>
                <div class="flex items-center space-x-1 text-yellow-400">
                  <span class="text-base"><?php echo $comic["notation"]; ?> 👍</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </section>
  </main>

  <!-- Footer -->
  <footer class="bg-gray-800 text-center text-gray-400 py-4">
    <p class="animate-scroll">Reda Steven Massi SaruScan</p>
  </footer>
</body>
