<?php
include_once("header.php");
require_once("../methode/db.php");

// Vérification utilisateur connecté
if (!isset($_SESSION['user'])) {
  header('Location: login.php');
  exit();
}

// Vérification et initialisation de la session utilisateur
$user = $_SESSION['user'];

// Assurez-vous que 'image_path' existe dans la session
if (!isset($user['image_path'])) {
  $user['image_path'] = 'default-profile.png';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $errors = [];
  $success_messages = [];

  // Gestion de l'image
  $profile_picture = $_FILES['profile_picture'] ?? null;
  $target_file = $user['image_path']; // Valeur par défaut (image existante)

  if (!empty($profile_picture['name'])) {
    $target_dir = "uploads/";

    // Création du dossier si nécessaire
    if (!is_dir($target_dir)) {
      mkdir($target_dir, 0777, true);
    }

    $extension = strtolower(pathinfo($profile_picture["name"], PATHINFO_EXTENSION));
    $new_file_name = uniqid('profile_', true) . '.' . $extension;
    $target_file = $target_dir . $new_file_name;

    // Validation du fichier
    if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif']) && $profile_picture['size'] <= 2 * 5096 * 5096) {
      if (move_uploaded_file($profile_picture["tmp_name"], $target_file)) {
        $success_messages[] = "Votre photo de profil a été mise à jour.";
      } else {
        $errors[] = "Le téléchargement de l'image a échoué.";
      }
    } else {
      $errors[] = "Format ou taille de fichier invalide.";
    }
  }

  // Validation des autres champs
  $new_username = trim($_POST['username'] ?? $user['username']);
  $new_email = trim($_POST['email'] ?? $user['email']);
  $new_password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;

  if ($new_username !== $user['username']) {
    if (!empty($new_username)) {
      $user['username'] = $new_username;
      $success_messages[] = "Votre nom d'utilisateur a été mis à jour.";
    } else {
      $errors[] = "Le nom d'utilisateur ne peut pas être vide.";
    }
  }

  if ($new_email !== $user['email']) {
    if (filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
      $user['email'] = $new_email;
      $success_messages[] = "Votre email a été mis à jour.";
    } else {
      $errors[] = "Le format de l'adresse e-mail est invalide.";
    }
  }

  if (!empty($new_password)) {
    $success_messages[] = "Votre mot de passe a été mis à jour.";
  }

  // Mise à jour en base de données
  if (empty($errors)) {
    try {
      // Construction de la requête de base
      $update_query = "UPDATE user 
                             SET username = :username, email = :email, image_path = :image_path";

      // Ajout conditionnel du mot de passe
      $params = [
        ':username' => $user['username'],
        ':email' => $user['email'],
        ':image_path' => $target_file,
        ':id_user' => $user['id_user'], // Ajout de l'identifiant utilisateur
      ];

      if (!empty($new_password)) {
        $update_query .= ", password = :password";
        $params[':password'] = $new_password;
      }

      // Ajout de la clause WHERE
      $update_query .= " WHERE id_user = :id_user";

      // Préparation et exécution
      $stmt = $pdo->prepare($update_query);
      $stmt->execute($params);

      // Mise à jour de la session si tout fonctionne
      $_SESSION['user'] = array_merge($user, [
        'username' => $user['username'],
        'email' => $user['email'],
        'image_path' => $target_file,
      ]);

      $success_messages[] = "Vos informations ont été mises à jour dans la base de données.";
    } catch (Exception $e) {
      $errors[] = "Une erreur s'est produite : " . $e->getMessage();
    }
  }
}
?>


<body class="bg-gray-900 text-gray-300">
  <main class="container mx-auto px-4 py-8">
    <div class="bg-gray-800 p-6 rounded-lg shadow-lg max-w-md mx-auto">
      <h1 class="text-xl font-semibold text-center mb-4">Modifier le profil</h1>

      <!-- Affichage des erreurs -->
      <?php if (!empty($errors)): ?>
        <div class="bg-red-500 text-white p-3 rounded mb-4">
          <?php foreach ($errors as $error): ?>
            <p><?php echo $error; ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <!-- Message(s) de succès -->
      <?php if (!empty($success_messages)): ?>
        <div class="bg-green-500 text-white p-3 rounded mb-4">
          <?php foreach ($success_messages as $message): ?>
            <p><?php echo $message; ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form action="profilepage.php" method="POST" enctype="multipart/form-data">
        <!-- Photo de profil -->
        <div class="mb-4 text-center">
          <img src="<?php echo isset($user['image_path']) ? $user['image_path'] : 'default-profile.png'; ?>" alt="Profile Picture" class="w-20 h-20 rounded-full mx-auto">
          <input type="file" name="profile_picture" class="mt-2 text-sm text-gray-400 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-500">
        </div>

        <!-- Nom d'utilisateur -->
        <div class="mb-4">
          <label for="username" class="block text-sm">Nom d'utilisateur</label>
          <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" class="w-full px-3 py-2 bg-gray-700 rounded text-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Email -->
        <div class="mb-4">
          <label for="email" class="block text-sm">E-mail</label>
          <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="w-full px-3 py-2 bg-gray-700 rounded text-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Mot de passe -->
        <div class="mb-4">
          <label for="password" class="block text-sm">Nouveau mot de passe</label>
          <input type="password" id="password" name="password" placeholder="Entrez un nouveau mot de passe" class="w-full px-4 py-2 bg-gray-700 rounded text-gray-300 focus:ring-2 focus:ring-blue-500 focus:outline-none">
        </div>

        <!-- Bouton de soumission -->
        <div class="text-center">
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Mettre à jour le profil</button>
        </div>
      </form>
    </div>
  </main>
</body>