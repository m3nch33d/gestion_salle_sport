<?php 
require_once 'includes/securite.php'; 
include 'includes/header.php';
require_once 'config/db.php';

// 1. Calcul du Total des paiements par mois (Année en cours)
$sql_mensuel = "SELECT MONTH(date_paiement) as mois, SUM(montant_paye) as total 
                FROM paiements 
                WHERE YEAR(date_paiement) = YEAR(CURDATE()) 
                GROUP BY MONTH(date_paiement)
                ORDER BY mois DESC";
$rapport_mois = $pdo->query($sql_mensuel)->fetchAll();

// --- CALCUL DE LA PROJECTION DYNAMIQUE ---
$total_cumule = 0;
$nombre_de_mois = count($rapport_mois);

foreach($rapport_mois as $r) {
    $total_cumule += $r['total'];
}

// Calcul : (Total encaissé / Mois écoulés) * 12
if ($nombre_de_mois > 0) {
    $projection = ($total_cumule / $nombre_de_mois) * 12;
    if ($projection >= 1000000) {
        $projection_format = number_format($projection / 1000000, 1, '.', '') . 'M';
    } else {
        $projection_format = number_format($projection / 1000, 0, '.', '') . 'K';
    }
} else {
    $projection_format = "0 HTG";
}

// 2. Liste des impayés
$sql_impayes = "SELECT m.nom, m.prenom, m.telephone 
                FROM membres m 
                LEFT JOIN souscriptions s ON m.id = s.id_membre AND s.date_fin >= CURDATE()
                WHERE s.id IS NULL AND m.statut = 'actif'";
$impayes = $pdo->query($sql_impayes)->fetchAll();

$nom_mois = [1=>"Janvier", 2=>"Février", 3=>"Mars", 4=>"Avril", 5=>"Mai", 6=>"Juin", 
             7=>"Juillet", 8=>"Août", 9=>"Septembre", 10=>"Octobre", 11=>"Novembre", 12=>"Décembre"];
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="public/css/style.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    body {
        margin: 0;
        background: url('assets/images/gymblanc.jpeg') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Inter', sans-serif;
        color: #f8fafc;
        overflow-x: hidden;
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
    }

    .stat-value { font-family: 'Monaco', monospace; letter-spacing: -1px; }

    .header-pill {
        background: rgba(255, 255, 255, 0.03);
        border: 1px solid rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
    }

    /* Scrollbar personnalisée pour la liste des impayés */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

    main { background: transparent !important; }

    /* MEDIA QUERIES POU RESPONSIVITE */
    @media (max-width: 768px) {
        .text-5xl { font-size: 2.5rem !important; } /* Rapport Financier vin pi piti */
        .p-8 { padding: 1.5rem !important; } /* Redui espas anndan bwat yo */
        .flex-col.md\:flex-row { align-items: flex-start !important; }
        .header-pill { text-align: left !important; width: 100%; }
    }
</style>

<div id="main-content" class="container mx-auto px-4 py-8 md:py-12 animate__animated animate__fadeInUp">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 md:mb-12 gap-6">
        <div>
            <span class="text-teal-400 font-bold uppercase tracking-[0.3em] text-[10px] md:text-xs">Système d'analyse 4.0</span>
            <h1 class="text-4xl md:text-5xl font-black tracking-tighter text-white mt-2">
                RAPPORT <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-emerald-400">FINANCIER</span>
            </h1>
        </div>
        <div class="header-pill px-6 py-3 rounded-2xl md:text-right">
            <p class="text-slate-400 text-[10px] font-bold uppercase">Période fiscale</p>
            <p class="text-lg md:text-xl font-mono font-bold text-white"><?= date('M Y') ?></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 items-start">
        
        <div class="lg:col-span-8 dashboard-glass p-1">
            <div class="p-6 md:p-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 md:mb-10 gap-4">
                    <h3 class="text-[11px] md:text-sm font-black uppercase tracking-widest text-slate-400 flex items-center">
                        <span class="w-2 h-2 bg-teal-500 rounded-full mr-3 animate-pulse"></span>
                        Flux de Trésorerie Mensuel
                    </h3>
                    <a href="export_finance.php" target="_blank" class="text-[9px] md:text-[10px] bg-white/10 hover:bg-teal-500 hover:text-slate-900 px-4 py-2 rounded-lg transition-all border border-white/20 uppercase font-black w-full sm:w-auto text-center">
                        Exporter CSV
                    </a>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <?php foreach($rapport_mois as $r): ?>
                    <div class="group flex flex-col sm:flex-row items-start sm:items-center justify-between p-5 md:p-6 rounded-2xl border border-white/5 hover:border-teal-500/30 transition-all hover:bg-white/5 gap-3">
                        <div class="flex items-center gap-4 md:gap-6">
                            <div class="text-slate-500 font-mono text-xs md:text-sm"><?= sprintf("%02d", $r['mois']) ?></div>
                            <div class="text-lg md:text-xl font-bold tracking-tight text-slate-200 group-hover:text-teal-400 transition"><?= $nom_mois[$r['mois']] ?></div>
                        </div>
                        <div class="sm:text-right w-full sm:w-auto">
                            <div class="stat-value text-xl md:text-2xl font-bold text-white">
                                <?= number_format($r['total'], 0, '.', ' ') ?> 
                                <span class="text-[10px] md:text-xs text-teal-500/50 ml-1">HTG</span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-6">
            <div class="dashboard-glass p-6 md:p-8 border-t-4 border-t-rose-500 shadow-2xl shadow-rose-500/10">
                <h3 class="text-[11px] md:text-sm font-black uppercase tracking-widest text-rose-500 mb-6 md:mb-8">
                    Risques de Recouvrement
                </h3>
                <div class="space-y-4 max-h-[350px] md:max-h-[400px] overflow-y-auto pr-2 custom-scrollbar">
                    <?php foreach($impayes as $i): ?>
                    <div class="p-4 md:p-5 rounded-2xl bg-white/5 border border-white/5 hover:bg-rose-500/10 transition-colors group">
                        <p class="text-xs md:text-sm font-bold text-slate-200"><?= htmlspecialchars($i['nom'] . ' ' . $i['prenom']) ?></p>
                        <div class="flex justify-between items-center mt-3 md:mt-4">
                            <span class="text-[10px] md:text-xs font-mono text-slate-500"><?= $i['telephone'] ?></span>
                            <a href="tel:<?= $i['telephone'] ?>" class="w-8 h-8 md:w-10 md:h-10 flex items-center justify-center bg-white/5 rounded-full hover:bg-rose-500 hover:text-white transition-all text-xs border border-white/10">
                                📞
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="bg-gradient-to-br from-teal-500 to-emerald-600 p-6 md:p-8 rounded-[30px] shadow-2xl shadow-teal-500/20 transform hover:scale-[1.02] transition-transform">
                <p class="text-white/70 text-[9px] md:text-[10px] font-black uppercase tracking-widest">Projection Annuelle</p>
                <h4 class="text-3xl md:text-4xl font-black text-white mt-2 stat-value">
                    Est. <?= $projection_format ?>
                </h4>
                <div class="mt-4 flex items-center gap-2">
                    <span class="text-[9px] md:text-[10px] bg-white/20 px-2 py-1 rounded-full text-white font-bold uppercase">Basé sur <?= $nombre_de_mois ?> mois</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById('main-content');
    const links = document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not([href^="tel:"])');

    links.forEach(link => {
        link.addEventListener('click', function(e) {
            if (link.hostname === window.location.hostname) {
                e.preventDefault();
                const destination = this.href;
                container.classList.remove('animate__fadeInUp');
                container.classList.add('animate__fadeOutDown');
                setTimeout(() => { window.location.href = destination; }, 500);
            }
        });
    });
});
</script>

<?php echo "</main></body></html>"; ?>