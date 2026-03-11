<?php
require_once 'config/db.php';

$mdp_clair = "123456";
$hash = password_hash($mdp_clair, PASSWORD_DEFAULT);

// Mise à jour de Jean, Marie ET de ton compte pour être sûr
$stmt = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE role = 'Employé' OR email = 'augustincrl123@gmail.com'");
$stmt->execute([$hash]);

echo "Mots de passe réinitialisés à : 123456";
?>