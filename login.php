<?php
session_start();
require_once 'config/db.php';

$erreur = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    $utilisateur = $stmt->fetch();

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
    <title>Login - Dechouke Grès Fitness</title>
   <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body {
            background: linear-gradient(-45deg, #0f172a, #1e293b, #14b8a6, #0f172a);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            min-height: 100vh;
        }
        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        /* Effet Glassmorphism */
        .login-card {
            background: rgba(255, 255, 255, 0.05); /* Très transparent */
            backdrop-filter: blur(15px); /* Flou d'arrière-plan */
            -webkit-backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2); /* Bordure brillante légère */
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        .neon-input {
            background: rgba(255, 255, 255, 0.9) !important;
            transition: all 0.3s ease;
        }
        .neon-input:focus {
            box-shadow: 0 0 15px rgba(20, 184, 166, 0.4);
            transform: translateY(-2px);
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">

    <div class="flex w-full max-w-5xl h-[600px] shadow-2xl rounded-[40px] overflow-hidden login-card animate__animated animate__zoomIn">
        
        <div class="w-full md:w-1/2 p-12 flex flex-col justify-center">
            <h1 class="text-5xl font-bold text-white mb-10 animate__animated animate__fadeInDown">Se connecter!</h1>
            
            <?php if($erreur): ?>
                <div class="bg-red-900/40 text-red-400 p-4 rounded-2xl mb-6 text-sm border border-red-500/50 flex items-center animate__animated animate__shakeX">
                    <span class="mr-2"><img src="assets/images/warning.png" alt="Error" class="w-6 h-6"></span> <?= $erreur ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-6">
                <div class="animate__animated animate__fadeInLeft" style="animation-delay: 0.2s;">
                    <input type="email" name="email" placeholder="Email" 
                           class="w-full p-5 rounded-full neon-input outline-none font-semibold text-slate-900" required>
                </div>
                <div class="animate__animated animate__fadeInLeft" style="animation-delay: 0.4s;">
                    <input type="password" name="mot_de_passe" placeholder="Password" 
                           class="w-full p-5 rounded-full neon-input outline-none font-semibold text-slate-900" required>
                </div>
                
                <button type="submit" class="w-full bg-[#14b8a6] text-slate-900 font-black text-xl py-5 rounded-full transition-all duration-300 hover:tracking-widest hover:bg-teal-300 active:scale-95 shadow-[0_0_20px_rgba(20,184,166,0.4)] animate__animated animate__fadeInUp">
                    CONNECTER
                </button>
            </form>

            <div class="mt-8 text-right text-sm text-gray-400 animate__animated animate__fadeIn">
                <a href="forgot_password.php" class="text-teal-400 font-bold hover:underline transition-colors hover:text-teal-200">Mot de passe oublié?</a>
            </div>
        </div>

        <div class="hidden md:flex w-1/2 flex-col justify-center items-center text-center p-12 relative bg-gradient-to-br from-white/5 to-transparent">
            <h2 class="text-6xl font-black text-white mb-4 tracking-tighter animate__animated animate__fadeInRight">Administrateur(s)</h2>
            <p class="text-teal-100/60 italic text-lg mb-10 animate__animated animate__fadeInRight" style="animation-delay: 0.5s;">Welcome back, Augustin & Celestin!</p>
            
            <img src="assets/images/wel_guy.png" 
                 alt="Coach" class="w-64 h-auto drop-shadow-[0_0_30px_rgba(20,184,166,0.3)] animate__animated animate__pulse animate__infinite animate__slow">
        </div>
    </div>

</body>
</html>