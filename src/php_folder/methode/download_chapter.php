<?php

// Vérifier si le paramètre chapter_path est fourni
if (!isset($_GET['chapter_path']) || empty($_GET['chapter_path'])) {
    echo "<p class='text-center text-red-500'>Aucun chemin de chapitre spécifié.</p>";
    exit();
}

// Récupérer le chemin du chapitre depuis le paramètre GET
$chapter_path = $_GET['chapter_path'];

// Construire le chemin complet et sécuriser avec realpath
$base_dir = realpath("../comics"); // Le dossier racine où sont stockés les chapitres
$full_path = realpath($base_dir . "/" . $chapter_path);

// Vérifier si le fichier est dans le dossier autorisé
if ($full_path && strpos($full_path, $base_dir) === 0 && file_exists($full_path)) {
    // Créer un fichier ZIP si le chapitre est un dossier
    if (is_dir($full_path)) {
        $zip_path = tempnam(sys_get_temp_dir(), 'chapter_') . ".zip";

        // Créer une archive ZIP manuellement
        $zip = new ZipArchive();
        if ($zip->open($zip_path, ZipArchive::CREATE) === TRUE) {
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($full_path, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $relative_path = substr($file, strlen($base_dir) + 1); // Chemin relatif pour l'archive
                    $zip->addFile($file, $relative_path);
                }
            }
            $zip->close();
        } else {
            echo "<p class='text-center text-red-500'>Erreur lors de la création de l'archive ZIP.</p>";
            exit();
        }

        // Définir les en-têtes pour le téléchargement du ZIP
        header('Content-Description: File Transfer');
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . basename($chapter_path) . '.zip"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($zip_path));

        // Envoyer le fichier ZIP
        readfile($zip_path);

        // Supprimer le fichier ZIP temporaire
        unlink($zip_path);
        exit();
    } else {
        // Si c'est un fichier individuel
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($full_path) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($full_path));

        // Envoyer le fichier
        readfile($full_path);
        exit();
    }
} else {
    // Fichier non trouvé ou invalide
    echo "<p class='text-center text-red-500'>Fichier ou dossier introuvable.</p>";
    exit();
}
