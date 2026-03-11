<?php
require_once 'config/db.php';

// On génère un hash propre et valide pour 123456
$nouveau_hash = password_hash("123456", PASSWORD_DEFAULT);

// Liste complète de l'équipe (Admins + Employés)
$comptes = [
    'augustincrl123@gmail.com', // Toi (Admin)
    'caleb@fitness.ht',          // Caleb (Admin) - J'ai mis cet email par défaut
    'jean@fitness.ht',           // Jean (Employé)
    'marie@fitness.ht'           // Marie (Employé)
];

foreach ($comptes as $email) {
    $stmt = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE email = ?");
    $stmt->execute([$nouveau_hash, $email]);
    
    if ($stmt->rowCount() > 0) {
        echo "✅ Succès : Mot de passe mis à jour pour <b>$email</b><br>";
    } else {
        echo "⚠️ Attention : L'email <b>$email</b> n'existe pas dans la base (vérifie l'orthographe).<br>";
    }
}

echo "<br><b>Action terminée. Tu peux maintenant tester le login !</b>";
?>