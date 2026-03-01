<?php
require_once 'config/db.php';

// On vide la table pour repartir sur de bonnes bases
$pdo->exec("DELETE FROM admins");

// On prépare les données
$admins = [
    ['username' => 'augustin', 'nom' => 'Augustin Carl-Menceed', 'pass' => 'admin123'],
    ['username' => 'celestin', 'nom' => 'Celestin Caleb', 'pass' => 'admin123']
];

$sql = "INSERT INTO admins (username, password, nom_complet) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);

foreach ($admins as $a) {
    // password_hash crée la version cryptée sécurisée pour la base de données
    $hash = password_hash($a['pass'], PASSWORD_DEFAULT);
    $stmt->execute([$a['username'], $hash, $a['nom']]);
    echo "Admin créé : " . $a['username'] . "<br>";
}

echo "<strong>Terminé ! Tu peux maintenant supprimer ce fichier et te connecter sur login.php.</strong>";