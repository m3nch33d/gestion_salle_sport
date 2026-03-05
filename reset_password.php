<?php
require_once 'config/db.php';
$message = "";
$token_valide = false;

// 1. On vérifie d'abord si le token est présent dans l'URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // On cherche l'utilisateur qui a ce token ET on vérifie si l'heure actuelle < expiration
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE reset_token = ? AND reset_expires_at > NOW()");
    $stmt->execute([$token]);
    $user = $stmt->fetch();

    if ($user) {
        $token_valide = true;
    } else {
        $message = "Le lien est invalide ou a expiré (limite de 2 minutes dépassée).";
    }
}

// 2. Traitement du nouveau mot de passe
if ($_SERVER['REQUEST_METHOD'] == 'POST' && $token_valide) {
    $nouveau_mdp = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);
    $user_id = $user['id'];

    // Mise à jour du mot de passe ET suppression du token (sécurité maximale)
    $update = $pdo->prepare("UPDATE utilisateurs SET mot_de_passe = ?, reset_token = NULL, reset_expires_at = NULL WHERE id = ?");
    
    if ($update->execute([$nouveau_mdp, $user_id])) {
        $message = "✅ Mot de passe modifié avec succès ! <a href='login.php' class='text-teal-400 font-bold'>Se connecter</a>";
        $token_valide = false; // On cache le formulaire
    } else {
        $message = "Une erreur est survenue.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau mot de passe - Dechouke Fitness</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body { background: #0f172a; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .card { background: rgba(30, 41, 59, 0.7); backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.1); }
    </style>
</head>
<body class="p-4">
    <div class="card w-full max-w-md p-8 rounded-[30px] shadow-2xl animate__animated animate__fadeIn">
        <h2 class="text-3xl font-bold text-white mb-6">Nouveau Password</h2>
        
        <?php if($message): ?>
            <div class="p-4 mb-4 bg-slate-800 text-white rounded-xl border border-teal-500/50 text-sm">
                <?= $message ?>
            </div>
        <?php endif; ?>

        <?php if($token_valide): ?>
            <form method="POST" class="space-y-6">
                <input type="password" name="mot_de_passe" placeholder="Nouveau mot de passe" 
                       class="w-full p-4 rounded-full bg-slate-100 outline-none text-slate-900 font-semibold" required>
                
                <button type="submit" class="w-full bg-[#14b8a6] text-slate-900 font-black py-4 rounded-full hover:bg-teal-400 transition-all">
                    VALIDER LE CHANGEMENT
                </button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>