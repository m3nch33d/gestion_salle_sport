<?php
require_once 'includes/securite.php'; 
require_once 'config/db.php';

$notification = "";
$user = null;

// 1. Charger les données de l'utilisateur à modifier
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ?");
    $stmt->execute([$id]);
    $user = $stmt->fetch();

    if (!$user) {
        header("Location: liste_utilisateurs.php");
        exit();
    }
}

// 2. Traitement de la mise à jour
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = (int)$_POST['id'];
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $role = $_POST['role'];
    
    try {
        // On met à jour sans toucher au mot de passe pour l'instant
        $sql = "UPDATE utilisateurs SET nom = ?, prenom = ?, email = ?, role = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $prenom, $email, $role, $id]);
        
        $notification = "success";
        header("refresh:2;url=liste_utilisateurs.php");
    } catch (PDOException $e) {
        $notification = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier Staff - Dechouke Grès</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body { background-image: url('assets/images/background.png'); background-size: cover; background-position: center; background-attachment: fixed; }
        .glass-card { background: rgba(15, 23, 42, 0.75); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .input-field { background: rgba(30, 41, 59, 0.5); border: 1px solid rgba(71, 85, 105, 0.5); color: white; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="glass-card max-w-lg w-full p-12 rounded-[35px] shadow-2xl animate__animated animate__fadeIn">
    
    <div class="text-center mb-12">
        <h1 class="text-3xl font-black text-white uppercase italic">Modifier <span class="text-teal-400">Profil</span></h1>
        <p class="text-slate-400 text-[10px] uppercase tracking-widest mt-2">ID Membre: #<?= $user['id'] ?></p>
    </div>

    <form action="" method="POST" class="space-y-6">
        <input type="hidden" name="id" value="<?= $user['id'] ?>">

        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="text-[9px] font-bold text-slate-500 uppercase ml-2 tracking-widest">Nom</label>
                <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required 
                       class="w-full input-field p-4 rounded-lg outline-none text-sm font-bold uppercase">
            </div>
            <div class="space-y-1">
                <label class="text-[9px] font-bold text-slate-500 uppercase ml-2 tracking-widest">Prénom</label>
                <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required 
                       class="w-full input-field p-4 rounded-lg outline-none text-sm font-bold uppercase">
            </div>
        </div>

        <div class="space-y-1">
            <label class="text-[9px] font-bold text-slate-500 uppercase ml-2 tracking-widest">Adresse Email</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required 
                   class="w-full input-field p-4 rounded-lg outline-none text-sm">
        </div>

        <div class="space-y-1">
            <label class="text-[10px] font-bold text-slate-500 uppercase ml-2 tracking-widest">Niveau d'accès</label>
            <select name="role" class="w-full input-field p-4 rounded-lg outline-none appearance-none cursor-pointer text-sm font-bold">
                <option value="employe" <?= $user['role'] == 'employe' ? 'selected' : '' ?> class="bg-slate-900">Employé (Standard)</option>
                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?> class="bg-slate-900">Administrateur (Groupe B)</option>
            </select>
        </div>

        <div class="flex items-center justify-end gap-6 mt-12 border-t border-white/10 pt-8">
            <a href="liste_utilisateurs.php" 
               class="text-slate-400 hover:text-white text-[11px] uppercase font-bold tracking-widest transition-colors px-4 py-2">
               Annuler
            </a>
            <button type="submit" 
                    class="bg-teal-500 hover:bg-teal-400 text-slate-900 font-black px-10 py-3 rounded-lg transition-all shadow-lg shadow-teal-500/20 uppercase text-[11px] tracking-widest">
                Sauvegarder
            </button>
        </div>
    </form>
</div>

</body>
</html>