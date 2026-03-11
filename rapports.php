<?php 
include 'includes/header.php';
require_once 'includes/securite.php'; 
require_once 'config/db.php';

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

// Correspondance pour les mois en français
$nom_mois = [1=>"Janvier", 2=>"Février", 3=>"Mars", 4=>"Avril", 5=>"Mai", 6=>"Juin", 
             7=>"Juillet", 8=>"Août", 9=>"Septembre", 10=>"Octobre", 11=>"Novembre", 12=>"Décembre"];
?>

<style>
    body {
        margin: 0;
        background: url('assets/images/gymblanc.jpeg') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Inter', 'Segoe UI', sans-serif;
        color: #f8fafc;
    }

    body::before {
        content: "";
        position: fixed;
        inset: 0;
        background: radial-gradient(circle at center, rgba(15, 23, 42, 0.4) 0%, rgba(2, 6, 23, 0.85) 100%);
        z-index: -1;
    }

    .dashboard-glass {
        background: rgba(15, 23, 42, 0.65) !important;
        backdrop-filter: blur(40px) saturate(180%);
        -webkit-backdrop-filter: blur(40px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 24px;
        box-shadow: 0 50px 100px -20px rgba(0, 0, 0, 0.5);
    }

    .stat-value {
        font-family: 'Monaco', 'Consolas', monospace;
        letter-spacing: -1px;
    }

    .header-pill {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
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
                    <a href="export_finance.php" class="text-[10px] bg-white/10 hover:bg-teal-500 hover:text-slate-900 px-4 py-2 rounded-lg transition-all border border-white/20 uppercase font-black">
                        Exporter CSV
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <?php foreach($rapport_mois as $r): ?>
                    <div class="group flex items-center justify-between p-6 rounded-2xl border border-white/5 hover:border-teal-500/30 transition-all hover:bg-white/5">
                        <div class="flex items-center gap-6">
                            <div class="text-slate-500 font-mono text-sm"><?= sprintf("%02d", $r['mois']) ?></div>
                            <div class="text-xl font-bold tracking-tight text-slate-200 group-hover:text-teal-400 transition"><?= $nom_mois[$r['mois']] ?></div>
                        </div>
                        
                        <div class="text-right">
                            <div class="stat-value text-2xl font-bold text-white">
                                <?= number_format($r['total'], 0, '.', ' ') ?> 
                                <span class="text-xs text-teal-500/50 ml-1">HTG</span>
                            </div>
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

            <div class="bg-gradient-to-br from-teal-500 to-emerald-600 p-8 rounded-[30px] shadow-2xl">
                <p class="text-white/70 text-[10px] font-black uppercase tracking-widest">Projection Annuelle</p>
                <h4 class="text-3xl font-black text-white mt-2 stat-value">Est. 2.4M</h4>
            </div>
        </div>
    </div>
</div>