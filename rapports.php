<?php 
include 'includes/header.php'; 
require_once 'config/db.php';

<<<<<<< HEAD
// 1. Calcul du Total des paiements par mois (Année en cours)
=======
// 1. Calcul du Total des paiements par mois
>>>>>>> 7e8a91057e02ca00628d58e63ac6c9f2945e29bc
$sql_mensuel = "SELECT MONTH(date_paiement) as mois, SUM(montant_paye) as total 
                FROM paiements 
                WHERE YEAR(date_paiement) = YEAR(CURDATE()) 
                GROUP BY MONTH(date_paiement)
                ORDER BY mois DESC";
$rapport_mois = $pdo->query($sql_mensuel)->fetchAll();

<<<<<<< HEAD
// 2. Liste des impayés (Membres actifs SANS souscription valide aujourd'hui)
=======
// 2. Liste des impayés
>>>>>>> 7e8a91057e02ca00628d58e63ac6c9f2945e29bc
$sql_impayes = "SELECT m.nom, m.prenom, m.telephone 
                FROM membres m 
                LEFT JOIN souscriptions s ON m.id = s.id_membre AND s.date_fin >= CURDATE()
                WHERE s.id IS NULL AND m.statut = 'actif'";
$impayes = $pdo->query($sql_impayes)->fetchAll();

<<<<<<< HEAD
// Tableau de correspondance pour les mois en français
=======
>>>>>>> 7e8a91057e02ca00628d58e63ac6c9f2945e29bc
$nom_mois = [1=>"Janvier", 2=>"Février", 3=>"Mars", 4=>"Avril", 5=>"Mai", 6=>"Juin", 
             7=>"Juillet", 8=>"Août", 9=>"Septembre", 10=>"Octobre", 11=>"Novembre", 12=>"Décembre"];
?>

<<<<<<< HEAD
<div class="container bg-teal-100 rounded-[50px] mx-auto px-4 py-8 shadow-2xl">
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
=======
<style>
    /* 1. Reset & Fond Immersif (Gym & Finance) */
    body {
        margin: 0;
        background: url('assets/images/gymblanc.jpeg') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Inter', 'Segoe UI', sans-serif;
        color: #f8fafc;
    }

    /* Overlay de profondeur (Gradient Radial) */
    body::before {
        content: "";
        position: fixed;
        inset: 0;
        background: radial-gradient(circle at center, rgba(15, 23, 42, 0.4) 0%, rgba(2, 6, 23, 0.85) 100%);
        z-index: -1;
    }

    /* 2. Conteneur "Neo-Glass" - Style 2026 */
    .dashboard-glass {
        background: rgba(15, 23, 42, 0.65) !important;
        backdrop-filter: blur(40px) saturate(180%);
        -webkit-backdrop-filter: blur(40px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 24px;
        box-shadow: 0 50px 100px -20px rgba(0, 0, 0, 0.5);
    }

    /* 3. Typographie Economique */
    .stat-value {
        font-family: 'Monaco', 'Consolas', monospace; /* Look "Données bancaires" */
        letter-spacing: -1px;
    }

    /* 4. Éléments de design 2026 */
    .border-glow-teal {
        border-left: 4px solid #2dd4bf;
        background: linear-gradient(90deg, rgba(45, 212, 191, 0.1) 0%, transparent 100%);
    }

    .border-glow-red {
        border-left: 4px solid #f43f5e;
        background: linear-gradient(90deg, rgba(244, 63, 94, 0.1) 0%, transparent 100%);
    }

    .header-pill {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
    }

    a[href="export_finance.php"]:hover {
    background-color: #2dd4bf !important; /* Teal 500 */
    color: #0f172a !important;            /* Slate 900 */
    transform: translateY(-2px);           /* Petit saut pour confirmer le hover */
}
</style>

<div class="container mx-auto px-4 py-12">
    <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
        <div>
            <span class="text-teal-400 font-bold uppercase tracking-[0.3em] text-xs">Analytics System v4.0</span>
            <h1 class="text-5xl font-black tracking-tighter text-white mt-2">
                RAPPORT <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-emerald-400">FINANCIER</span>
            </h1>
        </div>
        <div class="header-pill px-6 py-3 rounded-2xl text-right">
            <p class="text-slate-400 text-[10px] font-bold uppercase">Période fiscale</p>
            <p class="text-xl font-mono font-bold text-white"><?= date('M Y') ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <div class="lg:col-span-8 dashboard-glass p-1">
            <div class="p-8">
                <div class="flex justify-between items-center mb-10">
                    <h3 class="text-sm font-black uppercase tracking-widest text-slate-400 flex items-center">
                        <span class="w-2 h-2 bg-teal-500 rounded-full mr-3 animate-pulse"></span>
                        Flux de Trésorerie Mensuel
                    </h3>
                   <a href="export_finance.php" 
   class="relative z-10 inline-block text-[10px] bg-white/10 hover:!bg-teal-500 hover:!text-slate-900 px-4 py-2 rounded-lg transition-all duration-300 border border-white/20 uppercase font-black cursor-pointer">
    Exporter CSV
</a>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <?php foreach($rapport_mois as $r): ?>
                    <div class="group flex items-center justify-between p-6 rounded-2xl border border-white/5 hover:border-teal-500/30 transition-all duration-500 hover:bg-white/5">
                        <div class="flex items-center gap-6">
                            <div class="text-slate-500 font-mono text-sm"><?= sprintf("%02d", $r['mois']) ?></div>
                            <div class="text-xl font-bold tracking-tight text-slate-200 group-hover:text-teal-400 transition"><?= $nom_mois[$r['mois']] ?></div>
                        </div>
                        
                        <div class="text-right">
                            <div class="stat-value text-2xl font-bold text-white">
                                <?= number_format($r['total'], 0, '.', ' ') ?> 
                                <span class="text-xs text-teal-500/50 ml-1">HTG</span>
                            </div>
                            <div class="text-[9px] text-emerald-500 font-bold uppercase tracking-widest">+ 12.5% vs MoM</div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-6">
            <div class="dashboard-glass p-8 border-t-4 border-t-rose-500">
                <h3 class="text-sm font-black uppercase tracking-widest text-rose-500 mb-8">
                    Risques de Recouvrement
                </h3>

                <div class="space-y-4">
                    <?php foreach($impayes as $i): ?>
                    <div class="p-5 rounded-2xl bg-white/5 border border-white/5 hover:bg-rose-500/10 transition-colors group">
                        <p class="text-sm font-bold text-slate-200"><?= htmlspecialchars($i['nom'] . ' ' . $i['prenom']) ?></p>
                        <div class="flex justify-between items-center mt-4">
                            <span class="text-xs font-mono text-slate-500"><?= $i['telephone'] ?></span>
                            <a href="tel:<?= $i['telephone'] ?>" class="w-10 h-10 flex items-center justify-center bg-white/5 rounded-full hover:bg-rose-500 hover:text-white transition-all text-sm border border-white/10">
                                📞
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <?php if(empty($impayes)): ?>
                        <div class="text-center py-8">
                            <div class="text-teal-400 text-3xl mb-2">✦</div>
                            <p class="text-xs font-bold text-slate-500 uppercase">Aucun risque détecté</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="bg-gradient-to-br from-teal-500 to-emerald-600 p-8 rounded-[30px] shadow-2xl shadow-teal-500/20">
                <p class="text-white/70 text-[10px] font-black uppercase tracking-widest">Projection Annuelle</p>
                <h4 class="text-3xl font-black text-white mt-2 stat-value">Est. 2.4M</h4>
                <div class="mt-6 h-1 w-full bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full bg-white w-2/3"></div>
                </div>
>>>>>>> 7e8a91057e02ca00628d58e63ac6c9f2945e29bc
            </div>
        </div>
    </div>
</div>