<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Protection globale : si pas connecté, retour au login
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
    <title>Dechouke Grès Fitness - Admin</title>
    <link rel="stylesheet" href="public/css/style.css">
    <!-- <script src="https://cdn.tailwindcss.com"></script> -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        .sidebar-link:hover { background-color: rgba(20, 184, 166, 0.1); color: #14b8a6; }
        .sidebar-active { background-color: #14b8a6; color: white !important; shadow: 0 4px 14px 0 rgba(20, 184, 166, 0.39); }
    </style>
</head>
<body class="flex min-h-screen">

    <aside class="w-64 bg-slate-900 text-slate-300 flex flex-col fixed h-full z-50">
        <div class="p-6 text-center">
            <h1 class="text-2xl font-black text-teal-400 italic tracking-tighter">Dechouke Grès Fitness</h1>
            <p class="text-[10px] text-slate-500 uppercase tracking-widest mt-1">Management System</p>
        </div>

        <nav class="flex-1 px-4 space-y-2 mt-4">
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
    <a href="seances/ajouter.php" class="flex items-center gap-3 p-3 rounded-xl hover:bg-emerald-500/20 transition-all group">
    <div class="bg-emerald-500/10 p-2 rounded-lg group-hover:bg-emerald-500 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500 group-hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
    </div>
    <span class="text-gray-300 font-medium group-hover:text-white">Programmer Séance</span>
</a>
    <hr class="border-slate-800 my-4">
    <a href="scanner.php" class="sidebar-link flex items-center p-3 rounded-xl transition font-semibold <?= basename($_SERVER['PHP_SELF']) == 'scanner.php' ? 'sidebar-active' : '' ?>">
        <span class="mr-3"><img src="assets/images/security.png" class="w-5 h-5"></span> Scanner Entrée
    </a>
    <a href="rapports.php" class="sidebar-link flex items-center p-3 rounded-xl transition font-semibold <?= basename($_SERVER['PHP_SELF']) == 'rapports.php' ? 'sidebar-active' : '' ?>">
        <span class="mr-3"><img src="assets/images/bagmoney.png" class="w-5 h-5"></span> Rapports Financiers
    </a>
</nav>

        <div class="p-4 border-t border-slate-800">
            <div class="flex items-center p-2 mb-4">
                <div class="w-8 h-8 bg-teal-500 rounded-full flex items-center justify-center text-slate-900 font-bold mr-3">
                    <?= strtoupper(substr($_SESSION['utilisateur_nom'], 0, 1)) ?>
                </div>
                <div class="overflow-hidden">
                    <p class="text-sm font-bold text-white truncate"><?= $_SESSION['utilisateur_nom'] ?></p>
                    <p class="text-[10px] text-teal-400 uppercase font-black"><?= $_SESSION['utilisateur_role'] ?></p>
                </div>
            </div>
            <a href="logout.php" class="block w-full text-center bg-slate-800 hover:bg-red-500/20 hover:text-red-400 text-xs py-2 rounded-lg transition font-bold border border-slate-700">
                Déconnexion
            </a>
        </div>
    </aside>

    <main class="flex-1 ml-64 p-8">
