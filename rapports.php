<?php 
require_once 'includes/securite.php'; 
include 'includes/header.php';
require_once 'config/db.php';

// 1. Flux de Trésorerie Mensuel
$sql_mensuel = "SELECT MONTH(date_paiement) as mois, SUM(montant_paye) as total 
                FROM paiements WHERE YEAR(date_paiement) = YEAR(CURDATE()) 
                GROUP BY MONTH(date_paiement) ORDER BY mois DESC";
$rapport_mois = $pdo->query($sql_mensuel)->fetchAll();

// 2. Projection Annuelle
$total_cumule = 0;
foreach($rapport_mois as $r) { $total_cumule += $r['total']; }
$nb_mois = count($rapport_mois);
$projection_format = ($nb_mois > 0) ? number_format(($total_cumule / $nb_mois) * 12 / 1000, 0) . 'K' : '0 HTG';

// 3. LOGIQUE DES ALERTES ET IMPAYÉS
$sql_impayes = "SELECT m.id, m.nom, m.prenom, m.email, m.telephone 
                FROM membres m 
                LEFT JOIN souscriptions s ON m.id = s.id_membre AND s.date_fin >= CURDATE()
                WHERE s.id IS NULL AND m.statut = 'actif'";
$impayes = $pdo->query($sql_impayes)->fetchAll();

$sql_alertes = "SELECT m.id, m.nom, m.prenom, m.email, s.date_fin 
                FROM membres m 
                JOIN souscriptions s ON m.id = s.id_membre 
                WHERE s.date_fin BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 5 DAY)
                AND m.statut = 'actif'";
$alertes = $pdo->query($sql_alertes)->fetchAll();

$nom_mois = [1=>"Janvier", 2=>"Février", 3=>"Mars", 4=>"Avril", 5=>"Mai", 6=>"Juin", 
             7=>"Juillet", 8=>"Août", 9=>"Septembre", 10=>"Octobre", 11=>"Novembre", 12=>"Décembre"];
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    body { background: url('assets/images/gymblanc.jpeg') no-repeat center center fixed; background-size: cover; color: #f8fafc; }
    body::before { content: ""; position: fixed; inset: 0; background: radial-gradient(circle at center, rgba(15, 23, 42, 0.4) 0%, rgba(2, 6, 23, 0.85) 100%); z-index: -1; }
    .dashboard-glass { background: rgba(15, 23, 42, 0.65) !important; backdrop-filter: blur(40px); border: 1px solid rgba(255, 255, 255, 0.08); border-radius: 24px; }
    .stat-value { font-family: 'Monaco', monospace; }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    main { background: transparent !important; }
</style>

<div id="main-content" class="container mx-auto px-4 py-8 animate__animated animate__fadeInUp">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-6">
        <div>
            <span class="text-teal-400 font-bold uppercase tracking-[0.3em] text-[10px]">Analyse de Performance</span>
            <h1 class="text-4xl font-black text-white mt-2 uppercase tracking-tighter">Rapport <span class="text-teal-400">Financier</span></h1>
            <?php if (isset($_GET['msg']) && $_GET['msg'] == 'success'): ?>
                <div class="bg-emerald-500/20 border border-emerald-500/50 text-emerald-400 p-4 rounded-xl mb-6 animate__animated animate__fadeIn">
                    ✅ Notification envoyée avec succès au membre.
                </div>
            <?php endif; ?>
        </div>
        <a href="export_finance.php" class="bg-white/10 hover:bg-teal-500 hover:text-slate-900 px-6 py-3 rounded-xl border border-white/20 uppercase font-black text-[10px] transition-all no-anim">Exporter CSV</a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-8 dashboard-glass p-8">
            <h3 class="text-sm font-black uppercase tracking-widest text-slate-400 mb-8 flex items-center shadow-xl">
                <span class="w-2 h-2 bg-teal-500 rounded-full mr-3 animate-pulse"></span> Revenus Mensuels
            </h3>
            <div class="space-y-4">
                <?php foreach($rapport_mois as $r): ?>
                <div class="flex items-center justify-between p-6 rounded-2xl bg-white/5 border border-white/5 hover:border-teal-500/30 transition-all">
                    <span class="text-xl font-bold text-slate-200"><?= $nom_mois[$r['mois']] ?></span>
                    <div class="stat-value text-2xl font-bold text-white"><?= number_format($r['total'], 0, '.', ' ') ?> <span class="text-[10px] text-teal-500/50">HTG</span></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="lg:col-span-4 space-y-6">
            <div class="bg-gradient-to-br from-teal-500 to-emerald-600 p-8 rounded-[30px] shadow-2xl">
                <p class="text-white/70 text-[10px] font-black uppercase tracking-widest">Projection Annuelle</p>
                <h4 class="text-4xl font-black text-white mt-2 stat-value italic"><?= $projection_format ?></h4>
            </div>

            <div class="dashboard-glass p-6 border-t-4 border-t-rose-500">
                <h3 class="text-xs font-black uppercase tracking-widest text-white mb-6 shadow-xl">Suivi des Membres</h3>
                <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                    
                    <?php foreach($impayes as $i): ?>
                    <div class="p-4 rounded-xl bg-red-500/20 border border-red-500/30 flex flex-col gap-2 mb-3">
                        <div class="flex justify-between items-start">
                            <p class="text-sm font-black text-white"><?= htmlspecialchars($i['nom'] . ' ' . $i['prenom']) ?></p>
                            <span class="text-[9px] font-black bg-red-600 text-white px-2 py-1 rounded-full uppercase shadow-lg shadow-red-500/50">Dette</span>
                        </div>
                        <div class="flex justify-between items-center text-[11px] mt-1">
                            <span class="text-slate-300 truncate mr-2"><?= htmlspecialchars($i['email']) ?></span>
                            <a href="envoyer_rappel.php?id=<?= $i['id'] ?>" class="text-white bg-teal-600 px-3 py-1 rounded-lg font-black hover:bg-white hover:text-teal-600 transition shrink-0 uppercase">Rappeler</a>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <?php foreach($alertes as $a): ?>
                    <div class="p-4 rounded-xl bg-amber-500/20 border border-amber-500/30 flex flex-col gap-2 mb-3">
                        <div class="flex justify-between items-start">
                            <p class="text-sm font-black text-white"><?= htmlspecialchars($a['nom'] . ' ' . $a['prenom']) ?></p>
                            <span class="text-[9px] font-black bg-amber-500 text-slate-900 px-2 py-1 rounded-full uppercase">Bientôt</span>
                        </div>
                        <p class="text-[10px] text-amber-200 font-bold italic">Expire le : <?= date('d/m', strtotime($a['date_fin'])) ?></p>
                        <div class="flex justify-between items-center text-[11px] mt-1">
                            <span class="text-slate-300 truncate mr-2"><?= htmlspecialchars($a['email']) ?></span>
                            <a href="envoyer_alerte.php?id=<?= $a['id'] ?>&date=<?= $a['date_fin'] ?>" class="text-white bg-amber-600 px-3 py-1 rounded-lg font-black hover:bg-white hover:text-amber-600 transition shrink-0 uppercase">ALERTER</a>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <?php if(empty($impayes) && empty($alertes)): ?>
                        <div class="py-10 text-center"><p class="text-xs text-slate-500 italic">Tout est à jour ! ✅</p></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php ?>