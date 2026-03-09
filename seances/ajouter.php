<?php 
require_once '../config/db.php'; 

try {
    $query_coaches = $pdo->query("SELECT id, nom FROM coaches ORDER BY nom ASC");
    $coaches = $query_coaches->fetchAll();
} catch (PDOException $e) {
    $coaches = [];
}

$activites_list = ["Boxe", "Crossfit", "Musculation", "Yoga", "Zumba"];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elite Planning - Ajouter Séance</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { 
            font-family: 'Outfit', sans-serif; 
            background: #020617; 
            min-height: 100vh; 
            margin: 0;
            padding: 0; /* Retire padding pou l moute pi wo */
        }
        
        .bg-overlay { 
            position: fixed; top: 0; left: 0; width: 100%; height: 100%; 
            background: linear-gradient(rgba(2, 6, 23, 0.85), rgba(2, 6, 23, 0.95)), 
                        url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=2070'); 
            background-size: cover; background-position: center; z-index: -1; 
        }

        .glass-container { 
            background: rgba(15, 23, 42, 0.7); 
            backdrop-filter: blur(40px); 
            border: 1px solid rgba(255, 255, 255, 0.1); 
            box-shadow: 0 50px 100px -20px rgba(0, 0, 0, 0.8);
            margin-top: 0; /* Kole l anlè nèt */
        }

        .side-branding {
            background: linear-gradient(to bottom, rgba(15, 23, 42, 0.1), rgba(15, 23, 42, 0.9)), 
                        url('https://images.unsplash.com/photo-1517836357463-d25dfeac3438?q=80&w=2070');
            background-size: cover;
            background-position: center;
        }

        .input-pro { 
            background: rgba(255, 255, 255, 0.04); 
            border: 1px solid rgba(255, 255, 255, 0.1); 
            color: white; 
            transition: all 0.3s ease;
        }
        .input-pro:focus { border-color: #14b8a6; background: rgba(255, 255, 255, 0.08); outline: none; }

        /* BOUTON 3D REALISTIC */
        .btn-real-3d {
            position: relative;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 1.25rem 2rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            font-size: 10px;
            border-radius: 16px;
            transition: all 0.1s ease;
            cursor: pointer;
        }

        .btn-confirm-3d {
            background: linear-gradient(145deg, #14b8a6, #0d9488);
            color: white;
            box-shadow: 0 6px 0 #0a6c63, 0 12px 25px rgba(20, 184, 166, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .btn-confirm-3d:active { transform: translateY(4px); box-shadow: 0 2px 0 #0a6c63; }

        .btn-cancel-3d {
            background: rgba(255, 255, 255, 0.05);
            color: #94a3b8;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .btn-cancel-3d:hover { color: white; background: rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body>
    <div class="bg-overlay"></div>

    <div class="max-w-6xl mx-auto glass-container overflow-hidden flex flex-col md:flex-row min-h-[600px] border-b border-white/5">
        
        <div class="md:w-5/12 side-branding p-12 flex flex-col justify-end relative min-h-[300px]">
            <div class="relative z-10">
                <div class="h-14 w-14 bg-teal-500 rounded-2xl flex items-center justify-center mb-6 shadow-xl shadow-teal-500/30">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                </div>
                <h2 class="text-5xl font-black text-white uppercase italic tracking-tighter leading-none">
                    PLANIFIER <br><span class="text-teal-500 text-6xl">SÉANCE</span>
                </h2>
                <p class="mt-4 text-slate-300 text-xs font-bold uppercase tracking-widest opacity-70">Elite Administration </p>
            </div>
        </div>

        <div class="md:w-7/12 p-12 flex flex-col justify-center bg-black/20">
            <form action="process_seance.php" method="POST" class="space-y-8">
                
                <div class="space-y-2">
                    <label class="block text-[20px] font-black text-teal-500 uppercase tracking-[0.3em] ml-1">Discipline</label>
                    <select name="titre" required class="w-full input-pro rounded-2xl px-6 py-2.5 font-bold text-sm appearance-none cursor-pointer shadow-inner">
                        <option value="" disabled selected>Choisir une activité...</option>
                        <?php foreach($activites_list as $act): ?>
                            <option value="<?= $act ?>" class="bg-slate-900 text-white"><?= $act ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="block text-[20px] font-black text-teal-500 uppercase tracking-[0.3em] ml-1">Coach Expert</label>
                    <select name="coach_id" required class="w-full input-pro rounded-2xl px-6 py-2.5 font-bold text-sm appearance-none">
                        <option value="" disabled selected>Choisir un coach...</option>
                        <?php foreach($coaches as $coach): ?>
                            <option value="<?= $coach['id'] ?>" class="bg-slate-900 text-white"><?= htmlspecialchars($coach['nom']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <div class="space-y-2">
                        <label class="block text-[20px] font-black text-teal-500 uppercase tracking-[0.3em] ml-1">Date Séance</label>
                        <input type="date" name="date_seance" required class="w-full input-pro rounded-2xl px-6 py-2.5 font-bold text-sm">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="block text-[20px] font-black text-teal-500 uppercase tracking-[0.3em] ml-1">Heure</label>
                            <input type="time" name="heure_debut" required class="w-full input-pro rounded-2xl px-2 py-2.5 font-bold text-sm">
                        </div>

                    </div>
                </div>

                <div class="pt-10 flex flex-col sm:flex-row items-center gap-6">
                    <button type="submit" class="w-full sm:flex-[2] btn-real-3d btn-confirm-3d">
                        Confirmer
                    </button>
                    <a href="index.php" class="w-full sm:flex-1 btn-real-3d btn-cancel-3d text-center font-bold">
                        Retour
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>