<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. Vérification de base : Est-ce que l'utilisateur est au moins connecté ?
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: login.php");
    exit();
}

// 2. Vérification du rôle : Est-ce que c'est un Admin (Toi ou Caleb) ?
// On utilise strtolower pour être sûr que 'admin' ou 'Admin' en BDD fonctionne.
if (strtolower($_SESSION['utilisateur_role'] ?? '') !== 'admin') {
    // Si c'est un employé (Jean/Marie), on l'envoie vers la page pro "Accès Interdit"
    header("Location: 403.php");
    exit();
}

// Si le code arrive ici, c'est que c'est un Admin. La page s'affiche normalement.
?>