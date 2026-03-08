<?php 
require_once 'config/db.php'; 
include 'includes/header.php'; 

// Requête pour lier les membres, leurs abonnements et leurs dates
$sql = "SELECT s.*, m.nom, m.prenom, a.libelle 
        FROM souscriptions s
        JOIN membres m ON s.id_membre = m.id
        JOIN abonnements a ON s.id_abonnement = a.id
        ORDER BY s.date_fin DESC";
$query = $pdo->query($sql);
$souscriptions = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    body {
        margin: 0;
        background: url('assets/images/gymteal.jpeg') no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
    }

    /* Effet Glassmorphism réutilisable */
    .glass-effect {
        background: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(15px) saturate(150%);
        -webkit-backdrop-filter: blur(15px) saturate(150%);
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.2);
    }

    /* Spécifique pour le bouton Encaisser pour garder le ton Teal */
    .glass-teal {
        background: rgba(20, 184, 166, 0.25) !important;
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
    }

    .table-container {
        background: rgba(255, 255, 255, 0.95); /* Fond presque opaque pour la lisibilité des données */
        border-radius: 30px;
    }

    /* Force l'effet sur le bouton Nouvelle Inscription */
a[href="ajouter_souscription.php"]:hover {
    background: rgba(14, 235, 239, 0.4) !important;
    color: #ffffff !important; /* Couleur sombre pour le contraste */
    transform: translateY(-4px) !important;
}

a[href="encaisser.php"]:hover {
    background: rgba(14, 235, 239, 0.4) !important;
    color: #ffffff !important; /* Couleur sombre pour le contraste */
    transform: translateY(-4px) !important;
}
</style>

<div class="container mx-auto px-4 py-8 relative z-10">
    
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
        <div class="glass-effect p-6 rounded-[25px] flex-1">
            <h1 class="text-3xl font-black text-teal-300 tracking-tighter uppercase italic">Suivi des Adhésions</h1>
            <p class="text-white text-sm opacity-80">Gestion des abonnements en temps réel</p>
        </div>
        
        <a href="ajouter_souscription.php" 
   class="glass-effect text-teal-300 hover:!bg-white/40 hover:!text-teal-900 px-8 py-5 rounded-[25px] font-black uppercase tracking-widest text-xs transition-all duration-300 transform hover:-translate-y-1 flex items-center shadow-2xl cursor-pointer relative z-20">
    <span class="text-xl text-teal-300 mr-2 "><img src="assets/images/add.png" class="w-6 h-6"></span> Nouvelle Inscription
</a>
    </div>

    <div class="table-container shadow-2xl overflow-hidden border border-white/20">
        <table class="min-w-full">
            <thead class="bg-slate-50/50 border-b">
                <tr class="text-left text-xs font-bold text-slate-400 uppercase tracking-widest shadow-xl">
                    <th class="px-6 py-5">Membre</th>
                    <th class="px-6 py-5">Forfait</th>
                    <th class="px-6 py-5">Validité</th>
                    <th class="px-6 py-5">Statut</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($souscriptions as $s): ?>
                 <tr class="hover:bg-teal-100/70 transition-colors duration-700 ease-in-out">
                    <td class="px-6 py-5 font-bold text-slate-800"><?= htmlspecialchars($s['nom'] . ' ' . $s['prenom']) ?></td>
                    <td class="px-6 py-5 text-slate-600 font-medium"><?= htmlspecialchars($s['libelle']) ?></td>
                    <td class="px-6 py-5 text-sm text-slate-500">
                        Du <?= date('d/m/Y', strtotime($s['date_debut'])) ?> 
                        au <span class="font-bold text-teal-600"><?= date('d/m/Y', strtotime($s['date_fin'])) ?></span>
                    </td>
                    <td class="px-6 py-5">
                        <?php 
                        $expire = (strtotime($s['date_fin']) < time());
                        $classe = $expire ? 'bg-rose-100 text-rose-600' : 'bg-teal-100 text-teal-600';
                        $label = $expire ? 'Expiré' : 'Actif';
                        ?>
                        <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-tighter <?= $classe ?>">
                            <?= $label ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-10 max-w-md">
        <a href="encaisser.php" class="glass-teal block p-8 rounded-[30px] transition transform hover:-translate-y-2 hover:shadow-teal-500/40 shadow-xl group">
            <div class="flex items-center gap-6">
                <div class=" p-4 rounded-2xl group-hover:bg-teal-100 scale-110 transition">
                    <img src="assets/images/bagmoney.png" class="w-8 h-8 object-contain">
                </div>
                <div>
                    <p class="font-black text-white uppercase tracking-tighter text-xl">Encaisser un Paiement</p>
                    <p class="text-white text-xs opacity-80">Enregistrer une cotisation ou un frais</p>
                </div>
            </div>
        </a>
    </div>
    
</div>

</body>
</html>