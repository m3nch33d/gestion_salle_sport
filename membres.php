<?php 
require_once 'includes/securite.php';
include 'includes/header.php'; 
require_once 'config/db.php'; 

$query = $pdo->query("SELECT * FROM membres ORDER BY id DESC");
$membres = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
    body { margin: 0; overflow-x: hidden; font-family: 'Inter', sans-serif; }
    #video-bg { position: fixed; right: 0; bottom: 0; min-width: 100%; min-height: 100%; z-index: -2; object-fit: cover; filter: brightness(0.4); }
    .video-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.3); z-index: -1; }
    
    .glass-header { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.1); }
    .glass-btn { background: rgba(45, 212, 191, 0.2); backdrop-filter: blur(10px); border: 1px solid rgba(45, 212, 191, 0.3); transition: all 0.3s ease; }
    .glass-btn:hover { background: rgba(45, 212, 191, 0.5); transform: translateY(-2px); }

    @media (max-width: 768px) {
        table, thead, tbody, th, td, tr { display: block; }
        thead tr { position: absolute; top: -9999px; left: -9999px; }
        tr { border: 1px solid rgba(255,255,255,0.1); background: white; border-radius: 20px; margin-bottom: 1.5rem; padding: 1rem; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); }
        td { border: none; position: relative; padding-left: 40% !important; text-align: left !important; min-height: 40px; }
        td:before { position: absolute; left: 1rem; width: 35%; font-weight: bold; font-size: 10px; color: #94a3b8; text-transform: uppercase; }
        td:nth-of-type(1) { padding-left: 0 !important; margin-bottom: 1rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1rem; }
        td:nth-of-type(1):before { content: ""; }
        td:nth-of-type(2):before { content: "📞 TEL"; }
        td:nth-of-type(3):before { content: "⚡ STATUT"; }
        td:nth-of-type(4) { text-align: center !important; padding-left: 0 !important; margin-top: 1rem; border-top: 1px solid #f1f5f9; padding-top: 1rem; }
        td:nth-of-type(4):before { content: ""; }
    }
</style>

<video autoplay muted loop playsinline id="video-bg">
    <source src="assets/videos/peoplegym.mov" type="video/mp4">
</video>
<div class="video-overlay"></div>

<div id="main-content" class="container mx-auto space-y-6 md:space-y-8 p-4 md:p-8 animate__animated animate__fadeInUp">
    
    <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
        <div class="p-6 md:p-8 rounded-[24px] md:rounded-[35px] glass-header w-full lg:max-w-2xl shadow-2xl">
            <h2 class="text-2xl md:text-4xl font-black text-teal-400 uppercase tracking-tighter">Gestion des Adhérents</h2>
            <p class="font-medium text-slate-300 text-sm md:text-base mt-1">Écosystème des membres Dechouke Grès</p>
        </div> 

        <a href="ajouter_membre.php" class="glass-btn w-full lg:w-auto px-8 py-5 md:py-4 rounded-[20px] md:rounded-[25px] font-black text-white flex items-center justify-center shadow-xl">
            <img src="assets/images/add.png" class="w-6 h-6 mr-3">
            Ajouter un membre
        </a>
    </div>

    <div class="md:bg-white md:rounded-[40px] shadow-2xl overflow-hidden border border-white/10">
        <table class="min-w-full">
            <thead class="hidden md:table-header-group">
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-left text-[10px] uppercase font-black tracking-[0.2em]">
                    <th class="px-8 py-6">Adhérent</th>
                    <th class="px-8 py-6">Contact</th>
                    <th class="px-8 py-6">État</th>
                    <th class="px-8 py-6 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($membres as $m): ?>
                    <tr class="hover:bg-teal-50 transition-all duration-300 group">
                        <td class="px-4 md:px-8 py-4 md:py-6">
                            <div class="flex items-center">
                                <div class="h-10 w-10 md:h-12 md:w-12 rounded-full overflow-hidden flex items-center justify-center border-2 border-teal-500/20 shadow-lg bg-teal-500">
                                    <?php 
                                    $path = "assets/uploads/" . ($m['photo'] ?? 'default.png');
                                    if(!empty($m['photo']) && file_exists($path) && $m['photo'] != 'default.png'): ?>
                                        <img src="<?= $path ?>" class="w-full h-full object-cover">
                                    <?php else: ?>
                                        <span class="text-white font-black"><?= strtoupper(substr($m['nom'] ?? '?', 0, 1)) ?></span>
                                    <?php endif; ?>
                                </div>
                                <div class="ml-4">
                                    <p class="text-slate-900 font-black text-sm md:text-lg uppercase leading-tight"><?= htmlspecialchars($m['nom'] . ' ' . $m['prenom']) ?></p>
                                    <p class="text-slate-400 text-[10px] md:text-xs italic truncate max-w-[150px] md:max-w-none"><?= htmlspecialchars($m['email']) ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 md:px-8 py-4 md:py-6 text-sm font-bold text-slate-600">
                            <?= htmlspecialchars($m['telephone']) ?>
                        </td>
                        <td class="px-4 md:px-8 py-4 md:py-6">
                            <span class="px-4 py-1 text-[10px] font-black uppercase rounded-full <?= $m['statut'] == 'actif' ? 'text-teal-700 bg-teal-100' : 'text-rose-700 bg-rose-100' ?>">
                                <?= $m['statut'] ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3 md:gap-4">
                                <a href="modifier_membre.php?id=<?= $m['id'] ?>" class="text-orange-500 font-black text-[10px] uppercase hover:text-orange-700 transition">Éditer</a>
                                <a href="carte.php?id=<?= $m['id'] ?>" class="text-teal-600 font-black text-[10px] uppercase hover:text-teal-800 transition">Carte</a>
                                <button onclick="confirmerSuppression(<?= $m['id'] ?>, '<?= htmlspecialchars(addslashes($m['nom'])) ?>')" class="text-rose-500 font-black text-[10px] uppercase hover:text-rose-700 transition">Supprimer</button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div> 

    <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8">
        <a href="presences.php" class="glass-btn text-white px-8 py-5 rounded-[20px] font-black shadow-lg flex items-center justify-center">
            <img src="assets/images/souscriptions.png" class="w-6 h-6 mr-3">
            Liste des présences
        </a>
    </div>
</div> 

<script>
function confirmerSuppression(id, nomComplet) {
    if (confirm("Voulez-vous vraiment supprimer " + nomComplet + " ? Cette action est irréversible.")) {
        const container = document.getElementById('main-content');
        container.classList.remove('animate__fadeInUp');
        container.classList.add('animate__fadeOutDown');
        setTimeout(() => {
            window.location.href = "supprimer_membre.php?id=" + id;
        }, 500);
    }
}

document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById('main-content');
    const links = document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not([onclick])');

    links.forEach(link => {
        link.addEventListener('click', function(e) {
            if (link.hostname === window.location.hostname) {
                e.preventDefault();
                const dest = this.href;
                container.classList.remove('animate__fadeInUp');
                container.classList.add('animate__fadeOutDown');
                setTimeout(() => { window.location.href = dest; }, 500);
            }
        });
    });
});
</script>