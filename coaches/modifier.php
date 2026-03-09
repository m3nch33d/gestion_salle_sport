<?php
// Tcheke si se db.php oswa config.php ki nan dosye config ou a
require_once '../config/db.php'; 

// Isit la ou ta dwe gen kòd pou rekipere enfòmasyon coach la ak ID a
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Coach - Dechouke Grès Fitness</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(rgba(0, 0, 0, 0.8), rgba(15, 23, 42, 0.9)), 
                        url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=2070&auto=format&fit=crop');
            background-size: cover; background-attachment: fixed;
        }
        .glass-card { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(15px); border: 1px solid rgba(255, 255, 255, 0.1); }
    </style>
</head>
<body class="font-sans text-slate-200 min-h-screen flex items-center justify-center py-12 px-6">

    <div class="max-w-md w-full glass-card p-10 rounded-[2.5rem] shadow-2xl border-t-4 border-t-sky-400">
        <h2 class="text-3xl font-black text-white uppercase italic tracking-tighter text-center mb-8">
            Modifier <span class="text-sky-400">Coach</span>
        </h2>

        <form action="update_coach.php" method="POST" class="space-y-6">
            <input type="hidden" name="id" value="1"> 

            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-sky-400 mb-2 text-glow">Nom Complet</label>
                <input type="text" name="nom" value="Caleb CELESTIN" required class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 text-white focus:outline-none focus:border-sky-400 focus:ring-1 focus:ring-sky-400 transition">
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-sky-400 mb-2 text-glow">Email</label>
                <input type="email" name="email" value="calebcelestin35@gmail.com" required class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 text-white focus:outline-none focus:border-sky-400 focus:ring-1 focus:ring-sky-400 transition">
            </div>

            <div>
                <label class="block text-xs font-black uppercase tracking-widest text-sky-400 mb-2 text-glow">Spécialité</label>
                <input type="text" name="expertise" value="Musculation" required class="w-full bg-white/5 border border-white/10 rounded-xl px-5 py-4 text-white focus:outline-none focus:border-sky-400 focus:ring-1 focus:ring-sky-400 transition">
            </div>

           <div class="pt-6 flex items-center gap-4">
                <button type="submit" class="flex-1 bg-sky-400 hover:bg-white text-slate-900 font-black py-3 px-4 rounded-xl uppercase text-xs tracking-tighter transition-all duration-300 shadow-lg shadow-sky-400/20">
                    Sauvegarder
                </button>
                
                <a href="index.php" class="flex-1 bg-white/10 hover:bg-white/20 text-white font-black py-3 px-4 rounded-xl uppercase text-xs tracking-tighter text-center transition-all duration-300 border border-white/10">
                    Retourner
                </a>
            </div>
        </form>
    </div>

</body>
</html>