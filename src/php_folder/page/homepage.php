<?php
include_once("header.php");
//requete comics populaire
$comics_req = $pdo->query("SELECT 
    c.id_comics,
    c.title_comics,
    c.image_path,
    c.author,
    c.created_at,
    c.category,
    c.description,
    SUM(ch.view_count)                     AS total_views,
    COUNT(DISTINCT r.id_user)             AS notation
FROM comics c
LEFT JOIN chapter ch 
       ON c.id_comics = ch.id_comics
LEFT JOIN rate r 
       ON c.id_comics = r.id_comics
GROUP BY 
    c.id_comics,
    c.title_comics,
    c.image_path,
    c.author,
    c.created_at,
    c.category,
    c.description
ORDER BY total_views DESC;");
$comics = $comics_req->fetchAll();

//requete liste des comics
$liste_comics_req = $pdo->query("SELECT 
    comics.id_comics, 
    comics.title_comics, 
    comics.image_path, 
    comics.author, 
    comics.created_at, 
    comics.category, 
    comics.description, 
    SUM(chapter.view_count) AS total_views,
    COUNT(DISTINCT rate.id_user) AS notation
FROM comics
LEFT JOIN chapter 
       ON comics.id_comics = chapter.id_comics
LEFT JOIN rate 
       ON comics.id_comics = rate.id_comics
GROUP BY comics.id_comics
ORDER BY comics.created_at DESC;
");

$liste_comics = $liste_comics_req->fetchall();
//requete carousselle 
$best_comics_req = $pdo->query("SELECT 
    c.*,
    COUNT(DISTINCT r.id_user) AS notation
FROM comics c
LEFT JOIN rate r ON c.id_comics = r.id_comics
WHERE c.id_comics = 2
GROUP BY c.id_comics;
");
$best_comics = $best_comics_req->fetch();
?>

<body class="bg-gray-900 text-gray-300">

  <!-- Main Content -->
  <main class="container mx-auto px-4 py-8">

    <!-- Recommendations and Popular Section -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-8">

      <!-- Recommendations (Carousel) -->
      <div id="carousel" class="relative bg-gray-800 rounded-lg shadow-lg overflow-hidden w-full mx-auto h-auto">
      <?php foreach ($comics as $index => $comic): ?>
      <div class="carousel-item <?php echo $index === 0 ? 'block' : 'hidden'; ?> absolute inset-0">
      <!-- Image Section -->
      
        <div class="w-full h-4/5">
          <img src="<?php echo $comic['image_path']; ?>" alt="Comic Image" 
               class="w-full h-full object-cover aspect-square">
        </div>

      <!-- Text Section -->
      <div class="p-4 h-1/5 flex flex-col justify-center">
        <div class="flex items-center justify-between text-yellow-400">
          <span><?php echo $comic["notation"] ?>👍</span>
        </div>
        <h3 class="text-lg font-bold mt-2 text-center"><?php echo $comic['title_comics']; ?></h3>
        <p class="text-sm text-gray-400 text-center"><?php echo $comic['description']; ?></p>
      </div>
    </div>
  <?php endforeach; ?>
</div>

      <script>
        const items = document.querySelectorAll('.carousel-item');
        let currentIndex = 0;

        const showItem = (index) => {
          items.forEach((item, i) => {
            item.classList.toggle('block', i === index);
            item.classList.toggle('hidden', i !== index);
          });
        };

        // Automatic carousel
        setInterval(() => {
          currentIndex = (currentIndex + 1) % items.length;
          showItem(currentIndex);
        }, 5000); // Change every 5 seconds
      </script>

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
            $limitedComics = array_slice($comics, 0, 10); // Prendre seulement les 10 premiers
            $compt = 1;
            foreach($limitedComics as $comic){
              echo "<div class=\"flex items-center space-x-4\">
          <span class=\"text-gray-400\">#". $compt ."</span>
          <img src=". $comic["image_path"] ." alt=\"Comic\" class=\"w-12 h-12 rounded\">
          <div>
            <a href=\"comics_page.php?title=". $comic["title_comics"] ."\"><h4 class=\"font-semibold\">". $comic["title_comics"]."</h4></a>
            <div class=\"text-yellow-400\">".$comic["notation"]."👍</div>
          </div>
        </div>";
        $compt += 1;
              
            }
            ?>
          </div>
        </div>
      </div>
    </section>

    <!-- All Comics Section -->
    <section class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6">
      
      <?php foreach ($liste_comics as $comic): ?>
        <div class="comic-container bg-gray-900 rounded-lg shadow-lg flex overflow-hidden h-64">
          <div class="w-2/5">
            <img src="<?php echo $comic["image_path"] ?>" alt="Comic Image" class="comic-image h-full w-full object-cover">
          </div>

          <div class="w-3/5 p-6 flex flex-col justify-between">
            <div>
            <a href="comics_page.php?title=<?php echo $comic["title_comics"] ?>"><h3 class="text-xl font-bold text-white mb-3"> <?php echo $comic["title_comics"] ?></h3></a>
            <form method="POST" action="../methode/add_to_favorites.php" class="ml-4">
                  <input type="hidden" name="comic_id" value="<?php echo $comic['id_comics']; ?>">
                  <button
                    type="submit"
                    class="text-xs px-2 py-1 bg-blue-500 text-white rounded hover:bg-blue-700 transition">
                    Ajouter aux Favoris
                  </button>
              </form>  
            <div class="flex items-center justify-between mb-4">
                <span class="px-2 py-1 bg-blue-600 text-white text-xs font-medium rounded-full">Manga</span>
                <div class="flex items-center space-x-1 text-yellow-400">
                  <span class="text-base"><?php echo $comic["notation"] ?>👍</span>
                </div>
              </div>
            </div>
            <?php
            $chapter_list = $pdo->prepare("SELECT comics_path, id_comics, title_chapter, created_at FROM chapter WHERE id_comics = :i");
            $chapter_list->execute([':i' => $comic["id_comics"]]);
            ?>
            <div class="chapter-list">
              <?php while($chapter = $chapter_list->fetch()):?>
                <div class="chapter-item flex justify-between items-center px-3 py-2 hover:bg-gray-800 transition duration-300 border-b border-gray-700 last:border-b-0">
                  <span class="font-semibold text-white"><?php echo $chapter["title_chapter"] ?></span>
                  <span class="text-gray-400 text-sm"><?php echo $chapter["created_at"] ?></span>
                </div>
              <?php endwhile; ?>
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

