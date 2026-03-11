<?php 
require_once 'config/db.php';

$id_paiement = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_paiement <= 0) {
    header("Location: paiements.php");
    exit();
}

// Récupération des données
$sql = "SELECT p.*, m.nom, m.prenom, m.telephone, a.libelle, s.date_fin 
        FROM paiements p
        JOIN souscriptions s ON p.id_souscription = s.id
        JOIN membres m ON s.id_membre = m.id
        JOIN abonnements a ON s.id_abonnement = a.id
        WHERE p.id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_paiement]);
$data = $stmt->fetch();

if (!$data) die("Erreur : Paiement introuvable.");

// Logique simplifiée Cash/MonCash
$methode = strtolower($data['methode_paiement']);
$label_methode = ($methode === 'moncash') ? "Paiement via MonCash" : "Paiement en Espèces (Cash)";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu Elite #<?= $data['id'] ?></title>
    <link rel="stylesheet" href="public/css/style.css">
    <style>
        /* Configuration de l'arrière-plan */
        body {
            margin: 0;
            background: url('/gestion_salle_sport/assets/images/gymblanc.jpeg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Inter', sans-serif;
        }

        /* Overlay léger pour garantir la lisibilité du reçu sur l'image */
        body::before {
            content: "";
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.6); /* Voile blanc pour adoucir l'image */
            z-index: -1;
        }

        .receipt-card {
            background: white;
            border: 1px solid rgba(0, 0, 0, 0.05);
            border-radius: 30px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
        }

        .teal-accent {
            color: #0d9488;
        }

        @media print {
            .no-print { display: none !important; }
            body { background: white !important; }
            body::before { display: none !important; }
            .receipt-card { border: none; box-shadow: none; margin: 0; padding: 0; }
        }
    </style>
</head>
<body class="p-4 md:p-12">
    
    <div class="max-w-2xl mx-auto receipt-card p-8 md:p-14 relative z-10">
        <div class="flex flex-col md:flex-row justify-between items-start border-b border-slate-100 pb-8 mb-8 gap-4">
            <div>
                <h1 class="text-3xl font-black uppercase tracking-tighter teal-accent italic">
                    Dechouke Grès Fitness
                </h1>
                <p class="text-slate-500 text-xs font-bold uppercase tracking-widest mt-1">Management System v4.0</p>
                <p class="text-slate-400 text-xs mt-4">Cap-Haïtien, Haïti</p>
            </div>
            <div class="text-right">
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Reçu Officiel</p>
                <p class="text-4xl font-mono font-bold text-slate-900">#00<?= $data['id'] ?></p>
            </div>
        </div>

        <div class="mb-10">
            <span class="text-teal-600 font-bold uppercase text-[9px] tracking-[0.2em]">Membre Enregistré</span>
            <p class="text-2xl font-black text-slate-800 mt-1 uppercase italic">
                <?= htmlspecialchars($data['nom'] . ' ' . $data['prenom']) ?>
            </p>
            <p class="text-slate-500 font-mono text-sm mt-1"><?= htmlspecialchars($data['telephone']) ?></p>
        </div>

        <div class="bg-slate-50 rounded-3xl p-8 border border-slate-100 mb-8">
            <div class="flex justify-between items-center border-b border-slate-200 pb-4 mb-4">
                <span class="text-[10px] font-bold uppercase text-slate-400">Description</span>
                <span class="text-[10px] font-bold uppercase text-slate-400">Montant Total</span>
            </div>
            <div class="flex justify-between items-center">
                <div>
                    <p class="font-bold text-slate-800 text-lg"><?= htmlspecialchars($data['libelle']) ?></p>
                    <p class="text-[10px] text-teal-600 font-bold mt-1 uppercase">
                        Valide jusqu'au <?= date('d/m/Y', strtotime($data['date_fin'])) ?>
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-3xl font-black text-slate-900"><?= number_format($data['montant_paye'], 2) ?></p>
                    <p class="text-[10px] font-bold text-slate-400 uppercase">HTG</p>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-end">
            <div>
                <p class="text-[9px] font-bold uppercase tracking-widest text-slate-400 mb-1">Méthode de Paiement</p>
                <p class="font-bold text-slate-700 text-sm"><?= $label_methode ?></p>
            </div>
            <div class="text-right italic opacity-50 text-[10px] font-bold uppercase tracking-tighter">
                Merci de votre confiance
            </div>
        </div>
    </div>

    <div class="text-center no-print mt-10 space-y-4">
        <button onclick="window.print()" class="bg-teal-600 hover:bg-teal-700 text-white px-12 py-4 rounded-2xl font-black shadow-xl shadow-teal-600/20 transition transform hover:-translate-y-1 uppercase text-xs tracking-widest">
            🖨️ Imprimer la Facture
        </button>
        <a href="paiements.php" class="block text-slate-400 hover:text-teal-600 font-bold transition text-[10px] uppercase tracking-[0.2em]">
            ← Revenir au tableau de bord
        </a>
    </div>
</body>
</html>