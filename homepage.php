<?php
session_start();
include_once("./header.php");
?>


<body class="body_container">

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
      <label for="research"></label>
      <input type="text" name="research" id="research" placeholder="Search Comics...">
    </form>
  </div>

  <!-- Main -->
  <main class="main_container">

    <!-- Section Recommendations and Popular -->
    <section class="reco-populaire_container">
      <div class="reco-popular-left">

        <!-- Carousel for Recommendations -->
        <div class="recommendation_carousel">
          <img src="source" alt="Comic Image">
          <div class="comic_info">
            <div class="comic_rating">
              <span class="stars">⭐⭐⭐⭐⭐</span>
              <span class="rating_value">9.9</span>
            </div>
            <div class="comic_title">
              <h3>Manhwa</h3>
              <p>Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
            </div>
          </div>
        </div>

        <!-- Carousel Navigation (Left and Right buttons) -->
        <div class="carousel_nav">
          <button class="carousel_left">←</button>
          <button class="carousel_right">→</button>
        </div>
      </div>

      <div class="reco-popular-right">

        <!-- Popular Comics -->
        <div class="popular_comics">
          <div class="popular_nav">
            <button class="weekly">Weekly</button>
            <button class="monthly">Monthly</button>
            <button class="all">All</button>
          </div>

          <!-- Comics Grid -->
          <div class="comics_grid">
            <div class="comic_item">
              <span class="rank">#1</span>
              <img src="source" alt="Comic Image">
              <div class="comic_details">
                <h4>Manga's Name</h4>
                <div class="comic_rating">
                  <span class="stars">⭐⭐⭐⭐⭐</span>
                  <span class="rating_value">9.9</span>
                </div>
              </div>
            </div>

            <div class="comic_item">
              <span class="rank">#2</span>
              <img src="source" alt="Comic Image">
              <div class="comic_details">
                <h4>Manga's Name 2</h4>
                <div class="comic_rating">
                  <span class="stars">⭐⭐⭐⭐⭐</span>
                  <span class="rating_value">9.9</span>
                </div>
              </div>
            </div>

            <div class="comic_item">
              <span class="rank">#3</span>
              <img src="source" alt="Comic Image">
              <div class="comic_details">
                <h4>Manga's Name 3</h4>
                <div class="comic_rating">
                  <span class="stars">⭐⭐⭐⭐⭐</span>
                  <span class="rating_value">9.9</span>
                </div>
              </div>
            </div>
            <!-- à rajouter -->
          </div>
        </div>
      </div>
    </section>

    <!-- All Comics -->
    <section class="all_comics_container">
      <div class="comics_grid">
        <div class="comic_item">
          <img src="source" alt="Comic Image">
          <div class="comic_info">
            <h3>Swordmaster's Youngest Son</h3>
            <span class="comic_type">Manhwa</span>
            <div class="comic_rating">
              <span class="stars">⭐⭐⭐⭐⭐</span>
              <span class="rating_value">9.9</span>
            </div>
            <div class="comic_chapters">
              <p>Chapter 133 - 23 hours ago</p>
              <p>Chapter 132 - 2 days ago</p>
              <p>Chapter 131 - 3 days ago</p>
            </div>
          </div>
        </div>

        <div class="comic_item">
          <img src="source" alt="Comic Image">
          <div class="comic_info">
            <h3>Chainsaw Man</h3>
            <span class="comic_type">Manhwa</span>
            <div class="comic_rating">
              <span class="stars">⭐⭐⭐⭐⭐</span>
              <span class="rating_value">9.9</span>
            </div>
            <div class="comic_chapters">
              <p>Chapter 133 - 23 hours ago</p>
              <p>Chapter 132 - 2 days ago</p>
              <p>Chapter 131 - 3 days ago</p>
            </div>
          </div>
        </div>

        <div class="comic_item">
          <img src="source" alt="Comic Image">
          <div class="comic_info">
            <h3>Naruto</h3>
            <span class="comic_type">Manhwa</span>
            <div class="comic_rating">
              <span class="stars">⭐⭐⭐⭐⭐</span>
              <span class="rating_value">9.9</span>
            </div>
            <div class="comic_chapters">
              <p>Chapter 133 - 23 hours ago</p>
              <p>Chapter 132 - 2 days ago</p>
              <p>Chapter 131 - 3 days ago</p>
            </div>
          </div>
        </div>

        <div class="comic_item">
          <img src="source" alt="Comic Image">
          <div class="comic_info">
            <h3>Dbz</h3>
            <span class="comic_type">Manhwa</span>
            <div class="comic_rating">
              <span class="stars">⭐⭐⭐⭐⭐</span>
              <span class="rating_value">9.9</span>
            </div>
            <div class="comic_chapters">
              <p>Chapter 133 - 23 hours ago</p>
              <p>Chapter 132 - 2 days ago</p>
              <p>Chapter 131 - 3 days ago</p>
            </div>
          </div>
        </div>

        <!-- à rajouter -->
      </div>
    </section>

  </main>

  <!-- Footer -->
  <footer>
    <div class="footer_animation">
      <p>Animation de nos noms de gauche à droite en mode add</p>
    </div>
  </footer>

</body>

</html>