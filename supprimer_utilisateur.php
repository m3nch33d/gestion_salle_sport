<?php
require_once 'includes/securite.php'; 
require_once 'config/db.php';

// 1. On vérifie si l'ID est bien présent dans l'URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = (int)$_GET['id']; // On force l'ID en nombre entier par sécurité

    try {
        // 2. Préparation de la requête de suppression
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id]);

        // 3. Redirection avec un petit message de succès (optionnel)
        header("Location: liste_utilisateurs.php?msg=supprime");
        exit();

    } catch (PDOException $e) {
        // En cas d'erreur (ex: clé étrangère liée), on renvoie une erreur
        header("Location: liste_utilisateurs.php?msg=erreur");
        exit();
    }
} else {
    // Si on arrive ici sans ID, on retourne à la liste
    header("Location: liste_utilisateurs.php");
    exit();
}