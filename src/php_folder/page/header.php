<?php
session_start();

require_once '../methode/db.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../style.css">
    <link rel="stylesheet" href="../../input.css">
    <title>SaruScan</title>
</head>

<body class="bg-gray-900 text-gray-300">

    <header class="bg-gray-800 text-white shadow-lg">
        <div class="container mx-auto px-4 py-3">
            <nav class="flex justify-between items-center">
                <!-- Left - Navigation Links -->
                <div class="flex space-x-4">
                    <a href="homepage.php" class="hover:text-blue-400 transition">Home</a>
                    <a href="comics.php" class="hover:text-blue-400 transition">Comics</a>
                    <a href="category_page.php?category=" class="hover:text-blue-400 transition">Category</a>
                    <a href="favorites.php" class="hover:text-blue-400 transition">Favorite</a>
                </div>

                <!-- Right - User Options -->
                <div class="space-x-4">
                    <?php if (isset($_SESSION['user'])): ?>
                        <a href="profilepage.php" class="hover:text-blue-400 transition">
                            Bienvenue, <strong><?php echo htmlspecialchars($_SESSION['user']['username']); ?></strong>
                        </a>
                        <a href="../methode/logout.php" class="px-4 py-2 bg-red-600 hover:bg-red-500 rounded text-sm">Log
                            out</a>
                    <?php else: ?>
                        <a href="login.php" class="px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded text-sm">Sign in</a>
                    <?php endif; ?>
                </div>

    </header>

    <!-- Search Bar -->
    <div class="bg-gray-700 py-4">
        <div class="container mx-auto">
            <form action="" method="get" class="flex justify-center">
                <input type="text" name="research" id="research"
                    class="w-1/2 px-4 py-2 rounded bg-gray-800 border border-gray-600 focus:outline-none focus:border-blue-400 text-gray-200 placeholder-gray-400"
                    placeholder="Search Comics...">
                <button type="submit"
                    class="ml-2 px-4 py-2 bg-blue-600 hover:bg-blue-500 rounded text-white">Search</button>
            </form>
        </div>
    </div>

    <?php
    if (isset($_GET["research"]) && basename($_SERVER['PHP_SELF']) != 'comics_research.php') {
        header("Location: comics_research.php?research=" . urlencode($_GET["research"]));
        exit();
    }
    ?>