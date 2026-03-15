<?php 
include 'includes/header.php'; 
require_once 'config/db.php'; 

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

<style>
    body {
        margin: 0;
        background-image: url('assets/images/gymgris.jpeg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
    }

    body::before {
        content: "";
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(15, 23, 42, 0.4); 
        z-index: -1;
    }

    .glass-title-box {
        background: rgba(255, 255, 255, 0.1) !important;
        backdrop-filter: blur(15px) saturate(150%);
        -webkit-backdrop-filter: blur(15px) saturate(150%);
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 20px 40px;
        border-radius: 25px;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
    }

    .glass-title-box h2 {
        color: #2dd4bf !important; 
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }

    /* --- OPTIMISATION RESPONSIVE MINIMALE POUR TABLEAU --- */
    @media (max-width: 768px) {
        .responsive-table-container { border-radius: 20px !important; }
        
        table, thead, tbody, th, td, tr { display: block; }
        thead tr { position: absolute; top: -9999px; left: -9999px; }
        
        tr { border-bottom: 2px solid #f1f5f9; padding: 10px 0; }
        
        td {
            border: none;
            position: relative;
            padding-left: 50% !important;
            text-align: right !important;
            padding-right: 20px !important;
        }
        
        td:before {
            position: absolute;
            left: 20px;
            width: 45%;
            padding-right: 10px;
            white-space: nowrap;
            text-align: left;
            font-weight: bold;
            color: #94a3b8;
            text-transform: uppercase;
            font-size: 10px;
        }
        
        /* Libellés des colonnes sur mobile */
        td:nth-of-type(1):before { content: "Membre"; }
        td:nth-of-type(2):before { content: "Date"; }
        td:nth-of-type(3):before { content: "Heure"; }
        
        td:nth-of-type(1) { text-align: right !important; font-size: 14px; }
    }
</style>

<div class="space-y-8 relative z-10 p-4 md:p-6">
    
    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
        <div class="glass-title-box w-full md:w-auto text-center md:text-left">
            <h2 class="text-2xl md:text-3xl font-black uppercase tracking-tighter">Historique des entrées</h2>
        </div>

        <div class="bg-teal-500/80 backdrop-blur-md text-white px-6 py-3 rounded-full font-bold text-[10px] md:text-xs uppercase shadow-lg border border-teal-400/50">
            Contrôle d'accès actif
        </div>
    </div>

    <div class="responsive-table-container bg-white rounded-[30px] md:rounded-[40px] shadow-2xl border border-slate-100 overflow-hidden">
        <table class="min-w-full">
            <thead class="hidden md:table-header-group">
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-left text-xs uppercase font-bold tracking-widest shadow-sm">
                    <th class="px-6 py-5">Membre</th>
                    <th class="px-6 py-5">Date de visite</th>
                    <th class="px-6 py-5">Heure d'entrée</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                <?php foreach($presences as $p): ?>
                <tr class="hover:bg-teal-50 transition-colors duration-300">
                    <td class="px-6 py-4 font-bold text-slate-800">
                        <?= htmlspecialchars($p['nom'] . " " . $p['prenom']) ?>
                    </td>
                    <td class="px-6 py-4 text-slate-600 font-medium">
                        <?= date('d/m/Y', strtotime($p['date_presence'])) ?>
                    </td>
                    <td class="px-6 py-4">
                        <span class="bg-slate-100 text-slate-600 px-3 py-1 rounded-lg font-mono text-sm border border-slate-200">
                            <?= date('H:i', strtotime($p['heure_entree'])) ?>
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