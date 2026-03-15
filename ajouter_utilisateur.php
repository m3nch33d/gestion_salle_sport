<?php
require_once 'includes/securite.php'; 
require_once 'config/db.php';

$notification = "";
$mdp_clair = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $email = htmlspecialchars($_POST['email']);
    $role = $_POST['role'];
    $mdp_clair = $_POST['mot_de_passe']; 
    $mdp_hash = password_hash($mdp_clair, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $email, $mdp_hash, $role]);
        
        $notification = "success";
        // Redirection après 3s
        header("refresh:3;url=index.php"); 
        
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $notification = "duplicate";
        } else {
            $notification = "error";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau Staff - Dechouke Grès</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <style>
        body { 
            background-image: url('assets/images/background.png'); 
            background-size: cover; 
            background-position: center; 
            background-attachment: fixed; 
            overflow-x: hidden;
        }
        .glass-card { 
            background: rgba(15, 23, 42, 0.75); 
            backdrop-filter: blur(16px); 
            border: 1px solid rgba(255, 255, 255, 0.1); 
        }
        .input-field { 
            background: rgba(30, 41, 59, 0.5); 
            border: 1px solid rgba(71, 85, 105, 0.5); 
            color: white; 
            transition: all 0.3s;
        }
        .input-field:focus {
            border-color: #2dd4bf;
            background: rgba(30, 41, 59, 0.8);
        }
        .icon-mini { width: 16px; height: 16px; object-fit: contain; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div id="main-content" class="glass-card max-w-lg w-full p-8 rounded-[35px] shadow-2xl animate__animated animate__fadeInUp">
        
        <div class="text-center mb-8">
            <h1 class="text-3xl font-black text-white uppercase tracking-tighter italic">
                Nouveau <span class="text-teal-400">Staff</span>
            </h1>
            <p class="text-slate-400 text-[10px] uppercase tracking-widest mt-1">Management System</p>
        </div>

        <?php if ($notification === "success"): ?>
            <div class="bg-teal-500/20 border border-teal-500 text-teal-200 p-4 rounded-2xl mb-6 text-sm text-center animate__animated animate__pulse">
                ✅ Compte créé avec succès ! <br>
                <span class="text-[11px] opacity-80">Redirection vers l'accueil...</span>
            </div>
        <?php elseif ($notification === "duplicate"): ?>
            <div class="bg-orange-500/20 border border-orange-500 text-orange-200 p-4 rounded-2xl mb-6 text-sm text-center animate__animated animate__shakeX">
                ⚠️ Cet email est déjà utilisé.
            </div>
        <?php elseif ($notification === "error"): ?>
            <div class="bg-red-500/20 border border-red-500 text-red-200 p-4 rounded-2xl mb-6 text-sm text-center">
                ❌ Erreur lors de l'enregistrement.
            </div>
        <?php endif; ?>

        <form id="staffForm" action="" method="POST" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <input type="text" name="nom" placeholder="NOM" required 
                       class="input-field p-3.5 rounded-2xl outline-none text-sm font-bold uppercase">
                <input type="text" name="prenom" placeholder="PRÉNOM" required 
                       class="input-field p-3.5 rounded-2xl outline-none text-sm font-bold uppercase">
            </div>

            <input type="email" name="email" placeholder="ADRESSE EMAIL" required 
                   class="w-full input-field p-3.5 rounded-2xl outline-none text-sm">

            <div class="relative">
                <label class="text-[10px] font-bold text-slate-500 uppercase ml-2 mb-1 block tracking-widest">Mot de passe sécurisé (16 caractères)</label>
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <input type="password" name="mot_de_passe" id="mdp_input" required 
                               class="input-field w-full p-3.5 rounded-2xl outline-none font-mono text-xs pr-12">
                        <button type="button" onclick="togglePassword()" class="absolute right-4 top-1/2 -translate-y-1/2 opacity-60 hover:opacity-100 transition">
                            <img src="assets/images/oeye.png" id="eye_icon" alt="Voir" class="icon-mini">
                        </button>
                    </div>
                    <button type="button" onclick="genererMDP()" class="bg-slate-700 hover:bg-teal-500 text-white px-4 rounded-2xl transition flex items-center justify-center">
                        <img src="assets/images/refresh.png" alt="Générer" class="icon-mini">
                    </button>
                </div>
            </div>

            <div class="relative">
                <label class="text-[10px] font-bold text-slate-500 uppercase ml-2 mb-1 block tracking-widest">Niveau d'accès</label>
                <select name="role" class="w-full input-field p-3.5 rounded-2xl outline-none appearance-none cursor-pointer text-sm font-bold">
                    <option value="employe" class="bg-slate-900">Employé (Standard)</option>
                    <option value="admin" class="bg-slate-900">Administrateur (Groupe B)</option>
                </select>
            </div>

            <button type="submit" id="submitBtn" class="w-full bg-teal-500 hover:bg-teal-400 text-slate-900 font-black py-4 rounded-2xl transition-all shadow-lg uppercase text-sm tracking-widest mt-4">
                Enregistrer le compte
            </button>
        </form>
    </div>

    <script>
    // Animation de sortie automatique si redirection PHP détectée
    <?php if ($notification === "success"): ?>
    setTimeout(() => {
        const container = document.getElementById('main-content');
        container.classList.remove('animate__fadeInUp');
        container.classList.add('animate__fadeOutDown');
    }, 2500); // Se lance juste avant la redirection de 3s
    <?php endif; ?>

    function genererMDP() {
        const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%";
        let password = "";
        for (let i = 0; i < 16; i++) {
            password += chars.charAt(Math.floor(Math.random() * chars.length));
        }
        document.getElementById('mdp_input').value = password;
    }

    function togglePassword() {
        const input = document.getElementById('mdp_input');
        const icon = document.getElementById('eye_icon');
        if (input.type === "password") {
            input.type = "text";
            icon.src = "assets/images/ceye.png";
        } else {
            input.type = "password";
            icon.src = "assets/images/oeye.png";
        }
    }

    // Effet visuel au clic sur Enregistrer
    document.getElementById('staffForm').onsubmit = function() {
        const btn = document.getElementById('submitBtn');
        btn.innerHTML = "Traitement en cours...";
        btn.classList.add('opacity-50', 'cursor-not-allowed');
    };

    window.onload = genererMDP;
    </script>
</body>
</html>