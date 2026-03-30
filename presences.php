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

<meta name="viewport" content="width=device-width, initial-scale=1.0">

<style>
    body {
        margin: 0;
        background-image: url('assets/images/gymgris.jpeg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
        overflow-x: hidden;
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

    /* RESPONSIVE TABLE CONTAINER */
    .table-responsive-wrapper {
        background: white;
        border-radius: 30px;
        overflow: hidden; /* Pou border-radius la mache sou tablo a */
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .scrollable-table {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        min-width: 500px; /* Garanti tablo a pa parèt twò kwense sou mobil */
    }

    @media (max-width: 768px) {
        .glass-title-box {
            padding: 15px 20px;
        }
        .glass-title-box h2 {
            font-size: 1.25rem !important;
        }
    }
</style>

<div class="space-y-8 relative z-10 p-4 md:p-8">
    
    <div class="flex flex-col md:flex-row justify-between items-center gap-6">
        <div class="glass-title-box w-full md:w-auto text-center md:text-left">
            <h2 class="text-2xl md:text-3xl font-black uppercase tracking-tighter">Historique des entrées</h2>
        </div>

        <div class="bg-teal-500/80 backdrop-blur-md text-white px-6 py-3 rounded-full font-bold text-[10px] md:text-xs uppercase shadow-lg border border-teal-400/50">
            Contrôle d'accès actif
        </div>
    </div>

    <div class="table-responsive-wrapper">
        <div class="scrollable-table">
            <table>
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-slate-400 text-left text-[10px] md:text-xs uppercase font-bold tracking-widest shadow-xl">
                        <th class="px-6 py-5">Membre</th>
                        <th class="px-6 py-5">Date de visite</th>
                        <th class="px-6 py-5">Heure d'entrée</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    <?php foreach($presences as $p): ?>
                    <tr class="hover:bg-teal-50 transition-colors duration-300">
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-800 uppercase text-sm md:text-base">
                                <?= htmlspecialchars($p['nom'] . " " . $p['prenom']) ?>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-slate-600 font-medium text-sm">
                            <?= date('d/m/Y', strtotime($p['date_presence'])) ?>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-slate-100 text-teal-600 px-3 py-1 rounded-lg font-mono font-bold text-sm border border-slate-200">
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
</div>

</main>
</body>
</html>