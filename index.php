<?php 
// 1. On inclut le header qui gère déjà le session_start() et la structure HTML
include 'includes/header.php'; 

// 2. Connexion à la base (assure-toi que le chemin est correct)
require_once 'config/db.php';

// 3. Calcul des statistiques (SQL)
try {
    $total_membres = $pdo->query("SELECT COUNT(*) FROM membres")->fetchColumn();
    $membres_actifs = $pdo->query("SELECT COUNT(*) FROM membres WHERE statut = 'actif'")->fetchColumn();
    $recette_mois = $pdo->query("SELECT SUM(montant_paye) FROM paiements WHERE MONTH(date_paiement) = MONTH(CURDATE())")->fetchColumn() ?: 0;
    $alertes_expiration = $pdo->query("SELECT COUNT(*) FROM souscriptions WHERE date_fin <= DATE_ADD(CURDATE(), INTERVAL 3 DAY) AND date_fin >= CURDATE()")->fetchColumn();
} catch (Exception $e) {
    echo "Erreur SQL : " . $e->getMessage();
}
?>

<div class="space-y-8 bg-emerald-100 p-8 rounded-[30px] border border-slate-100">
    <div class="flex justify-between bg-emerald-50 items-center">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight uppercase">Tableau de Bord</h2>
            <p class="text-slate-500 font-medium italic">Content de vous revoir, <?= htmlspecialchars($_SESSION['utilisateur_nom']) ?> !</p>
        </div>
        <div class="text-right">
            <p class="text-sm font-bold text-slate-400 uppercase tracking-widest"><?= date('d F Y') ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-[30px] shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="w-14 h-14 bg-emerald-50 text-blue-600 rounded-2xl flex items-center justify-center text-2xl">👥</div>
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase">Membres</p>
                <p class="text-2xl font-black text-slate-800"><?= $total_membres ?></p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[30px] shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="w-14 h-14 bg-emerald-50 text-teal-600 rounded-2xl flex items-center justify-center text-2xl">⚡</div>
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase">Actifs</p>
                <p class="text-2xl font-black text-slate-800"><?= $membres_actifs ?></p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[30px] shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center text-2xl">💰</div>
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase">Recettes</p>
                <p class="text-2xl font-black text-slate-800"><?= number_format($recette_mois, 0, '.', ' ') ?> <small class="text-xs">HTG</small></p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-[30px] shadow-sm border border-slate-100 flex items-center space-x-4">
            <div class="w-14 h-14 bg-red-50 text-red-600 rounded-2xl flex items-center justify-center text-2xl">⚠️</div>
            <div>
                <p class="text-slate-400 text-xs font-bold uppercase">Alertes</p>
                <p class="text-2xl font-black text-slate-800"><?= $alertes_expiration ?></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 bg-white rounded-[40px] p-8 shadow-sm border border-slate-100">
            <h3 class="text-xl font-black text-slate-800 mb-6 uppercase">Dernières inscriptions</h3>
            <div class="space-y-4">
                <?php
                $derniers = $pdo->query("SELECT * FROM membres ORDER BY id DESC LIMIT 5")->fetchAll();
                foreach($derniers as $d): ?>
                <div class="flex items-center justify-between p-4 hover:bg-slate-50 rounded-2xl transition">
                    <div class="flex items-center space-x-4">
                        <div class="w-10 h-10 bg-teal-500 rounded-full flex items-center justify-center font-bold text-white">
                            <?= strtoupper(substr($d['nom'], 0, 1)) ?>
                        </div>
                        <div>
                            <p class="font-bold text-slate-800"><?= htmlspecialchars($d['nom'] . ' ' . $d['prenom']) ?></p>
                            <p class="text-xs text-slate-400"><?= htmlspecialchars($d['email']) ?></p>
                        </div>
                    </div>
                    <span class="text-xs font-black px-3 py-1 rounded-full bg-teal-100 text-teal-700">ACTIF</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="space-y-4">
            <a href="ajouter_membre.php" class="block p-6 bg-teal-500 text-white rounded-[30px] shadow-lg hover:bg-teal-400 transition transform hover:-translate-y-1">
                <p class="text-2xl mb-2">➕</p>
                <p class="font-black uppercase tracking-tighter">Nouveau Membre</p>
            </a>
            <a href="scanner.php" class="block p-6 bg-slate-900 text-white rounded-[30px] shadow-lg hover:bg-slate-800 transition transform hover:-translate-y-1">
                <p class="text-2xl mb-2">🛡️</p>
                <p class="font-black uppercase tracking-tighter">Scanner</p>
            </a>
        </div>
    </div>
</div>

</main> </body>
</html>