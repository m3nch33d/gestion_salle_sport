<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Protection globale : si pas connecté, retour au login
if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: login.php");
    exit();
}

function verifierAdmin() {
    // Si l'utilisateur n'est pas connecté ou n'est pas 'Admin'
    if (!isset($_SESSION['utilisateur_role']) || $_SESSION['utilisateur_role'] !== 'admin') {
        // On le renvoie vers l'index avec un message d'erreur
        header("Location: index.php?error=acces_refuse");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dechouke Grès Fitness - Admin</title>
    <link rel="stylesheet" href="public/css/style.css">
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .sidebar-link:hover { background-color: rgba(20, 184, 166, 0.1); color: #14b8a6; }
        .sidebar-active { background-color: #14b8a6 !important; color: white !important; box-shadow: 0 4px 14px 0 rgba(20, 184, 166, 0.39); }
        
        /* Personnalisation de la barre de défilement pour un look plus pro */
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #14b8a6; }
    </style>
</head>
<body class="flex min-h-screen">

    <aside class="w-64 bg-slate-900 text-slate-400 flex flex-col fixed h-full z-50">
        <div class="p-6 text-center shrink-0">
            <h1 class="text-2xl font-black text-teal-400 italic tracking-tighter">Dechouke Grès Fitness</h1>
            <p class="text-[10px] text-slate-500 uppercase tracking-widest mt-1">Management System</p>
        </div>

        <nav class="flex-1 px-4 space-y-2 mt-4 overflow-y-auto custom-scrollbar">
            <a href="index.php" class="sidebar-link flex items-center p-3 rounded-xl transition font-semibold <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'sidebar-active' : '' ?>">
                <span class="mr-3"><img src="assets/images/dashboard.png" class="w-5 h-5"></span> Dashboard
            </a>
            <a href="membres.php" class="sidebar-link flex items-center p-3 rounded-xl transition font-semibold <?= basename($_SERVER['PHP_SELF']) == 'membres.php' ? 'sidebar-active' : '' ?>">
                <span class="mr-3"><img src="assets/images/members.png" class="w-5 h-5"></span> Membres
            </a>
            <a href="abonnements.php" class="sidebar-link flex items-center p-3 rounded-xl transition font-semibold <?= basename($_SERVER['PHP_SELF']) == 'abonnements.php' ? 'sidebar-active' : '' ?>">
                <span class="mr-3"><img src="assets/images/abonnement.png" class="w-5 h-5"></span> Abonnements
            </a>
            <a href="souscriptions.php" class="sidebar-link flex items-center p-3 rounded-xl transition font-semibold <?= basename($_SERVER['PHP_SELF']) == 'souscriptions.php' ? 'sidebar-active' : '' ?>">
                <span class="mr-3"><img src="assets/images/souscriptions.png" class="w-5 h-5"></span> Souscriptions
            </a>

            <a href="scanner.php" class="sidebar-link flex items-center p-3 rounded-xl transition font-semibold <?= basename($_SERVER['PHP_SELF']) == 'scanner.php' ? 'sidebar-active' : '' ?>">
                <span class="mr-3"><img src="assets/images/security.png" class="w-5 h-5"></span> Scanner Entrée
            </a>
            <a href="rapports.php" class="sidebar-link flex items-center p-3 rounded-xl transition font-semibold <?= basename($_SERVER['PHP_SELF']) == 'rapports.php' ? 'sidebar-active' : '' ?>">
                <span class="mr-3"><img src="assets/images/bagmoney.png" class="w-5 h-5"></span> Rapports Financiers
            </a>
        </nav>

<?php if (isset($_SESSION['utilisateur_role']) && strtolower($_SESSION['utilisateur_role']) === 'admin'): ?>
            <div class="px-4 mt-6 pt-6 border-t border-slate-800">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-4 ml-2">Administration</p>
                <a href="ajouter_utilisateur.php" class="sidebar-link flex items-center p-3 rounded-xl transition font-semibold <?= basename($_SERVER['PHP_SELF']) == 'ajouter_utilisateur.php' ? 'sidebar-active' : '' ?>">
                    <span class="mr-3"><img src="assets/images/members.png" class="w-5 h-5 opacity-70"></span> Ajouter Staff
                </a>
            </div>
        <?php endif; ?>


        <div class="p-4 border-t border-slate-800 shrink-0">
            <div class="flex items-center p-2 mb-4">
                <div class="w-8 h-8 bg-teal-500 rounded-full flex items-center justify-center text-slate-900 font-bold mr-3">
                    <?= strtoupper(substr($_SESSION['utilisateur_nom'] ?? 'A', 0, 1)) ?>
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate"><?= $_SESSION['utilisateur_nom'] ?? 'Admin' ?></p>
                    <p class="text-[10px] text-teal-400 uppercase font-black"><?= $_SESSION['utilisateur_role'] ?? 'Coach' ?></p>
                </div>
            </div>
            <a href="logout.php" class="block w-full text-center bg-slate-800 hover:bg-red-500/20 hover:text-red-400 text-xs py-2 rounded-lg transition font-bold border border-slate-700">
                Déconnexion
            </a>
        </div>
    </aside>

    <main class="flex-1 ml-64 p-8">