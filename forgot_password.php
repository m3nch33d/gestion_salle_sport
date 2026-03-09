<?php
// Affichage des erreurs pour le développement
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'config/db.php';
require_once 'config/mail.php';

$message = "";
<<<<<<< HEAD
$type_message = "info"; // Pour changer la couleur de l'alerte
=======
$type_message = "info"; 
>>>>>>> 7e8a91057e02ca00628d58e63ac6c9f2945e29bc

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);

<<<<<<< HEAD
    // 1. Vérifier si l'utilisateur existe
=======
>>>>>>> 7e8a91057e02ca00628d58e63ac6c9f2945e29bc
    $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
<<<<<<< HEAD
        // 2. Générer le token et l'expiration (2 min)
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime('+2 minutes'));

        // 3. Sauvegarder dans la base de données
        $stmt = $pdo->prepare("UPDATE utilisateurs SET reset_token = ?, reset_expires_at = ? WHERE email = ?");
        $stmt->execute([$token, $expires, $email]);

        // 4. Créer le lien de réinitialisation
        // Remplace 192.168.x.xx par tes vrais chiffres
$ma_vrai_ip = "192.168.137.236"; 
$lien = "http://" . $ma_vrai_ip . "/gestion_salle_sport/reset_password.php?token=" . $token;

        // 5. Envoyer l'email via Resend
=======
        $token = bin2hex(random_bytes(32));
        $expires = date("Y-m-d H:i:s", strtotime('+2 minutes'));

        $stmt = $pdo->prepare("UPDATE utilisateurs SET reset_token = ?, reset_expires_at = ? WHERE email = ?");
        $stmt->execute([$token, $expires, $email]);

        $ma_vrai_ip = "192.168.137.236"; 
        $lien = "http://" . $ma_vrai_ip . "/gestion_salle_sport/reset_password.php?token=" . $token;

>>>>>>> 7e8a91057e02ca00628d58e63ac6c9f2945e29bc
        envoyerEmailRecuperation($email, $lien);
        
        $message = "📧 Un e-mail de sécurité a été envoyé à votre adresse !";
        $type_message = "success";
    } else {
        $message = "Désolé, cet e-mail n'existe pas dans notre base.";
        $type_message = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récupération - Dechouke Fitness</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<<<<<<< HEAD
</head>
<body class="bg-[#0f172a] flex items-center justify-center min-h-screen p-6 text-white">

    <div class="w-full max-w-md bg-slate-800/50 p-8 rounded-[40px] border border-slate-700 shadow-2xl animate__animated animate__zoomIn">
        <h1 class="text-3xl font-black mb-2 text-white">Oubli ? 🔐</h1>
        <p class="text-slate-400 mb-8">On t'envoie un lien magique pour revenir coacher !</p>

        <?php if($message): ?>
            <div class="p-4 mb-6 rounded-2xl border text-sm font-bold <?= $type_message == 'success' ? 'bg-teal-900/30 border-teal-500 text-teal-400' : 'bg-red-900/30 border-red-500 text-red-400' ?>">
=======
    <style>
        /* Background avec image et flou */
        body {
            position: relative;
            min-height: 100vh;
            margin: 0;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background-image: url('assets/images/acceuilgym.png');
            background-size: cover;
            background-position: center;
            filter: blur(8px); /* Léger effet blur */
            transform: scale(1.1); /* Pour éviter les bords blancs du flou */
            z-index: -2;
        }

        /* Overlay sombre pour la lisibilité */
        body::after {
            content: "";
            position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(15, 23, 42, 0.6);
            z-index: -1;
        }

        /* Effet Glassmorphism sur la carte */
        .glass-card {
            background: rgba(255, 255, 255, 0.05) !important;
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.15) !important;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .glass-input {
            background: rgba(15, 23, 42, 0.8) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
        }
    </style>
</head>
<body class="p-6 text-white">

    <div class="w-full max-w-md p-8 rounded-[40px] glass-card animate__animated animate__zoomIn">
        <h1 class="text-3xl font-black mb-2 text-white">Mdp oublié ? <img src="assets/images/glasskey.png" alt="Key" class="w-14 h-10 inline-block ml-2"></h1>
        <p class="text-slate-300 mb-8">On t'envoie un lien magique pour revenir te connecter!</p>

        <?php if($message): ?>
            <div class="p-4 mb-6 rounded-2xl border text-sm font-bold <?= $type_message == 'success' ? 'bg-teal-900/40 border-teal-500 text-teal-400' : 'bg-red-900/40 border-red-500 text-red-400' ?>">
>>>>>>> 7e8a91057e02ca00628d58e63ac6c9f2945e29bc
                <?= $message ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4">
            <div>
<<<<<<< HEAD
                <label class="text-xs uppercase tracking-widest text-slate-500 ml-4 font-bold">Ton Email Admin</label>
                <input type="email" name="email" required placeholder="exemple@gmail.com"
                       class="w-full mt-2 p-4 rounded-full bg-slate-900 border border-slate-700 text-white outline-none focus:border-teal-500 transition-all">
=======
                <label class="text-xs uppercase tracking-widest text-teal-400 ml-4 font-bold">Ton Email Admin</label>
                <input type="email" name="email" required placeholder="exemple@gmail.com"
                       class="w-full mt-2 p-4 rounded-full glass-input text-white outline-none focus:border-teal-500 transition-all">
>>>>>>> 7e8a91057e02ca00628d58e63ac6c9f2945e29bc
            </div>

            <button type="submit" class="w-full bg-teal-500 text-slate-900 font-black py-4 rounded-full hover:bg-teal-400 hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-teal-500/20">
                ENVOYER LE LIEN
            </button>
        </form>

        <div class="mt-8 text-center">
<<<<<<< HEAD
            <a href="login.php" class="text-slate-500 hover:text-white text-sm transition-colors">Retour à la connexion</a>
=======
            <a href="login.php" class="text-slate-400 hover:text-white text-sm transition-colors font-medium">Retour à la connexion</a>
>>>>>>> 7e8a91057e02ca00628d58e63ac6c9f2945e29bc
        </div>
    </div>

</body>
</html>