<?php 
include 'includes/header.php'; 
require_once 'config/db.php'; 

// Requête simplifiée et robuste
$sql = "SELECT p.date_presence, p.heure_entree, m.nom, m.prenom 
        FROM presences p 
        JOIN membres m ON p.id_membre = m.id 
        ORDER BY p.date_presence DESC, p.heure_entree DESC";

try {
    $stmt = $pdo->query($sql);
    $presences = $stmt->fetchAll();
} catch (PDOException $e) {
    echo "<div class='bg-red-500 text-white p-4 rounded-xl'>Erreur SQL : " . $e->getMessage() . "</div>";
    $presences = [];
}
?>

<<<<<<< HEAD
<div class="space-y-8">
    <div class="flex justify-between items-center">
        <h2 class="text-3xl font-black text-slate-800 uppercase tracking-tighter">Historique des entrées</h2>
        <div class="bg-teal-100 text-teal-700 px-4 py-2 rounded-full font-bold text-xs uppercase">
=======
<style>
    /* Configuration du background avec l'image demandée */
    body {
        margin: 0;
        background-image: url('assets/images/gymgris.jpeg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
    }

    /* Overlay pour assombrir légèrement et flouter l'image de fond derrière les éléments */
    body::before {
        content: "";
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.4); 
        z-index: -1;
    }

    /* Effet Glassmorphism dédié uniquement au titre */
    .glass-title-box {
        background: rgba(255, 255, 255, 0.1) !important;
        backdrop-filter: blur(15px) saturate(150%);
        -webkit-backdrop-filter: blur(15px) saturate(150%);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 20px 40px;
        border-radius: 25px;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
    }

    /* Couleur vive pour le titre sur le verre */
    .glass-title-box h2 {
        color: #2dd4bf !important; /* Ton Teal vif */
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }
</style>

<div class="space-y-8 relative z-10 p-6">
    
    <div class="flex justify-between items-center">
        <div class="glass-title-box">
            <h2 class="text-3xl font-black uppercase tracking-tighter">Historique des entrées</h2>
        </div>

        <div class="bg-teal-500/80 backdrop-blur-md text-white px-6 py-3 rounded-full font-bold text-xs uppercase shadow-lg">
>>>>>>> 7e8a91057e02ca00628d58e63ac6c9f2945e29bc
            Contrôle d'accès actif
        </div>
    </div>

<<<<<<< HEAD
    <div class="bg-white rounded-[40px] shadow-sm border border-slate-100 overflow-hidden">
        <table class="min-w-full">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-left text-xs uppercase font-bold tracking-widest">
=======
    <div class="bg-white rounded-[40px] shadow-2xl border border-slate-100 overflow-hidden">
        <table class="min-w-full">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-left text-xs uppercase font-bold tracking-widest shadow-xl">
>>>>>>> 7e8a91057e02ca00628d58e63ac6c9f2945e29bc
                    <th class="px-6 py-5">Membre</th>
                    <th class="px-6 py-5">Date de visite</th>
                    <th class="px-6 py-5">Heure d'entrée</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach($presences as $p): ?>
<<<<<<< HEAD
                <tr class="hover:bg-teal-50/30 transition">
=======
                <tr class="hover:bg-teal-100/70 transition-colors duration-700 ease-in-out">
>>>>>>> 7e8a91057e02ca00628d58e63ac6c9f2945e29bc
                    <td class="px-6 py-4 font-bold text-slate-800">
                        <?= htmlspecialchars($p['nom'] . " " . $p['prenom']) ?>
                    </td>
                    <td class="px-6 py-4 text-slate-600">
                        <?= date('d/m/Y', strtotime($p['date_presence'])) ?>
                    </td>
                    <td class="px-6 py-4">
<<<<<<< HEAD
                        <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-lg font-mono text-sm">
=======
                        <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-lg font-mono text-sm border border-slate-200">
>>>>>>> 7e8a91057e02ca00628d58e63ac6c9f2945e29bc
                            <?= $p['heure_entree'] ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; ?>

                <?php if (empty($presences)): ?>
                <tr>
                    <td colspan="3" class="px-6 py-10 text-center text-slate-400 italic">
                        Aucune entrée enregistrée pour le moment.
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>