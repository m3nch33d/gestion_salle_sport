<?php 
require_once 'includes/securite.php'; 
include 'includes/header.php';
require_once 'config/db.php';

// Calcul Trésorerie
$sql_mensuel = "SELECT MONTH(date_paiement) as mois, SUM(montant_paye) as total 
                FROM paiements WHERE YEAR(date_paiement) = YEAR(CURDATE()) 
                GROUP BY MONTH(date_paiement) ORDER BY mois DESC";
$rapport_mois = $pdo->query($sql_mensuel)->fetchAll();

// Calcul Projection
$total_cumule = 0;
foreach($rapport_mois as $r) { $total_cumule += $r['total']; }
$nb_mois = count($rapport_mois);
$projection_format = ($nb_mois > 0) ? number_format((($total_cumule / $nb_mois) * 12) / 1000000, 1) . 'M' : '0 HTG';

// Impayés
$sql_impayes = "SELECT m.nom, m.prenom, m.telephone FROM membres m 
                LEFT JOIN souscriptions s ON m.id = s.id_membre AND s.date_fin >= CURDATE()
                WHERE s.id IS NULL AND m.statut = 'actif'";
$impayes = $pdo->query($sql_impayes)->fetchAll();

$nom_mois = [1=>"Janvier", 2=>"Février", 3=>"Mars", 4=>"Avril", 5=>"Mai", 6=>"Juin", 
             7=>"Juillet", 8=>"Août", 9=>"Septembre", 10=>"Octobre", 11=>"Novembre", 12=>"Décembre"];
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    body { background: url('assets/images/gymblanc.jpeg') no-repeat center center fixed; background-size: cover; color: #f8fafc; }
    body::before { content: ""; position: fixed; inset: 0; background: radial-gradient(circle at center, rgba(15, 23, 42, 0.4) 0%, rgba(2, 6, 23, 0.85) 100%); z-index: -1; }
    .dashboard-glass { background: rgba(15, 23, 42, 0.65) !important; backdrop-filter: blur(40px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 24px; }
    .stat-value { font-family: 'Monaco', monospace; }
    main { background: transparent !important; }
</style>

<div id="main-content" class="container mx-auto px-4 py-8 md:py-12 animate__animated animate__fadeInUp">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 md:mb-12 gap-6">
        <div>
            <span class="text-teal-400 font-bold uppercase tracking-[0.3em] text-[10px]">Analyse 4.0</span>
            <h1 class="text-3xl md:text-5xl font-black text-white mt-2 uppercase">Rapport <span class="text-teal-400">Financier</span></h1>
        </div>
        <a href="export_finance.php" target="_blank" class="no-anim w-full md:w-auto text-center bg-white/10 hover:bg-teal-500 hover:text-slate-900 px-6 py-4 md:py-2 rounded-xl border border-white/20 uppercase font-black text-[10px] transition-all">
            Exporter CSV
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8 dashboard-glass p-4 md:p-8">
            <h3 class="text-xs font-black uppercase tracking-widest text-slate-400 mb-8 flex items-center">
                <span class="w-2 h-2 bg-teal-500 rounded-full mr-3"></span> Flux Mensuel
            </h3>
            <div class="space-y-4">
                <?php foreach($rapport_mois as $r): ?>
                <div class="flex items-center justify-between p-4 md:p-6 rounded-2xl bg-white/5 border border-white/5">
                    <div class="flex items-center gap-4">
                        <span class="text-xs font-mono text-slate-500"><?= sprintf("%02d", $r['mois']) ?></span>
                        <span class="text-sm md:text-xl font-bold"><?= $nom_mois[$r['mois']] ?></span>
                    </div>
                    <div class="stat-value text-lg md:text-2xl font-bold"><?= number_format($r['total'], 0, '.', ' ') ?> <span class="text-xs text-teal-500">HTG</span></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-6">
            <div class="bg-gradient-to-br from-teal-500 to-emerald-600 p-8 rounded-[30px] shadow-xl">
                <p class="text-white/70 text-[10px] font-black uppercase tracking-widest">Projection Annuelle</p>
                <h4 class="text-3xl md:text-4xl font-black text-white mt-2 stat-value">Est. <?= $projection_format ?></h4>
            </div>

            <div class="dashboard-glass p-6 border-t-4 border-t-rose-500">
                <h3 class="text-xs font-black uppercase text-rose-500 mb-6">Risques de Recouvrement</h3>
                <div class="space-y-4 max-h-[300px] overflow-y-auto pr-2">
                    <?php foreach($impayes as $i): ?>
                    <div class="p-4 rounded-xl bg-white/5 flex justify-between items-center">
                        <span class="text-xs font-bold"><?= $i['nom'] ?></span>
                        <a href="tel:<?= $i['telephone'] ?>" class="text-lg">📞</a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById('main-content');
    // On exclut les liens target=_blank et ceux avec la classe no-anim
    const links = document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not([href^="tel:"]):not(.no-anim)');
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const dest = this.href;
            container.classList.add('animate__fadeOutDown');
            setTimeout(() => { window.location.href = dest; }, 500);
        });
    });
});
</script>