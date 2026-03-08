<?php
require_once '../config/db.php';

// Nou rekipere tout coach yo nan baz de done a
try {
    $stmt = $pdo->query("SELECT * FROM coaches ORDER BY created_at DESC");
    $coaches = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elite Staff - Nos Coaches</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;900&display=swap" rel="stylesheet">
    <style>
        body { 
            font-family: 'Outfit', sans-serif; 
            background: linear-gradient(rgba(2, 6, 23, 0.9), rgba(2, 6, 23, 0.9)), 
                        url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=2070&auto=format&fit=crop'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .glass-container {
            background: rgba(15, 23, 42, 0.6);
            backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        .row-hover:hover {
            background: rgba(59, 130, 246, 0.05);
            transition: all 0.3s ease;
        }
    </style>
</head>
<body class="min-h-screen p-8 md:p-12">

    <div class="max-w-6xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-center mb-12 gap-6">
            <div>
                <h1 class="text-5xl font-black italic text-white uppercase tracking-tighter">
                    Nos <span class="text-blue-500 text-6xl">Coaches</span>
                </h1>
                <p class="text-slate-400 text-xs mt-2 uppercase tracking-[0.3em] font-bold">Elite Management System</p>
            </div>
            
            <a href="ajouter.php" class="bg-blue-600 hover:bg-blue-500 text-white font-black uppercase text-[11px] tracking-[0.2em] px-8 py-4 rounded-2xl shadow-lg shadow-blue-500/20 transition-all flex items-center gap-3 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke-width="3" stroke-linecap="round"/></svg>
                Ajouter un Coach
            </a>
        </div>

        <div class="glass-container rounded-[40px] overflow-hidden shadow-2xl border border-white/5">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/5 bg-white/5">
                        <th class="p-6 text-[10px] font-black text-slate-500 uppercase tracking-widest">Nom & Prénom</th>
                        <th class="p-6 text-[10px] font-black text-slate-500 uppercase tracking-widest">Email</th>
                        <th class="p-6 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Téléphone</th>
                        <th class="p-6 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php foreach ($coaches as $coach): ?>
                    <tr class="row-hover group">
                        <td class="p-6">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-blue-500/10 flex items-center justify-center text-blue-500 font-black text-xs border border-blue-500/20">
                                    <?= substr($coach['nom'], 0, 1) ?>
                                </div>
                                <span class="text-white font-bold tracking-wide uppercase text-sm italic">
                                    <?= htmlspecialchars($coach['nom'] . ' ' . ($coach['prenom'] ?? '')) ?>
                                </span>
                            </div>
                        </td>
                        <td class="p-6">
                            <span class="text-slate-400 font-medium text-sm">
                                <?= $coach['email'] ? htmlspecialchars($coach['email']) : '<span class="text-slate-700 italic">---</span>' ?>
                            </span>
                        </td>
                        <td class="p-6 text-center">
                            <span class="text-blue-400 font-mono text-sm tracking-tighter">
                                <?= $coach['telephone'] ? htmlspecialchars($coach['telephone']) : '---' ?>
                            </span>
                        </td>
                        <td class="p-6 text-right">
                            <a href="supprimer.php?id=<?= $coach['id'] ?>" 
                               onclick="return confirm('Eske ou sèten ou vle efase coach sa a?')"
                               class="bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                                Supprimer
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php if (empty($coaches)): ?>
            <div class="p-20 text-center">
                <p class="text-slate-500 uppercase text-[10px] font-black tracking-[0.5em]">Aucun coach enregistré</p>
            </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>