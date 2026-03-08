<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Coachs - Dechouke Grès Fitness</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    colors: {
                        'gym-accent': '#38bdf8',
                        'gym-danger': '#f97316',
                    }
                }
            }
        }
    </script>
    <style>
        body {
            /* Imaj background ki soti nan Unsplash (Gym style) */
            background: linear-gradient(rgba(15, 23, 42, 0.85), rgba(15, 23, 42, 0.95)), 
                        url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .glass-card {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="font-sans text-slate-200 antialiased min-h-screen">

    <div class="max-w-7xl mx-auto px-6 py-12">
        
        <div class="mb-12 text-center md:text-left">
            <h1 class="text-5xl font-extrabold tracking-tighter text-white uppercase italic drop-shadow-lg">
                Gestion <span class="text-gym-accent">Coachs</span>
            </h1>
            <p class="text-slate-400 mt-2 font-medium">Administrez vos experts en performance</p>
        </div>

        <div class="glass-card rounded-3xl shadow-2xl overflow-hidden">
            <table class="w-full text-left table-auto border-collapse">
                <thead>
                    <tr class="bg-slate-900/50 text-gym-accent uppercase text-xs font-black tracking-[0.2em]">
                        <th class="px-8 py-6">Coach</th>
                        <th class="px-8 py-6">Expertises</th>
                        <th class="px-8 py-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <tr class="hover:bg-white/5 transition-all duration-300">
                        <td class="px-8 py-7">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-full bg-gym-accent/20 flex items-center justify-center border border-gym-accent/30 font-bold text-gym-accent">
                                    CC
                                </div>
                                <div>
                                    <div class="font-bold text-xl text-white">Caleb CELESTIN</div>
                                    <div class="text-sm text-slate-400">calebcelestin35@gmail.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-7">
                            <span class="inline-flex items-center rounded-lg bg-gym-accent/10 px-3 py-1 text-xs font-bold text-gym-accent border border-gym-accent/20">
                                MUSCULATION
                            </span>
                        </td>
                        <td class="px-8 py-7 text-center">
                            <div class="flex items-center justify-center gap-4">
                                <a href="modifier.php" class="bg-white/5 hover:bg-gym-accent hover:text-gym-dark px-4 py-2 rounded-xl transition-all duration-300 text-sm font-bold border border-white/10">
                                    Modifier
                                </a>
                                <a href="supprimer.php" class="bg-white/5 hover:bg-gym-danger hover:text-white px-4 py-2 rounded-xl transition-all duration-300 text-sm font-bold border border-white/10">
                                    Supprimer
                                </a>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-12 flex justify-center">
            <a href="ajouter.php" class="group relative inline-flex items-center justify-center px-12 py-5 font-black text-gym-dark uppercase tracking-widest transition-all duration-300 bg-gym-accent rounded-2xl hover:scale-105 hover:shadow-[0_0_30px_rgba(56,189,248,0.4)]">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 mr-3">
                  <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 9a.75.75 0 00-1.5 0v2.25H9a.75.75 0 000 1.5h2.25V15a.75.75 0 001.5 0v-2.25H15a.75.75 0 000-1.5h-2.25V9z" clip-rule="evenodd" />
                </svg>
                Ajouter un Coach
            </a>
        </div>
        
        <div class="mt-20 text-center text-xs text-slate-500 uppercase tracking-widest font-bold">
            &mdash; Dechouke Grès Fitness &mdash;
        </div>
    </div>

</body>
</html>