<?php
// 1. On initialise la session pour pouvoir la manipuler
session_start();

// 2. On vide toutes les variables de session en mémoire
$_SESSION = array();

// 3. On détruit physiquement le cookie de session dans le navigateur
// C'est une étape de sécurité cruciale pour éviter les vols de session
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// 4. On détruit la session sur le serveur
session_destroy();

// 5. Redirection avec un petit paramètre pour confirmer la déconnexion
header("Location: login.php?logout=success");
exit();
?>