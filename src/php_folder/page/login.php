<?php
session_start();
require_once("../methode/db.php");


if (isset($_POST['submit'])) {
  $email = trim($_POST['email']);
  $password = $_POST['password'];

  // Initialiser les erreurs
  $errors = [];

  // Valider les champs
  if (empty($email) || empty($password)) {
    $errors[] = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  }

  if (empty($errors)) {
    // Vérifier si l'utilisateur existe
    $stmt = $pdo->prepare('SELECT * FROM user WHERE email = :email');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
      // Stocker les informations de l'utilisateur dans la session
      $_SESSION['user'] = [
        'id' => $user['id'],
        'username' => $user['username'],
        'email' => $user['email'],
        'is_admin' => $user['is_admin']
      ];
      header('Location: homepage.php'); // Rediriger vers la page d'accueil
      exit();
    } else {
      $errors[] = "Invalid email or password.";
    }
  }

  // Enregistrer les erreurs dans la session
  $_SESSION['errors'] = $errors;
  if (!empty($errors)) {
    header('Location: login.php'); // Recharger la page avec les erreurs
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <link rel="stylesheet" href="../../style.css">
  <title>Login</title>
</head>

<body class="bg-gray-900 text-gray-300 flex items-center justify-center min-h-screen">
  <?php if (isset($_SESSION['errors'])): ?>
    <div class="bg-red-500 text-white p-3 rounded mb-4">
      <?php
      foreach ($_SESSION['errors'] as $error) {
        echo "<p>$error</p>";
      }
      unset($_SESSION['errors']);
      ?>
    </div>
  <?php endif; ?>

  <div class="absolute top-5 left-5 text-gray-400">
    <nav>
      <a href="homepage.php" class="hover:text-gray-200">homepage</a> >
      <span>login</span>
    </nav>
  </div>

  <div class="bg-gray-800 p-10 rounded-lg shadow-lg w-full max-w-md">
    <div class="flex justify-center mb-6">
      <img src="" alt="Logo" class="h-16 w-16">
    </div>

    <form action="login.php" method="POST">
      <div class="mb-4">
        <label for="email" class="block mb-1">Email</label>
        <input type="email" id="email" name="email" class="w-full px-4 py-2 rounded bg-gray-700 text-gray-300 focus:outline-none focus:ring focus:ring-blue-500" required>
      </div>

      <div class="mb-4">
        <label for="password" class="block mb-1">Password</label>
        <input type="password" id="password" name="password" class="w-full px-4 py-2 rounded bg-gray-700 text-gray-300 focus:outline-none focus:ring focus:ring-blue-500" required>
      </div>

      <div class="mb-6">
        <button type="submit" name="submit" class="w-full py-2 bg-gray-700 text-gray-300 rounded hover:bg-gray-600 focus:outline-none focus:ring focus:ring-blue-500">Login</button>
      </div>

      <div class="text-center">
        <span class="text-sm">New user?</span>
        <a href="register.php" class="text-sm bg-gray-700 px-4 py-2 rounded hover:bg-gray-600 focus:outline-none focus:ring focus:ring-blue-500">Sign up</a>
      </div>
    </form>
  </div>
</body>

</html>