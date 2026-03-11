<?php
session_start();

// Rediriger si l'utilisateur n'est pas connecté du tout
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès Restreint - Dechouke Grès</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body {
            /* Utilisation de ton image background.png */
            background-image: url('assets/images/background.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
        }
        /* Style Glassmorphism pour le panneau central */
        .glass-panel {
            background: rgba(15, 23, 42, 0.65); /* Fond sombre semi-transparent */
            backdrop-filter: blur(20px) saturate(180%);
            -webkit-backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
        }
    </style>
</head>
<body class="p-4 md:p-8">
    
    <div class="glass-panel max-w-xl w-full p-8 md:p-12 rounded-[30px] md:rounded-[40px] text-center animate__animated animate__zoomIn Up">
        
        <div class="mb-10 flex justify-center">
            <div class="relative">
                <div class="absolute inset-0 bg-red-600 rounded-full blur-2xl opacity-20 scale-150"></div>
                
                <img src="assets/images/denied.png" alt="Accès Refusé" class="relative w-50 h-10 md:w-10 md:h-32 object-contain animate__animated animate__pulse animate__infinite animate__slow">
            </div>
        </div>
        
        <h1 class="text-3xl md:text-4xl font-black text-white mb-5 uppercase tracking-tighter leading-none">
            Zone <span class="text-red-500">Restreinte</span>
        </h1>
        
        <p class="text-slate-300 text-base md:text-lg mb-10 leading-relaxed max-w-md mx-auto">
            Désolé, <span class="text-teal-400 font-bold"><?= htmlspecialchars($_SESSION['utilisateur_nom'] ?? 'Utilisateur') ?></span>.<br>
            Votre niveau d'accès actuel (<span class="text-slate-100 font-medium"><?= htmlspecialchars(ucfirst($_SESSION['utilisateur_role'] ?? 'employé')) ?></span>) ne vous permet pas de consulter cette page du groupe B.
        </p>

        <a href="index.php" class="inline-flex items-center gap-2 bg-teal-500 hover:bg-teal-400 text-slate-950 font-bold py-3.5 px-3 rounded-full transition-all duration-300 transform hover:scale-105 active:scale-95 shadow-lg shadow-teal-500/20 text-sm md:text-base">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z" />
            </svg>
            Retour au Tableau de Bord
        </a>
        
    </div>

</body>
</html>