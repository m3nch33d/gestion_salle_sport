<?php
session_start();
require_once 'config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$stmt = $pdo->prepare("SELECT * FROM membres WHERE id = ?");
$stmt->execute([$id]);
$m = $stmt->fetch();

if (!$m) { die("Membre introuvable."); }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Carte Membre - <?= htmlspecialchars($m['prenom']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <style>
        @media print { .no-print { display: none; } }
    </style>
</head>
<body class="bg-slate-900 flex flex-col items-center justify-center min-h-screen p-4">

    <div class="bg-white rounded-[40px] shadow-2xl p-10 w-full max-w-sm text-center relative overflow-hidden">
        <div class="absolute top-0 left-0 w-full h-3 bg-indigo-600"></div>

        <h1 class="text-3xl font-black text-slate-800 uppercase mt-4"><?= htmlspecialchars($m['nom']) ?></h1>
        <p class="text-indigo-600 font-bold text-xl mb-8"><?= htmlspecialchars($m['prenom']) ?></p>

        <div class="flex justify-center mb-8">
            <div id="qrcode" class="p-4 bg-white border-2 border-dashed border-slate-200 rounded-3xl"></div>
        </div>

        <p class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-10 italic">Scannez ce code à l'entrée</p>

        <button onclick="window.print()" class="no-print w-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-black py-4 rounded-2xl transition mb-4">
            Imprimer la carte
        </button>

        <a href="membres.php" class="no-print text-slate-400 font-bold hover:text-indigo-600 transition text-sm">
            Retour
        </a>
    </div>

    <script>
        // On génère le QR Code avec l'ID du membre
        new QRCode(document.getElementById("qrcode"), {
            text: "<?= $m['id'] ?>",
            width: 180,
            height: 180,
            colorDark : "#1e293b",
            colorLight : "#ffffff",
            correctLevel : QRCode.CorrectLevel.H
        });
    </script>
</body>
</html>