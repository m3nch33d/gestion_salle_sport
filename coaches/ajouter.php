<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elite Recruitment - Professional Gym Staff</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;900&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Outfit', sans-serif; 
            background: linear-gradient(rgba(2, 6, 23, 0.8), rgba(2, 6, 23, 0.8)), 
                        url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=2070&auto=format&fit=crop'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .glass-card {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        .input-dark {
            background: rgba(30, 41, 59, 0.4);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }
        .input-dark:focus {
            border-color: rgba(59, 130, 246, 0.5);
            background: rgba(30, 41, 59, 0.7);
            box-shadow: 0 0 15px rgba(59, 130, 246, 0.1);
            outline: none;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-6">

    

    <div class="glass-card w-full max-w-5xl rounded-[50px] overflow-hidden flex flex-col md:flex-row">
        
        <div class="w-full md:w-[38%] p-12 border-r border-white/5 flex flex-col justify-between relative">
            <div class="relative z-10">
                <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mb-10 shadow-[0_10px_30px_rgba(37,99,235,0.4)]">
                    <svg class="text-white w-9 h-9" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
                <h1 class="text-5xl font-black italic text-white leading-[0.85] tracking-tighter uppercase">
                   RECRUTER<br><span class="text-blue-500">L'Excellence</span>
                </h1>
                <p class="text-slate-400 text-sm mt-8 leading-relaxed font-light">
                   Intégrez un nouvel expert à votre équipe pour offrir une expérience fitness inégalée à vos membres.
                </p>
            </div>

            <div class="mt-16 flex items-center gap-4 relative z-10">
                <div class="flex space-x-[-10px]">
                    <div class="w-8 h-8 rounded-full border-2 border-slate-900 bg-slate-700"></div>
                    <div class="w-8 h-8 rounded-full border-2 border-slate-900 bg-blue-600 text-[10px] flex items-center justify-center font-bold">+12</div>
                </div>
                <span class="text-[9px] font-black text-blue-500 uppercase tracking-[0.4em]">Active Coaches</span>
            </div>
        </div>

        <div class="flex-1 p-12 md:p-16">
            <form action="traitement.php" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div class="space-y-2 md:col-span-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Nom du Coach</label>
                    <input type="text" name="nom" required placeholder="CAROLINE" 
                        class="w-full input-dark rounded-2xl p-4 text-white placeholder:text-slate-700 font-bold uppercase tracking-widest">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Email de Contact</label>
                    <input type="email" name="email" placeholder="carolina@elite.com" 
                        class="w-full bg-white rounded-2xl p-4 text-slate-900 placeholder:text-slate-400 font-medium focus:outline-none focus:ring-4 focus:ring-blue-500/20 transition-all">
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Numéro Téléphone</label>
                    <input type="text" name="telephone" placeholder="+509 XXXX-XXXX" 
                        class="w-full input-dark rounded-2xl p-4 text-white placeholder:text-slate-700 font-mono tracking-widest">
                </div>

                <div class="space-y-2 md:col-span-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Specialter</label>
                    <div class="relative">
                        <select name="specialite" class="w-full input-dark rounded-2xl p-4 text-white appearance-none cursor-pointer font-semibold">
                            <option value="Yoga & Mobility">Yoga & Mobility</option>
                            <option value="Bodybuilding">Bodybuilding</option>
                            <option value="Crossfit & Strength">Crossfit & Strength</option>
                        </select>
                        <div class="absolute right-5 top-1/2 -translate-y-1/2 pointer-events-none text-blue-500">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 9l-7 7-7-7" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-4 pt-6 md:col-span-2">
                    <button type="submit" class="flex-[2] bg-gradient-to-r from-blue-700 to-blue-500 text-white font-black uppercase text-[11px] tracking-[0.3em] py-5 rounded-2xl shadow-xl shadow-blue-600/20 hover:scale-[1.01] hover:shadow-blue-600/40 active:scale-95 transition-all">
                        Register Coach
                    </button>
                    <a href="liste_coaches.php" class="flex-1 bg-white/5 text-slate-400 font-black uppercase text-[11px] tracking-[0.3em] py-5 rounded-2xl hover:bg-white/10 hover:text-white text-center transition-all border border-white/5">
                        Back
                    </a>
                </div>
            </form>
        </div>
    </div>

</body>
</html>