<?php
include_once("header.php");
require_once("../methode/db.php");


// Vérification utilisateur connecté
if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit();
}

// Récupération des données utilisateur depuis la session
$user = $_SESSION['user'];

// Mise à jour des informations
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $errors = [];
  $success = false;

  $new_username = trim($_POST['username']);
  $new_email = trim($_POST['email']);
  $profile_picture = $_FILES['profile_picture'];

  if (empty($new_username)) {
    $errors[] = "The username cannot be empty.";
  }

  if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  if (empty($errors)) {
    try {
      $stmt = $pdo->prepare('UPDATE user SET username = :username, email = :email WHERE id = :id');
      $stmt->execute(['username' => $new_username, 'email' => $new_email, 'id' => $user['id']]);

      if (!empty($profile_picture['name'])) {
        $target_dir = "uploads/profile_pictures/";
        $target_file = $target_dir . basename($profile_picture["name"]);
        move_uploaded_file($profile_picture["tmp_name"], $target_file);

        $pdo->prepare('UPDATE user SET profile_picture = :profile_picture WHERE id = :id')
          ->execute(['profile_picture' => $target_file, 'id' => $user['id']]);

        $user['profile_picture'] = $target_file;
        $_SESSION['user']['profile_picture'] = $target_file;
      }

      $user['username'] = $new_username;
      $user['email'] = $new_email;
      $_SESSION['user'] = $user;

      $success = true;
    } catch (Exception $e) {
      $errors[] = "An error occurred: " . $e->getMessage();
    }
  }
}
?>

<body class="bg-gray-900 text-gray-300">
  <main class="container mx-auto px-4 py-8">
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-md mx-auto">
      <h1 class="text-xl font-semibold text-center mb-4">Edit Profile</h1>

      <?php if (!empty($errors)): ?>
        <div class="bg-red-500 text-white p-3 rounded mb-4">
          <?php foreach ($errors as $error): ?>
            <p><?php echo $error; ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <?php if (isset($success) && $success): ?>
        <div class="bg-green-500 text-white p-3 rounded mb-4">
          <p>Profile updated successfully!</p>
        </div>
      <?php endif; ?>

      <form action="profile.php" method="POST" enctype="multipart/form-data">
        <!-- Profile Picture -->
        <div class="mb-4 text-center">
          <img src="<?php echo isset($user['profile_picture']) ? $user['profile_picture'] : 'default-profile.png'; ?>" alt="Profile Picture" class="w-20 h-20 rounded-full mx-auto">
          <input type="file" name="profile_picture" class="mt-2 text-sm text-gray-400 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-500">
        </div>

        <!-- Username -->
        <div class="mb-4">
          <label for="username" class="block text-sm mb-1">Username</label>
          <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" class="w-full px-3 py-2 bg-gray-700 rounded text-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Email -->
        <div class="mb-4">
          <label for="email" class="block text-sm mb-1">Email</label>
          <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full px-3 py-2 bg-gray-700 rounded text-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Password -->
        <div class="mb-4">
          <label for="password" class="block mb-1">Password</label>
          <input type="password" id="password" name="password" placeholder="Enter a new password" class="w-full px-4 py-2 rounded bg-gray-700 text-gray-300 focus:outline-none focus:ring focus:ring-blue-500">
        </div>

        <!-- Favorites Link -->
        <div class="mb-4 text-center">
          <a href="favorites.php" class="text-sm text-blue-400 hover:underline">View Favorites</a>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="w-full py-2 bg-blue-600 rounded text-gray-100 hover:bg-blue-500 focus:ring-2 focus:ring-blue-500">Save Changes</button>
      </form>
    </div>
  </main>
</body>