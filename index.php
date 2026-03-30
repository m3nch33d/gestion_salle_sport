<?php 
include 'includes/header.php'; 
require_once 'config/db.php';

function formatArgent($n) {
    if ($n >= 1000000) return round($n / 1000000, 1) . 'M';
    if ($n >= 1000) return round($n / 1000, 1) . 'k';
    return $n;
}

try {
    $total_membres = $pdo->query("SELECT COUNT(*) FROM membres")->fetchColumn();
    $membres_actifs = $pdo->query("SELECT COUNT(*) FROM membres WHERE statut = 'actif'")->fetchColumn();
    $recette_mois = $pdo->query("SELECT SUM(montant_paye) FROM paiements WHERE MONTH(date_paiement) = MONTH(CURDATE()) AND YEAR(date_paiement) = YEAR(CURDATE())")->fetchColumn() ?: 0;
    $alertes_expiration = $pdo->query("SELECT COUNT(*) FROM souscriptions WHERE date_fin <= DATE_ADD(CURDATE(), INTERVAL 3 DAY) AND date_fin >= CURDATE()")->fetchColumn();
} catch (Exception $e) { echo "Erreur SQL : " . $e->getMessage(); }
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    body { min-height: 100vh; overflow-x: hidden; font-family: 'Inter', sans-serif; background: #020617; }
    #video-bg { position: fixed; right: 0; bottom: 0; min-width: 100%; min-height: 100%; z-index: -2; object-fit: cover; filter: brightness(0.3) contrast(1.1); }
    .video-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(180deg, rgba(2, 6, 23, 0.4) 0%, rgba(2, 6, 23, 0.9) 100%); z-index: -1; }
    main { background: transparent !important; }

    .glass-card {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glass-card:hover { border: 3px solid rgba(45, 212, 191, 0.3); transform: translateY(-5px); }
</style>

<video autoplay muted loop playsinline id="video-bg">
    <source src="assets/videos/background.mp4" type="video/mp4">
</video>
<div class="video-overlay"></div>

<div id="main-content" class="max-w-7xl mx-auto space-y-8 p-4 md:p-10 animate__animated animate__fadeInUp">
     
    <div class="glass-card flex flex-col md:flex-row justify-between rounded-[32px] items-start md:items-center p-6 md:p-8 gap-4 border-b-2 border-teal-500/20">
        <div class="flex items-center space-x-5">
            <div class="p-3 bg-teal-500/10 rounded-2xl border border-teal-500/30">
                <img src="assets/images/logogym.png" class="w-10 h-10 object-contain">
            </div>
            <div>
                <h2 class="text-3xl font-black text-white tracking-tighter uppercase">DASHBOARD</h2>
                <p class="text-slate-400 text-sm">Bienvenue, <span class="text-teal-400 font-bold"><?= htmlspecialchars($_SESSION['utilisateur_nom'] ?? 'Admin') ?></span></p>
            </div>
        </div>
        <div class="bg-white/5 px-6 py-2 rounded-full border border-white/10">
            <p class="text-xs font-black text-teal-400 uppercase tracking-[0.2em]"><?= date('d F Y') ?></p>
        </div>
    </div>
  
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-8">
        <?php 
        $stats = [
            ['Membres', $total_membres, 'assets/images/members.png', 'text-white'],
            ['Actifs', $membres_actifs, 'assets/images/actif.png', 'text-teal-400'],
            ['Recettes', formatArgent($recette_mois), 'assets/images/bagmoney.png', 'text-white'],
            ['Alertes', $alertes_expiration, 'assets/images/warning.png', 'text-red-400']
        ];
        foreach($stats as $s): ?>
        <div class="glass-card p-6 rounded-[32px] flex flex-col items-center md:items-start space-y-4">
            <div class="w-12 h-12 bg-white/5 rounded-2xl flex items-center justify-center border border-white/10">
                <img src="<?= $s[2] ?>" class="w-10 h-10 ">
            </div>
            <div>
                <p class="text-slate-500 text-[10px] font-bold uppercase tracking-widest"><?= $s[0] ?></p>
                <p class="text-3xl font-black <?= $s[3] ?> mt-1"><?= $s[1] ?><?= $s[0]=='Recettes'?' <span class="text-xs font-normal opacity-50">HTG</span>':'' ?></p>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="lg:col-span-2 glass-card rounded-[40px] p-8">
            <h3 class="text-xl font-black text-white uppercase tracking-tight mb-8 shadow-xl">Dernières Activités</h3>
            <div class="space-y-4">
                <?php
                $derniers = $pdo->query("SELECT * FROM membres ORDER BY id DESC LIMIT 5")->fetchAll();
                foreach($derniers as $d): ?>
                <div class="flex items-center justify-between p-4 rounded-3xl bg-white/[0.02] border border-transparent hover:border-teal-500/20 hover:bg-teal-500/5 transition-all group">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-gradient-to-tr from-teal-500 to-emerald-400 rounded-2xl flex items-center justify-center font-black text-slate-900 shadow-lg shadow-teal-500/20">
                            <?= strtoupper(substr($d['nom'], 0, 1)) ?>
                        </div>
                        <div>
                            <p class="font-bold text-white text-base group-hover:text-teal-400 transition-colors"><?= htmlspecialchars($d['nom'] . ' ' . $d['prenom']) ?></p>
                            <p class="text-xs text-slate-500"><?= htmlspecialchars($d['email']) ?></p>
                        </div>
                    </div>
                    <span class="text-[10px] font-black text-teal-400 tracking-tighter bg-teal-500/10 px-3 py-1 rounded-full">ACTIF</span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="flex flex-col gap-6">
            <a href="ajouter_membre.php" class="glass-card p-8 rounded-[35px] flex flex-col items-center justify-center group text-center h-full hover:bg-teal-500/10">
                <div class="w-16 h-16 bg-teal-500 rounded-3xl flex items-center justify-center mb-6 shadow-xl shadow-teal-500/30 group-hover:scale-110 transition-transform">
                    <img src="assets/images/add.png" class="w-8 h-8 brightness-0 invert">
                </div>
                <h4 class="text-white font-black uppercase tracking-tighter text-lg">Nouveau Membre</h4>
            </a>

            <a href="scanner.php" class="glass-card p-8 rounded-[35px] flex flex-col items-center justify-center group text-center h-full hover:bg-slate-800/50">
                <div class="w-16 h-16 bg-slate-800 rounded-3xl flex items-center justify-center mb-6 border border-white/10 group-hover:scale-110 transition-transform">
                    <img src="assets/images/security.png" class="w-8 h-8 grayscale brightness-200">
                </div>
                <h4 class="text-white font-black uppercase tracking-tighter text-lg">Scan Entrée</h4>
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const mainContainer = document.getElementById('main-content');
    const links = document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not([href^="tel:"])');

    links.forEach(link => {
        link.addEventListener('click', function(e) {
            if (link.hostname === window.location.hostname) {
                e.preventDefault();
                const destination = this.href;

                // LANCE L'ANIMATION DE SORTIE VERS LE BAS
                mainContainer.classList.remove('animate__fadeInUp');
                mainContainer.classList.add('animate__fadeOutDown');

                // REDIRECTION APRÈS 500ms (durée de l'animation)
                setTimeout(() => {
                    window.location.href = destination;
                }, 500);
            }
        });
    });
});
</script>