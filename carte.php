<?php
require_once 'includes/securite.php';
require_once 'config/db.php';
require 'vendor/autoload.php';

use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Writer\PngWriter;

$id = $_GET['id'] ?? null;
$membre = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM membres WHERE id = ?");
    $stmt->execute([$id]);
    $membre = $stmt->fetch();
}

if (!$membre) { die("Erreur : Membre introuvable."); }

// Génération du QR Code locale (sans internet)
$result = Builder::create()
    ->writer(new PngWriter())
    ->data("ID_MEMBRE:" . $membre['id'])
    ->encoding(new Encoding('UTF-8'))
    ->size(180)
    ->margin(10)
    ->build();
$qrCodeImage = $result->getDataUri();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            .card-container { box-shadow: none !important; border: 2px solid #14b8a6 !important; }
        }
    </style>
</head>
<body class="bg-slate-950 flex flex-col items-center justify-center min-h-screen p-4">

    <div class="card-container relative w-[450px] bg-slate-900 rounded-[40px] overflow-hidden border border-teal-500/30 shadow-2xl shadow-teal-500/10">
        
        <div class="bg-teal-500 py-6 text-center">
            <h2 class="text-slate-950 font-black uppercase tracking-[0.3em] text-sm">Dechouke Grès Fitness</h2>
        </div>

        <div class="p-10 text-center">
            <div class="w-44 h-44 mx-auto rounded-full border-4 border-[#5eead4] p-1.5 mb-6 overflow-hidden shadow-lg shadow-teal-500/20">
                <?php 
                // Mise à jour du chemin vers assets/uploads
                $photoPath = 'assets/uploads/' . $membre['photo'];
                
                if (!empty($membre['photo']) && file_exists($photoPath)): ?>
                    <img src="<?= $photoPath ?>" class="w-full h-full rounded-full object-cover">
                <?php else: ?>
                    <div class="w-full h-full rounded-full bg-slate-800 flex items-center justify-center">
                        <span class="text-[#5eead4] text-[10px] font-bold">PAS DE PHOTO</span>
                    </div>
                <?php endif; ?>
            </div>
            
            <h3 class="text-4xl font-black uppercase text-white leading-none">
                <?= htmlspecialchars($membre['nom']) ?><br>
                <span class="text-[#5eead4]"><?= htmlspecialchars($membre['prenom']) ?></span>
            </h3>
            
            <p class="text-slate-500 text-[11px] font-bold tracking-[0.5em] uppercase mt-6 mb-8">Membre Officiel</p>

            <div class="bg-white p-4 rounded-[2.5rem] inline-block shadow-xl">
                <img src="<?= $qrCodeImage ?>" alt="QR Code" class="w-40 h-40">
            </div>
        </div>

        <div class="flex justify-between px-10 pb-10 mt-4 text-[10px] font-bold uppercase tracking-widest text-slate-500">
            <div class="text-left">ID ADHÉRENT<br><span class="text-[#5eead4] text-lg">#<?= str_pad($membre['id'], 5, '0', STR_PAD_LEFT) ?></span></div>
            <div class="text-right">STATUT<br><span class="text-[#5eead4] text-lg uppercase"><?= htmlspecialchars($membre['statut']) ?></span></div>
        </div>
    </div>

    <div class="no-print flex gap-4 mt-12">
        <button onclick="window.print()" class="bg-teal-500 text-slate-950 px-10 py-4 rounded-2xl font-black uppercase text-xs hover:scale-105 transition-all shadow-lg shadow-teal-500/30">
            Imprimer la carte
        </button>
        <a href="membres.php" class="bg-slate-800 text-white px-10 py-4 rounded-2xl font-black uppercase text-xs hover:bg-slate-700 transition-all">
            Retour
        </a>
    </div>

</body>
</html>