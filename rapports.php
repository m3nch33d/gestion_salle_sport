<?php 
require_once 'includes/securite.php'; 
include 'includes/header.php';
require_once 'config/db.php';

// 1. Calcul Trésorerie Mensuelle
$sql_mensuel = "SELECT MONTH(date_paiement) as mois, SUM(montant_paye) as total 
                FROM paiements WHERE YEAR(date_paiement) = YEAR(CURDATE()) 
                GROUP BY MONTH(date_paiement) ORDER BY mois DESC";
$rapport_mois = $pdo->query($sql_mensuel)->fetchAll();

// 2. Calcul Projection Annuelle
$total_cumule = 0;
foreach($rapport_mois as $r) { $total_cumule += $r['total']; }
$nb_mois = count($rapport_mois);

if ($nb_mois > 0) {
    $projection = ($total_cumule / $nb_mois) * 12;
    $projection_format = ($projection >= 1000000) ? number_format($projection / 1000000, 1) . 'M' : number_format($projection / 1000, 0) . 'K';
} else {
    $projection_format = '0 HTG';
}

// 3. Liste des impayés (Membres actifs sans souscription valide)
$sql_impayes = "SELECT m.nom, m.prenom, m.telephone 
                FROM membres m 
                LEFT JOIN souscriptions s ON m.id = s.id_membre AND s.date_fin >= CURDATE()
                WHERE s.id IS NULL AND m.statut = 'actif'";
$impayes = $pdo->query($sql_impayes)->fetchAll();

$nom_mois = [1=>"Janvier", 2=>"Février", 3=>"Mars", 4=>"Avril", 5=>"Mai", 6=>"Juin", 
             7=>"Juillet", 8=>"Août", 9=>"Septembre", 10=>"Octobre", 11=>"Novembre", 12=>"Décembre"];
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    body {
        background: url('assets/images/gymblanc.jpeg') no-repeat center center fixed;
        background-size: cover;
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
        backdrop-filter: blur(40px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 24px;
    }
    .stat-value { font-family: 'Monaco', monospace; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    main { background: transparent !important; }
</style>

<div id="main-content" class="container mx-auto px-4 py-8 md:py-12 animate__animated animate__fadeInUp">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 md:mb-12 gap-6">
        <div>
            <span class="text-teal-400 font-bold uppercase tracking-[0.3em] text-[10px] md:text-xs">Système d'analyse 4.0</span>
            <h1 class="text-3xl md:text-5xl font-black text-white mt-2 uppercase tracking-tighter">
                Rapport <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-emerald-400">Financier</span>
            </h1>
        </div>
        <a href="export_finance.php" target="_blank" class="w-full md:w-auto text-center bg-white/10 hover:bg-teal-500 hover:text-slate-900 px-6 py-4 md:py-3 rounded-xl border border-white/20 uppercase font-black text-[10px] transition-all no-anim">
            Exporter CSV
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 items-start">
        
        <div class="lg:col-span-8 dashboard-glass p-6 md:p-8">
            <h3 class="text-[11px] md:text-sm font-black uppercase tracking-widest text-slate-400 mb-8 flex items-center">
                <span class="w-2 h-2 bg-teal-500 rounded-full mr-3 animate-pulse"></span>
                Flux de Trésorerie Mensuel
            </h3>
            
            <div class="space-y-4">
                <?php foreach($rapport_mois as $r): ?>
                <div class="flex flex-row items-center justify-between p-4 md:p-6 rounded-2xl bg-white/5 border border-white/5 hover:border-teal-500/30 transition-all group">
                    <div class="flex items-center gap-4 md:gap-6">
                        <span class="text-slate-500 font-mono text-xs"><?= sprintf("%02d", $r['mois']) ?></span>
                        <span class="text-sm md:text-xl font-bold text-slate-200 group-hover:text-teal-400 transition"><?= $nom_mois[$r['mois']] ?></span>
                    </div>
                    <div class="stat-value text-base md:text-2xl font-bold text-white">
                        <?= number_format($r['total'], 0, '.', ' ') ?> 
                        <span class="text-[10px] text-teal-500/50 ml-1">HTG</span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-6">
            
            <div class="bg-gradient-to-br from-teal-500 to-emerald-600 p-6 md:p-8 rounded-[30px] shadow-2xl shadow-teal-500/20 transform hover:scale-[1.02] transition-transform">
                <p class="text-white/70 text-[9px] md:text-[10px] font-black uppercase tracking-widest">Projection Annuelle</p>
                <h4 class="text-3xl md:text-4xl font-black text-white mt-2 stat-value">Est. <?= $projection_format ?></h4>
                <div class="mt-4 flex items-center gap-2">
                    <span class="text-[9px] bg-white/20 px-2 py-1 rounded-full text-white font-bold uppercase">Basé sur <?= $nb_mois ?> mois</span>
                </div>
            </div>

            <div class="dashboard-glass p-6 md:p-8 border-t-4 border-t-rose-500">
                <h3 class="text-[11px] md:text-sm font-black uppercase tracking-widest text-rose-500 mb-6">Risques de Recouvrement</h3>
                <div class="space-y-3 max-h-[300px] md:max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                    <?php foreach($impayes as $i): ?>
                    <div class="p-4 rounded-xl bg-white/5 flex justify-between items-center group hover:bg-rose-500/10 transition-all border border-white/5">
                        <div class="overflow-hidden">
                            <p class="text-xs font-bold text-slate-200 truncate"><?= htmlspecialchars($i['nom'] . ' ' . $i['prenom']) ?></p>
                            <p class="text-[10px] font-mono text-slate-500 mt-1"><?= $i['telephone'] ?></p>
                        </div>
                        <a href="tel:<?= $i['telephone'] ?>" class="w-8 h-8 flex items-center justify-center bg-white/5 rounded-full hover:bg-rose-500 hover:text-white transition-all text-xs border border-white/10 shrink-0 ml-2">
                            📞
                        </a>
                    </div>
                    <?php endforeach; ?>
                    
                    <?php if(empty($impayes)): ?>
                        <p class="text-xs text-slate-500 italic text-center py-4">Aucun impayé détecté.</p>
                    <?php endif; ?>
                </div>
            </div>
            
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById('main-content');
    const links = document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not([href^="tel:"]):not(.no-anim)');
    
    links.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const dest = this.href;
            container.classList.remove('animate__fadeInUp');
            container.classList.add('animate__fadeOutDown');
            setTimeout(() => { window.location.href = dest; }, 500);
        });
    });
});
</script>

<?php echo "</main></body></html>"; ?>