<?php
require_once 'includes/securite.php'; 
require_once 'config/db.php';
include 'includes/header.php'; 

// 1. Récupération de tous les utilisateurs
$stmt = $pdo->query("SELECT id, nom, prenom, email, role FROM utilisateurs ORDER BY id DESC");
$utilisateurs = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste Staff - Dechouke Grès</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body { 
            background-image: url('assets/images/background.png'); 
            background-size: cover; background-position: center; background-attachment: fixed; 
        }
        .glass-panel { 
            background: rgba(15, 23, 42, 0.8); backdrop-filter: blur(20px); 
            border: 1px solid rgba(255, 255, 255, 0.1); 
        }
    </style>
</head>
<body class="min-h-screen p-8">

    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-10 animate__animated animate__fadeInDown">
            <div>
                <h1 class="text-4xl font-black text-white italic uppercase">Staff <span class="text-teal-400">Members</span></h1>
                <p class="text-slate-400 text-xs tracking-[0.3em] uppercase mt-1">Gestion des accès</p>
            </div>
            <a href="ajouter_utilisateur.php" class="bg-teal-500 hover:bg-teal-400 text-slate-900 font-bold px-6 py-3 rounded-2xl transition-all shadow-lg text-sm uppercase">
                <img src="assets/images/add.png" class="w-10 h-10">Ajouter un membre</span> 
            </a>
        </div>


        <?php if (isset($_GET['msg']) && $_GET['msg'] === 'supprime'): ?>
    <div class="bg-red-500/20 border border-red-500 text-red-200 p-4 rounded-2xl mb-6 text-sm text-center animate__animated animate__headShake">
        🗑️ Le membre a été retiré du staff.
    </div>
<?php endif; ?>

        <div class="glass-panel rounded-[35px] overflow-hidden shadow-2xl animate__animated animate__fadeInUp">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-white/5 text-teal-400 text-[10px] uppercase tracking-widest">
                        <th class="p-6">Identité</th>
                        <th class="p-6">Email</th>
                        <th class="p-6">Rôle / Niveau</th>
                        <th class="p-6 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-slate-200 text-sm">
                    <?php foreach ($utilisateurs as $user): ?>
                    <tr class="border-t border-white/5 hover:bg-teal-100/30 transition-all duration-700 ease-in-out border border-transparent hover:border-teal-300">
                        <td class="p-6">
                            <span class="font-bold text-white uppercase"><?= htmlspecialchars($user['nom']) ?></span> 
                            <?= htmlspecialchars($user['prenom']) ?>
                        </td>
                        <td class="p-6 text-slate-400 font-mono"><?= htmlspecialchars($user['email']) ?></td>
                        <td class="p-6">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase <?= $user['role'] === 'admin' ? 'bg-purple-500/20 text-purple-400 border border-purple-500/50' : 'bg-blue-500/20 text-blue-400 border border-blue-500/50' ?>">
                                <?= $user['role'] ?>
                            </span>
                        </td>
                        <td class="p-6 flex justify-center gap-3">
                            <a href="modifier_utilisateur.php?id=<?= $user['id'] ?>" class="hover:scale-110 transition">
                                <img src="assets/images/oi.png" alt="Edit" class="w-10 h-10 opacity-70 hover:opacity-100">
                            </a>
                            <a href="supprimer_utilisateur.php?id=<?= $user['id'] ?>" 
                               onclick="return confirm('Es-tu sûr de vouloir supprimer ce membre ?')" 
                               class="hover:scale-110 transition">
                                <img src="assets/images/trash.png" alt="Delete" class="w-10 h-10 opacity-70 hover:opacity-100">
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>