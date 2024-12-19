<?php
require_once 'header.php';
require_once '../class/comics.php';

$category_req = $pdo->query("SELECT DISTINCT category from comics");
$choice_category = $_GET["category"];
if( $choice_category != null){
    $choice_req = $pdo->prepare("SELECT * from comics WHERE category = :c");
    $choice_req->execute([':c' => $_GET["category"]]);
}
else{
    $choice_req = $pdo->query("SELECT * from comics");
}
$choice = $choice_req->fetchall();
echo "<div>";
while($category = $category_req->fetch()){
    echo "<a href=\"category_page.php?category=". $category["category"] . "\">" . $category["category"] . "</a> ";
}
echo "</div>";

?>

<?php foreach ($choice as $comic): ?>
        <div class="comic-container bg-gray-900 rounded-lg shadow-lg flex overflow-hidden h-64">
          <div class="w-2/5">
            <img src="<?php echo $comic["image_path"] ?>" alt="Comic Image" class="comic-image h-full w-full object-cover">
          </div>

          <div class="w-3/5 p-6 flex flex-col justify-between">
            <div>
              <a href="comics_page.php?title=<?php echo $comic["title_comics"] ?>"><h3 class="text-xl font-bold text-white mb-3"> <?php echo $comic["title_comics"] ?></h3></a>
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