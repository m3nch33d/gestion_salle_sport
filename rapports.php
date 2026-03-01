<?php 
session_start();
require_once 'config/db.php'; 

// Sécurité : Seuls les admins connectés voient les finances
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include 'includes/header.php'; 

// 1. Calcul du Total des paiements par mois (Année en cours)
$sql_mensuel = "SELECT MONTH(date_paiement) as mois, SUM(montant_paye) as total 
                FROM paiements 
                WHERE YEAR(date_paiement) = YEAR(CURDATE()) 
                GROUP BY MONTH(date_paiement)
                ORDER BY mois DESC";
$rapport_mois = $pdo->query($sql_mensuel)->fetchAll();

// 2. Liste des impayés (Membres actifs SANS souscription valide aujourd'hui)
$sql_impayes = "SELECT m.nom, m.prenom, m.telephone 
                FROM membres m 
                LEFT JOIN souscriptions s ON m.id = s.id_membre AND s.date_fin >= CURDATE()
                WHERE s.id IS NULL AND m.statut = 'actif'";
$impayes = $pdo->query($sql_impayes)->fetchAll();

// Tableau de correspondance pour les mois en français
$nom_mois = [1=>"Janvier", 2=>"Février", 3=>"Mars", 4=>"Avril", 5=>"Mai", 6=>"Juin", 
             7=>"Juillet", 8=>"Août", 9=>"Septembre", 10=>"Octobre", 11=>"Novembre", 12=>"Décembre"];
?>

<div class="container bg-teal-100 border-slate-100 mx-auto px-4 py-8">
    <div class="mb-10 bg-teal-500 text-white p-6 rounded-[30px] shadow-lg">
        <h1 class="text-4xl font-black text-gray-800 tracking-tighter italic uppercase">Rapport Financier <?= date('Y') ?></h1>
        <p class="text-gray-500">Analyse des revenus et suivi des recouvrements.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-2 bg-white p-8 rounded-[30px] shadow-sm border border-gray-100">
            <div class="flex items-center mb-6">
                <span class="text-2xl mr-3">📈</span>
                <h3 class="font-bold text-xl text-gray-800 uppercase tracking-tight">Revenus Mensuels</h3>
            </div>
            
            <div class="space-y-4">
                <?php foreach($rapport_mois as $r): ?>
                    <div class="flex justify-between items-center p-4 bg-slate-50 rounded-2xl border-l-4 border-emerald-500">
                        <span class="font-bold text-slate-600"><?= $nom_mois[$r['mois']] ?></span>
                        <span class="text-xl font-black text-emerald-600"><?= number_format($r['total'], 2, '.', ' ') ?> <small class="text-xs">HTG</small></span>
                    </div>
                <?php endforeach; ?>
                <?php if(empty($rapport_mois)): ?>
                    <p class="text-center text-gray-400 py-10 italic">Aucun encaissement cette année.</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="bg-white p-8 rounded-[30px] shadow-lg border-t-8 border-red-500">
            <div class="flex items-center mb-6 text-red-600">
                <span class="text-2xl mr-3">🚨</span>
                <h3 class="font-bold text-xl uppercase tracking-tight">Impayés</h3>
            </div>
            
            <p class="text-xs text-gray-400 mb-4 font-bold uppercase">Membres actifs sans abonnement valide :</p>
            
            <div class="space-y-3">
                <?php foreach($impayes as $i): ?>
                    <div class="p-4 bg-red-50 rounded-2xl border border-red-100">
                        <p class="font-bold text-gray-800"><?= htmlspecialchars($i['nom'] . ' ' . $i['prenom']) ?></p>
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-xs text-red-500 font-bold tracking-widest"><?= $i['telephone'] ?></span>
                            <a href="tel:<?= $i['telephone'] ?>" class="bg-white p-2 rounded-full shadow-sm hover:scale-110 transition">📞</a>
                        </div>
                    </div>
                <?php endforeach; ?>
                <?php if(empty($impayes)): ?>
                    <div class="text-center py-10 text-green-500 font-bold">
                        ✅ Tout le monde est à jour !
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>