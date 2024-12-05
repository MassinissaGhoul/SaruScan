<?php

require_once("db.php");
session_start();



// <!-- LOGIQUE A R2CRIRE AU PROPRE AUTRE PART -->

if (isset($_POST['submit'])) {
  $username = trim($_POST['username']);
  $email = trim($_POST['email']);
  $password = $_POST['password'];
  $conf_password = $_POST['conf_password'];


  // On initialise les erreurs ! 
  $errors = [];

  // On doit valider les inputs 
  if (empty($username) || empty($email) || empty($password) || empty($conf_password)) {
    $errors[] = "All fields are required.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Invalid email format.";
  } elseif ($password !== $conf_password) {
    $errors[] = "Passwords do not match.";
  }

  if (empty($errors)) {
    //Vérifie si l'utilisateur ou l'email est déjà dans la bdd
    $stmt = $bdd->prepare('SELECT * FROM user WHERE username = :username OR email = :email');
    $stmt->execute(['username' => $username, 'email' => $email]);

    if ($stmt->fetch()) {
      $errors[] = "Username or email is already in use.";
    } else {
      // Hash le pass
      $hashed_password = password_hash($password, PASSWORD_DEFAULT);

      // nouvel utilisateur
      $stmt = $bdd->prepare('INSERT INTO user (username, email, password, is_admin) VALUES (:username, :email, :password, :is_admin)');
      $stmt->execute([

        'username' => $username,
        'email' => $email,
        'password' => $hashed_password,
        'is_admin' => 0
      ]);

      // Registration successful
      $_SESSION['success'] = "Account created successfully! Please log in.";
      header('Location: homepage.php');
      exit();
    }
  }

  // On garde en mémoire les erreurs, et on reload le form
  $_SESSION['errors'] = $errors;
  if (!empty($errors)) {
    header('Location: register.php');
    exit();
  }
}
?>

<head>
  <link rel="stylesheet" href="../style.css">
</head>


<body class="bg-gray-900 text-gray-300 flex items-center justify-center min-h-screen">
  <?php if (isset($_POST['submit']) && isset($_SESSION['errors'])): ?>
    <div class="bg-red-500 text-white p-3 rounded mb-4">
      <?php
      foreach ($_SESSION['errors'] as $error) {
        echo "<p>$error</p>";
      }
      unset($_SESSION['errors']); // Clear les erreurs ? --> UTILISER news fonc de css ?
      ?>
    </div>
  <?php endif; ?>

  <!-- le truc du dessus -->
  <div class="absolute top-5 left-5 text-gray-400">
    <nav>
      <a href="homepage.php" class="hover:text-gray-200">homepage</a> >
      <a href="login.php" class="hover:text-gray-200">login</a> >
      <span>sign up</span>
    </nav>
  </div>

  <!-- Login Form  -->
  <div class="bg-gray-800 p-10 rounded-lg shadow-lg w-full max-w-md">
    <!-- Logo -->
    <div class="flex justify-center mb-6">
      <img src="" alt="Logo" class="h-16 w-16">
    </div>

    <!-- Form -->
    <form action="register.php" method="POST">
      <!-- Username Champ -->
      <div class="mb-4">
        <label for="username" class="block mb-1">username</label>
        <input type="text" id="username" name="username" class="w-full px-4 py-2 rounded bg-gray-700 text-gray-300 focus:outline-none focus:ring focus:ring-blue-500" required>
      </div>

      <!-- Email Champ -->
      <div class="mb-4">
        <label for="email" class="block mb-1">email</label>
        <input type="email" id="email" name="email" class="w-full px-4 py-2 rounded bg-gray-700 text-gray-300 focus:outline-none focus:ring focus:ring-blue-500" required>
      </div>

      <!-- Password Champ -->
      <div class="mb-4">
        <label for="password" class="block mb-1">password</label>
        <input type="password" id="password" name="password" class="w-full px-4 py-2 rounded bg-gray-700 text-gray-300 focus:outline-none focus:ring focus:ring-blue-500" required>
      </div>

      <div class="mb-4">
        <label for="conf_password" class="block mb-1">confirm password</label>
        <input type="password" id="conf_password" name="conf_password" class="w-full px-4 py-2 rounded bg-gray-700 text-gray-300 focus:outline-none focus:ring focus:ring-blue-500" required>
      </div>

      <!-- Remember Me Checkbox -->
      <div class="flex items-center mb-6">
        <input type="checkbox" id="remember" name="remember" class="h-4 w-4 text-blue-600 focus:ring-blue-500 bg-gray-700 border-gray-600 rounded">
        <label for="remember" class="ml-2 text-sm">remind me</label>
      </div>

      <!-- sign up button -->
      <div class="mb-6">
        <button type="submit" name="submit" class="w-full py-2 bg-gray-700 text-gray-300 rounded hover:bg-gray-600 focus:outline-none focus:ring focus:ring-blue-500">sign up</button>

      </div>

      <!-- Sign In and Forgot Password Links -->
      <div class="text-center">
        <span class="text-sm">already a user?</span>
        <div class="flex justify-center space-x-4 mt-2">
          <a href="login.php" class="text-sm bg-gray-700 px-4 py-2 rounded hover:bg-gray-600 focus:outline-none focus:ring focus:ring-blue-500">sign in</a>
          <!-- à voir pour mot de passe oublié -->
          <a href="forgot_password.php" class="text-sm bg-gray-700 px-4 py-2 rounded hover:bg-gray-600 focus:outline-none focus:ring focus:ring-blue-500">forgot password?</a>
        </div>
      </div>
    </form>
  </div>
</body>