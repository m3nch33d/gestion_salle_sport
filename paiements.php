<?php 
require_once 'config/db.php'; 
include 'includes/header.php'; 

// Requête pour récupérer tous les paiements avec les noms des membres
$sql = "SELECT p.*, m.nom, m.prenom, a.libelle 
        FROM paiements p
        JOIN souscriptions s ON p.id_souscription = s.id
        JOIN membres m ON s.id_membre = m.id
        JOIN abonnements a ON s.id_abonnement = a.id
        ORDER BY p.date_paiement DESC";
$query = $pdo->query($sql);
$paiements = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    body {
        /* On utilise gymgris.php comme arrière-plan */
        background: url('assets/images/gymgris.jpeg') no-repeat center center fixed; 
        background-size: cover;
        min-height: 100vh;
    }

    /* Effet Glassmorphism pour le titre et le total */
    .glass-header {
        background: rgba(255, 255, 255, 0.1) !important;
        backdrop-filter: blur(15px) saturate(150%);
        -webkit-backdrop-filter: blur(15px) saturate(150%);
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        border-radius: 20px;
    }

    .glass-total {
        background: rgba(16, 185, 129, 0.2) !important; /* Vert émeraude transparent */
        backdrop-filter: blur(10px);
        -webkit-backdrop-filter: blur(10px);
        border: 1px solid rgba(16, 185, 129, 0.3) !important;
    }

    /* Fond du tableau légèrement opaque pour garder la lisibilité des chiffres */
    .table-container {
        background: rgba(255, 255, 255, 0.9);
        border-radius: 24px;
        overflow: hidden;
    }
</style>

<div class="container mx-auto px-4 py-12 relative z-10">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
        <div class="glass-header px-8 py-6">
            <h1 class="text-3xl font-black text-white uppercase tracking-tighter italic">
                Historique des Paiements
            </h1>
            <p class="text-teal-300 text-xs font-bold uppercase tracking-widest mt-1">Registre des encaissements</p>
        </div>

        <div class="glass-total text-emerald-400 px-8 py-5 rounded-2xl shadow-2xl flex flex-col items-end">
            <span class="text-[10px] font-black uppercase tracking-widest text-emerald-200/60 mb-1">Recette Totale</span>
            <span class="text-3xl text-teal-900  font-black">
                <?php 
                    $total = array_sum(array_column($paiements, 'montant_paye'));
                    echo number_format($total, 2, '.', ' ') . " <span class='text-sm'>HTG</span>";
                ?>
            </span>
        </div>
    </div>

    <div class="table-container shadow-2xl border border-white/20">
        <table class="min-w-full">
            <thead class="bg-slate-100/50 border-b border-slate-200">
                <tr class="text-left text-xs font-black text-slate-500 uppercase tracking-widest shadow-xl">
                    <th class="px-6 py-5 text-center">Date</th>
                    <th class="px-6 py-5">Membre</th>
                    <th class="px-6 py-5">Forfait</th>
                    <th class="px-6 py-5">Montant</th>
                    <th class="px-6 py-5 text-center">Méthode</th>
                    <th class="px-6 py-5 text-center">Action</th> 
                </tr>
            </thead>

            <tbody class="divide-y divide-slate-100">
                <?php foreach ($paiements as $p): ?>
                <tr class="hover:bg-teal-100/70 transition-colors duration-700 ease-in-out">
                    <td class="px-6 py-5 text-xs font-bold text-slate-400 text-center">
                        <?= date('d/m/Y', strtotime($p['date_paiement'])) ?>
                        <br><span class="text-[9px] opacity-60"><?= date('H:i', strtotime($p['date_paiement'])) ?></span>
                    </td>
                    <td class="px-6 py-5 font-bold text-slate-800 uppercase text-sm italic">
                        <?= htmlspecialchars($p['nom'] . ' ' . $p['prenom']) ?>
                    </td>
                    <td class="px-6 py-5 text-slate-600 text-sm font-medium">
                        <?= htmlspecialchars($p['libelle']) ?>
                    </td>
                    <td class="px-6 py-5 font-black text-emerald-600">
                        <?= number_format($p['montant_paye'], 2, '.', ' ') ?> HTG
                    </td>
                    <td class="px-6 py-5 text-center">
                        <span class="px-3 py-1 bg-slate-200 text-slate-600 text-[9px] font-black uppercase rounded-lg border border-slate-300">
                            <?= $p['methode_paiement'] ?>
                        </span>
                    </td>
                    <td class="px-6 py-5 text-center">
                        <a href="recu.php?id=<?= $p['id'] ?>" class="inline-flex items-center px-4 py-2 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-tighter transition-all shadow-sm">
                            <span class="mr-2">  <img src="assets/images/recu.png" class="w-4 h-4">  </span> Reçu
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>