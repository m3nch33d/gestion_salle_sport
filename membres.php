<?php 
require_once 'includes/securite.php';
// 1. Inclure le header (qui gère déjà la session, la sécurité et la sidebar)
include 'includes/header.php'; 

// 2. Connexion à la base de données
require_once 'config/db.php'; 

// 3. Récupération des membres
$query = $pdo->query("SELECT * FROM membres ORDER BY id DESC");
$membres = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    /* Fond vidéo plein écran */
    body {
        margin: 0;
        overflow-x: hidden;
    }

    #video-bg {
        position: fixed;
        right: 0;
        bottom: 0;
        min-width: 100%;
        min-height: 100%;
        z-index: -2;
        object-fit: cover;
        filter: brightness(0.5); 
    }

    /* Overlay pour lisser le rendu */
    .video-overlay {
        position: fixed;
        top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0, 0, 0, 0.2);
        z-index: -1;
    }

    /* CLASSE GLASSMORPHISM */
    .glass-effect {
        background: rgba(255, 255, 255, 0.1) !important;
        backdrop-filter: blur(15px) saturate(150%);
        -webkit-backdrop-filter: blur(15px) saturate(150%);
        border: 1px solid rgba(255, 255, 255, 0.2) !important;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
    }

    /* Ajustement pour les textes sur le glassmorphism */
    .glass-text-container h2 { 
        color: #2dd4bf !important; 
        text-shadow: 0 0 15px rgba(45, 212, 191, 0.4); 
        font-weight: 900; 
        letter-spacing: -0.02em; 
    }
    .glass-text-container p { color: rgba(255, 255, 255, 0.7) !important; }

    /* Bouton d'ajout style Verre/Teal */
    .btn-add-glass {
        background: rgba(20, 184, 166, 0.4) !important;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        color: white !important;
    }
    .btn-add-glass:hover {
        background: rgba(58, 233, 212, 0.6) !important;
        transform: scale(1.05);
    }
</style>

<video autoplay muted loop playsinline id="video-bg">
    <source src="assets/videos/peoplegym.mov" type="video/mp4">
</video>
<div class="video-overlay"></div>

<div class="space-y-8 relative z-10 p-6">
    
    <div class="flex justify-between items-center">
        <div class="p-6 rounded-[30px] glass-effect glass-text-container w-full max-w-xl mr-4">
            <h2 class="text-3xl font-black text-slate-800 tracking-tight uppercase">Gestion des Adhérents</h2>
            <p class="font-medium">Liste complète des membres de la salle</p>
        </div> 

        <a href="ajouter_membre.php" class="btn-add-glass px-8 py-4 rounded-[25px] font-black transition shadow-lg flex items-center transform">
            <span class="mr-3"> <img src="assets/images/add.png" class="w-8 h-8"> </span> 
            Ajouter un membre
        </a>
    </div>

    <div class="bg-white rounded-[40px] shadow-2xl border border-slate-100 overflow-hidden">
        <table class="min-w-full">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-left text-xs uppercase font-bold tracking-widest shadow-xl">
                    <th class="px-6 py-5">Membre</th>
                    <th class="px-6 py-5">Téléphone</th>
                    <th class="px-6 py-5">Statut</th>
                    <th class="px-6 py-5 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach ($membres as $m): ?>
                    <tr class="hover:bg-teal-100/70 transition-colors duration-700 ease-in-out">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-full bg-slate-200 flex items-center justify-center text-slate-700 font-bold">
                                    <?= strtoupper(substr($m['nom'] ?? '?', 0, 1)) ?>
                                </div>
                                <div class="ml-4">
                                    <p class="text-slate-900 font-bold"><?= htmlspecialchars($m['nom'] . ' ' . $m['prenom']) ?></p>
                                    <p class="text-slate-400 text-xs italic"><?= htmlspecialchars($m['email']) ?></p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 font-medium"><?= htmlspecialchars($m['telephone']) ?></td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full <?= $m['statut'] == 'actif' ? 'text-teal-700 bg-teal-100' : 'text-red-700 bg-red-100' ?>">
                                <?= ucfirst($m['statut']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right space-x-3 text-xs font-bold">
                            <a href="modifier_membre.php?id=<?= $m['id'] ?>" class="text-orange-500 hover:underline">Modifier</a>
                            <a href="carte.php?id=<?= $m['id'] ?>" class="text-teal-600 hover:underline">Carte</a>
                            <a href="#" onclick="confirmerSuppression(<?= $m['id'] ?>, '<?= htmlspecialchars(addslashes($m['nom'])) ?>')" class="text-red-500 hover:underline">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div> 

    <div class="flex justify-end mt-8">
        <a href="presences.php" class="glass-effect text-white px-8 py-4 rounded-[25px] font-black transition shadow-lg flex items-center transform hover:scale-105">
            <span class="mr-3"><img src="assets/images/souscriptions.png" class="w-8 h-8"></span> 
            Liste des présences
        </a>
    </div>
</div> 

</main>

<script>
function confirmerSuppression(id, nomComplet) {
    if (confirm("Êtes-vous sûr de vouloir supprimer \"" + nomComplet + "\" ?")) {
        window.location.href = "supprimer_membre.php?id=" + id;
    }
}
</script>
</body>
</html>