<?php
include_once("header.php");
$comics_req = $bdd->query("SELECT * FROM comics");
$comics = $comics_req->fetchAll();

$best_comics_req = $bdd->query("SELECT * FROM comics WHERE id_comics = 2");
$best_comics = $best_comics_req->fetch();
?>

<body class="bg-gray-900 text-gray-300">

  <!-- Main Content -->
  <main class="container mx-auto px-4 py-8">

    <!-- Recommendations and Popular Section -->
    <section class="grid grid-cols-1 md:grid-cols-2 gap-8">

      <!-- Recommendations (Carousel) -->
      <div>
        <div class="bg-gray-800 rounded shadow-lg overflow-hidden">
          <img src="<?php echo $best_comics["image_path"]?>" alt="Comic Image" class="w-full">
          <div class="p-4">
            <div class="flex items-center justify-between text-yellow-400">
              <span>⭐⭐⭐⭐⭐</span>
              <span>9.9</span>
            </div>
            <h3 class="text-xl font-bold mt-2"><?php echo $best_comics["title_comics"]?> </h3>
            <p class="text-sm text-gray-400"><?php echo $best_comics["description"]?></p>
          </div>
        </div>
        <div class="flex justify-between mt-4">
          <button class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded">←</button>
          <button class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded">→</button>
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
            foreach($comics as $comic){
              echo "<div class=\"flex items-center space-x-4\">
          <span class=\"text-gray-400\">#1</span>
          <img src=". $comic["image_path"] ." alt=\"Comic\" class=\"w-12 h-12 rounded\">
          <div>
            <h4 class=\"font-semibold\">". $comic["title_comics"]."</h4>
            <div class=\"text-yellow-400\">⭐⭐⭐⭐⭐ <span class=v\"text-sm\">9.9</span></div>
          </div>
        </div>";
              
            }
            ?>
          </div>
        </div>
      </div>
    </section>

    <!-- All Comics Section -->
    <section class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6">
      <?php foreach ($comics as $comic): ?>
        <div class="comic-container bg-gray-900 rounded-lg shadow-lg flex overflow-hidden h-64">
          <div class="w-2/5">
            <img src="<?php echo $comic["image_path"] ?>" alt="Comic Image" class="comic-image h-full w-full object-cover">
          </div>

          <div class="w-3/5 p-6 flex flex-col justify-between">
            <div>
              <h3 class="text-xl font-bold text-white mb-3"> <?php echo $comic["title_comics"] ?></h3>
              <div class="flex items-center justify-between mb-4">
                <span class="px-2 py-1 bg-blue-600 text-white text-xs font-medium rounded-full">Manhwa</span>
                <div class="flex items-center space-x-1 text-yellow-400">
                  <span class="text-base">★</span>
                  <span class="text-base">★</span>
                  <span class="text-base">★</span>
                  <span class="text-base">★</span>
                  <span class="text-base">★</span>
                  <span class="text-gray-300 text-sm">(9.9)</span>
                </div>
              </div>
            </div>

            <div class="chapter-list">
              <div class="chapter-item flex justify-between items-center px-3 py-2 hover:bg-gray-800 transition duration-300 border-b border-gray-700 last:border-b-0">
                <span class="font-semibold text-white">Chapter 133</span>
                <span class="text-gray-400 text-sm">23 hours ago</span>
              </div>
              <div class="chapter-item flex justify-between items-center px-3 py-2 hover:bg-gray-800 transition duration-300 border-b border-gray-700 last:border-b-0">
                <span class="font-semibold text-white">Chapter 132</span>
                <span class="text-gray-400 text-sm">2 days ago</span>
              </div>
              <div class="chapter-item flex justify-between items-center px-3 py-2 hover:bg-gray-800 transition duration-300">
                <span class="font-semibold text-white">Chapter 131</span>
                <span class="text-gray-400 text-sm">3 days ago</span>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </section>
  </main>

  <!-- Footer -->
  <footer class="bg-gray-800 text-center text-gray-400 py-4">
    <p class="animate-scroll">Animation de nos noms de gauche à droite en mode add</p>
  </footer>
</body>