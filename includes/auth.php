<?php
// On ne démarre la session que si elle n'est pas déjà lancée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fonction pour bloquer les non-connectés
function est_connecte() {
    if (!isset($_SESSION['utilisateur_id'])) {
        header("Location: login.php");
        exit();
    }
}

// Fonction pour bloquer ceux qui ne sont pas Admin (pour Caleb par exemple)
function est_admin() {
    est_connecte();
    if ($_SESSION['utilisateur_role'] !== 'Admin') {
        header("Location: index.php?error=access_denied");
        exit();
    }
}