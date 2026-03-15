<?php 
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['utilisateur_id'])) {
    header("Location: login.php");
    exit();
}

require_once 'config/db.php'; 

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) {
    header("Location: membres.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM membres WHERE id = ?");
$stmt->execute([$id]);
$m = $stmt->fetch();

if (!$m) {
    die("Erreur : Ce membre n'existe pas.");
}

include 'includes/header.php'; 
?>

<style>
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

    /* Ajustement pour petit écran */
    @media (max-width: 640px) {
        .card-container { padding: 1.5rem !important; border-radius: 35px !important; }
        .qr-box { padding: 1.5rem !important; }
        .member-name { font-size: 1.875rem !important; }
    }

    /* Style spécifique pour l'impression */
    @media print {
        .no-print, nav, aside { display: none !important; }
        body, main { background: white !important; margin: 0 !important; padding: 0 !important; }
        .card-container { 
            box-shadow: none !important; 
            border: 2px solid #000 !important; 
            margin: auto !important;
            width: 80mm !important; /* Taille standard carte de visite environ */
        }
    }
</style>

<div class="flex flex-col items-center justify-center min-h-[80vh] p-4 md:p-6">
    <div class="card-container bg-white p-8 md:p-12 rounded-[50px] shadow-2xl border border-slate-100 w-full max-w-sm text-center">
        
        <div class="mb-6">
            <h2 class="member-name text-3xl md:text-4xl font-black text-slate-800 uppercase leading-tight">
                <?= htmlspecialchars($m['nom']) ?>
            </h2>
            <p class="text-teal-500 font-bold text-lg md:text-xl mt-1">
                <?= htmlspecialchars($m['prenom']) ?>
            </p>
        </div>

        <div class="qr-box bg-slate-50 p-6 md:p-8 rounded-[40px] border-2 border-dashed border-slate-200 mb-6 inline-block">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data=<?= $m['id'] ?>" 
                 alt="QR Code Membre" 
                 class="mx-auto w-[150px] h-[150px] md:w-[180px] md:h-[180px]">
        </div>

        <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mb-8">
            ID : #<?= $m['id'] ?> | SCANNEZ À L'ENTRÉE
        </p>

        <div class="space-y-4 no-print">
            <button onclick="window.print()" class="w-full bg-teal-500 hover:bg-teal-400 text-slate-900 font-black py-4 rounded-2xl transition shadow-lg flex items-center justify-center gap-2">
                <span>🖨️</span> IMPRIMER LA CARTE
            </button>
            <a href="membres.php" class="block text-slate-400 font-bold hover:text-slate-600 transition text-xs uppercase tracking-widest">
                ← Retour
            </a>
        </div>
    </div>
</div>

</main> </body>
</html>