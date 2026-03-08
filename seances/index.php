<?php 
require_once '../config/db.php'; 

try {
    $sql = "SELECT s.id, s.titre, s.date_seance, s.heure_debut, s.heure_fin, c.nom as coach_nom 
            FROM seances s 
            LEFT JOIN coaches c ON s.coach_id = c.id 
            ORDER BY s.date_seance DESC, s.heure_debut DESC";
    $stmt = $pdo->query($sql);
    $seances = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Programmer Séance - Pro Edition</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Outfit', sans-serif; background: #020617; min-height: 100vh; color: white; margin: 0; }
        .bg-fixed-img { 
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
            background: linear-gradient(rgba(2, 6, 23, 0.85), rgba(2, 6, 23, 0.95)), 
                        url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=2070'); 
            background-size: cover; background-position: center; z-index: -1; 
        }

        /* Card Professional */
        .glass-card { 
            background: rgba(255, 255, 255, 0.01); backdrop-filter: blur(25px); 
            border: 1px solid rgba(255, 255, 255, 0.05); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card:hover { 
            background: rgba(255, 255, 255, 0.03); 
            border-color: rgba(20, 184, 166, 0.3);
            transform: translateY(-5px);
        }

        /* Bouton Classic/Pro */
        .btn-pro {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #94a3b8;
            transition: all 0.3s ease;
            display: flex; align-items: center; justify-content: center;
        }
        .btn-pro:hover {
            color: white;
            background: rgba(20, 184, 166, 0.1);
            border-color: #14b8a6;
            box-shadow: 0 0 20px rgba(20, 184, 166, 0.15);
        }
        .btn-delete-pro:hover {
            background: rgba(225, 29, 72, 0.1);
            border-color: #e11d48;
            box-shadow: 0 0 20px rgba(225, 29, 72, 0.15);
        }

        /* Bouton Ajouter Elegant */
        .btn-add-elite {
            background: #14b8a6;
            box-shadow: 0 4px 15px rgba(20, 184, 166, 0.3);
            transition: all 0.3s ease;
        }
        .btn-add-elite:hover {
            background: #0d9488;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(20, 184, 166, 0.4);
        }

        #editModal { transition: all 0.4s ease; display: none; }
    </style>
</head>
<body class="p-6 md:p-12">
    <div class="bg-fixed-img"></div>

    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-16 gap-6">
            <div>
                <h1 class="text-4xl font-extrabold tracking-tighter uppercase italic leading-none">
                    SEANCE <span class="text-teal-500">PROGRAMMER</span>
                </h1>
                <div class="h-1 w-12 bg-teal-500 mt-2 rounded-full"></div>
            </div>
            <a href="ajouter.php" class="btn-add-elite text-white text-[11px] font-black py-4 px-10 rounded-xl uppercase tracking-[0.2em]">
                Nouvelle Séance
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-10">
            <?php foreach($seances as $seance): 
                $row = array_change_key_case($seance, CASE_LOWER);
            ?>
                <div class="glass-card rounded-[2.5rem] p-8 relative overflow-hidden group">
                    <div class="absolute right-0 top-0 w-24 h-24 bg-teal-500/5 blur-3xl rounded-full -mr-12 -mt-12"></div>
                    
                    <div class="flex justify-between items-center mb-8">
                        <span class="text-[9px] font-bold text-teal-500 uppercase tracking-[0.2em] border border-teal-500/30 px-4 py-1.5 rounded-lg bg-teal-500/5">
                            <?= date('d M Y', strtotime($row['date_seance'])) ?>
                        </span>
                        
                        <div class="flex gap-2.5">
                            <button onclick="openEditModal({
                                id: '<?= $row['id'] ?>',
                                titre: '<?= addslashes($row['titre']) ?>',
                                date: '<?= $row['date_seance'] ?>',
                                debut: '<?= $row['heure_debut'] ?>',
                                fin: '<?= $row['heure_fin'] ?>'
                            })" class="btn-pro w-9 h-9 rounded-xl" title="Modifier">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" stroke-width="2"></path></svg>
                            </button>
                            <a href="supprimer_seance.php?id=<?= $row['id'] ?>" onclick="return confirm('Confirmer la suppression ?')" class="btn-pro btn-delete-pro w-9 h-9 rounded-xl" title="Supprimer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" stroke-width="2"></path></svg>
                            </a>
                        </div>
                    </div>

                    <h2 class="text-2xl font-black text-white uppercase tracking-tight mb-2 group-hover:text-teal-400 transition-colors"><?= htmlspecialchars($row['titre']) ?></h2>
                    <p class="text-xs text-slate-500 font-medium mb-8">Coach: <span class="text-slate-200"><?= htmlspecialchars($row['coach_nom'] ?? '---') ?></span></p>

                    <div class="bg-white/[0.02] rounded-2xl p-5 border border-white/5 flex items-center justify-between">
                        <div>
                            <p class="text-[8px] text-slate-500 uppercase font-black mb-1">Session Début</p>
                            <p class="text-lg font-bold text-white"><?= date('H:i', strtotime($row['heure_debut'])) ?></p>
                        </div>
                        <div class="w-px h-10 bg-gradient-to-b from-transparent via-white/10 to-transparent"></div>
                        <div class="text-right">
                            <p class="text-[8px] text-slate-500 uppercase font-black mb-1">Heure Fin</p>
                            <p class="text-lg font-bold text-teal-500"><?= $row['heure_fin'] ? date('H:i', strtotime($row['heure_fin'])) : '--:--' ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div id="editModal" class="fixed inset-0 bg-black/90 backdrop-blur-2xl z-50 flex items-center justify-center p-6">
        <div class="glass-card w-full max-w-md rounded-[3rem] p-12 border-white/10 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-teal-500"></div>
            <h2 class="text-2xl font-black uppercase italic mb-10 text-white tracking-widest">Édition <span class="text-teal-500 italic">Sélective</span></h2>
            
            <form action="process_edit.php" method="POST" class="space-y-6">
                <input type="hidden" name="id" id="edit_id">
                
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-teal-500 uppercase tracking-[0.3em] ml-1">Désignation</label>
                    <input type="text" name="titre" id="edit_titre" class="w-full bg-white/5 border border-white/10 p-4 rounded-xl text-white focus:border-teal-500 outline-none transition-all font-semibold">
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-teal-500 uppercase tracking-[0.3em] ml-1">Calendrier</label>
                    <input type="date" name="date_seance" id="edit_date" class="w-full bg-white/5 border border-white/10 p-4 rounded-xl text-white focus:border-teal-500 outline-none">
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-teal-500 uppercase tracking-[0.3em] ml-1">Début</label>
                        <input type="time" name="heure_debut" id="edit_debut" class="w-full bg-white/5 border border-white/10 p-4 rounded-xl text-white focus:border-teal-500 outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-teal-500 uppercase tracking-[0.3em] ml-1">Terminus</label>
                        <input type="time" name="heure_fin" id="edit_fin" class="w-full bg-white/5 border border-white/10 p-4 rounded-xl text-white focus:border-teal-500 outline-none">
                    </div>
                </div>

                <div class="flex flex-col gap-4 pt-8">
                    <button type="submit" class="w-full btn-add-elite p-4 rounded-xl font-black uppercase text-[10px] tracking-[0.2em] text-white">Mettre à jour</button>
                    <button type="button" onclick="closeModal()" class="w-full text-slate-500 font-bold uppercase text-[9px] tracking-widest hover:text-white transition-colors">Abandonner</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openEditModal(data) {
        document.getElementById('edit_id').value = data.id;
        document.getElementById('edit_titre').value = data.titre;
        document.getElementById('edit_date').value = data.date;
        document.getElementById('edit_debut').value = data.debut;
        document.getElementById('edit_fin').value = data.fin;
        document.getElementById('editModal').style.display = 'flex';
    }
    function closeModal() { document.getElementById('editModal').style.display = 'none'; }
    window.onclick = function(e) { if (e.target == document.getElementById('editModal')) closeModal(); }
    </script>
</body>
</html>