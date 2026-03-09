<?php 
// FORCER LA LECTURE DE LA SESSION AVANT LE HEADER
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Vérification manuelle rapide pour le débogage
if (!isset($_SESSION['utilisateur_id'])) {
    // Si on arrive ici, c'est que la session est vide
    header("Location: login.php");
    exit();
}

require_once 'config/db.php'; 

// Récupération sécurisée de l'ID
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: membres.php");
    exit();
}

// Récupérer les infos du membre
$stmt = $pdo->prepare("SELECT * FROM membres WHERE id = ?");
$stmt->execute([$id]);
$m = $stmt->fetch();

if (!$m) {
    die("Erreur : Ce membre n'existe pas dans la base de données.");
}

// Maintenant on inclut le header pour le design (Sidebar, etc.)
include 'includes/header.php'; 
?>


<style>
    /* 1. Fond Immersif identique aux autres pages */
    body {
        margin: 0;
        background: url('/gestion_salle_sport/assets/images/gymgris.jpeg') no-repeat center center fixed;
        background-size: cover;
    }

    body::before {
        content: "";
        position: fixed;
        inset: 0;
        background: radial-gradient(circle at center, rgba(15, 23, 42, 0.4) 0%, rgba(2, 6, 23, 0.9) 100%);
        z-index: -1;
    }
</style>

<div class="flex flex-col items-center justify-center p-6">
    <div class="bg-white p-10 rounded-[50px] shadow-2xl border border-slate-100 w-full max-w-md text-center">
        
        <div class="mb-6">
            <h2 class="text-4xl font-black text-slate-800 uppercase leading-none">
                <?= htmlspecialchars($m['nom']) ?>
            </h2>
            <p class="text-teal-500 font-bold text-xl mt-2">
                <?= htmlspecialchars($m['prenom']) ?>
            </p>
        </div>

        <div class="bg-slate-50 p-8 rounded-[40px] border-2 border-dashed border-slate-200 mb-8 inline-block">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=<?= $m['id'] ?>" 
                 alt="QR Code Membre" 
                 style="width: 180px; height: 180px;"
                 class="mx-auto">
        </div>

        <p class="text-slate-400 text-xs font-black uppercase tracking-[0.2em] mb-10">
            ID MEMBRE : #<?= $m['id'] ?> | SCANNEZ À L'ENTRÉE
        </p>

        <div class="space-y-4 no-print">
            <button onclick="window.print()" class="w-full bg-teal-500 hover:bg-teal-400 text-slate-900 font-black py-5 rounded-2xl transition transform hover:scale-105 shadow-lg shadow-teal-500/20">
                🖨️ IMPRIMER LA CARTE
            </button>
            <a href="membres.php" class="block text-slate-400 font-bold hover:text-slate-600 transition text-sm">
                ← Retour à la liste
            </a>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print, aside, .sidebar-link { display: none !important; }
        main { margin-left: 0 !important; padding: 0 !important; }
        body { background: white !important; }
        .shadow-2xl { border: 1px solid #eee !important; box-shadow: none !important; }
    </div>
</style>

</main> </body>
</html>