<?php
session_start();
require_once 'config/db.php';

$erreur = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    // On cherche l'utilisateur par email
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $utilisateur = $stmt->fetch();

    // On vérifie le mot de passe haché
    if ($utilisateur && password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
        $_SESSION['utilisateur_id'] = $utilisateur['id'];
        $_SESSION['utilisateur_nom'] = $utilisateur['nom'];
        $_SESSION['utilisateur_role'] = $utilisateur['role'];
        
        header("Location: index.php");
        exit();
    } else {
        $erreur = "Identifiants invalides.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Login - NeuroCode Gym</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 50%, #14b8a6 50%);
            min-height: 100vh;
        }
        .login-card {
            background: rgba(15, 23, 42, 0.95);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .neon-input {
            background: #eef4ff !important; /* Couleur claire comme sur ton image */
            border: none;
            color: #1e293b;
        }
        .btn-teal {
            background-color: #14b8a6;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">

    <div class="flex w-full max-w-5xl h-[600px] shadow-2xl rounded-[40px] overflow-hidden login-card">
        
        <div class="w-full md:w-1/2 p-12 flex flex-col justify-center">
            <h1 class="text-5xl font-bold text-white mb-10">Login</h1>
            
            <?php if($erreur): ?>
                <div class="bg-red-900/40 text-red-400 p-4 rounded-2xl mb-6 text-sm border border-red-500/50 flex items-center">
                    <span class="mr-2">⚠️</span> <?= $erreur ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div>
                    <input type="email" name="email" placeholder="Email" 
                           class="w-full p-5 rounded-full neon-input outline-none font-semibold" required>
                </div>
                <div>
                    <input type="password" name="mot_de_passe" placeholder="Password" 
                           class="w-full p-5 rounded-full neon-input outline-none font-semibold" required>
                </div>
                <button type="submit" class="w-full btn-teal text-slate-900 font-black text-xl py-5 rounded-full transition transform hover:scale-105 shadow-[0_0_20px_rgba(20,184,166,0.4)]">
                    Login
                </button>
            </form>

            <div class="mt-8 text-center text-sm text-gray-400">
                Don't have an account? <a href="creer_compte.php" class="text-teal-400 font-bold hover:underline">Sign Up</a>
            </div>
        </div>

        <div class="hidden md:flex w-1/2 flex-col justify-center items-center text-center p-12 relative">
            <h2 class="text-6xl font-black text-white mb-4 tracking-tighter">WELCOME BACK!</h2>
            <p class="text-teal-100/60 italic text-lg mb-10">Hope, You and your Family have a Great Day</p>
            
            <img src="assets/images/wel_guy.png" 
                 alt="Coach" class="w-64 h-auto drop-shadow-[0_0_30px_rgba(20,184,166,0.3)]">
        </div>
    </div>

</body>
</html>