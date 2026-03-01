<?php
session_start();
require_once 'config/db.php';

$erreur = "";
$succes = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $role = $_POST['role'];

    // Vérification si l'email existe déjà
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetch()) {
        $erreur = "Cet email est déjà utilisé.";
    } else {
        $hash = password_hash($mot_de_passe, PASSWORD_DEFAULT);
        $sql = "INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, role) VALUES (?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$nom, $prenom, $email, $hash, $role])) {
            $succes = "Compte créé ! Vous pouvez vous connecter.";
        } else {
            $erreur = "Erreur lors de la création du compte.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Sign Up - NeuroCode Gym</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #0f172a 50%, #14b8a6 50%);
            min-height: 100vh;
        }
        .signup-card {
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .neon-input {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid #14b8a6;
            color: white;
        }
        .neon-input:focus {
            box-shadow: 0 0 15px rgba(20, 184, 166, 0.4);
        }
        select option { background: #0f172a; color: white; }
    </style>
</head>
<body class="flex items-center justify-center p-4">

    <div class="flex w-full max-w-4xl shadow-2xl rounded-3xl overflow-hidden signup-card">
        
        <div class="hidden md:flex w-1/2 p-12 flex-col justify-center items-center text-center">
            <h2 class="text-4xl font-black text-white mb-4">JOIN US!</h2>
            <p class="text-teal-100/70 italic text-sm">Create an account to manage your gym and help your members.</p>
            <img src="assets/images/wel_girl.png" 
                 alt="Coach" class="w-80 h-auto drop-shadow-[0_0_30px_rgba(20,184,166,0.3)]">
        </div>

        <div class="w-full md:w-1/2 p-10 flex flex-col justify-center">
            <h1 class="text-3xl font-bold text-white mb-6">Sign Up</h1>
            
            <?php if($erreur): ?>
                <div class="bg-red-500/20 text-red-400 p-3 rounded-lg mb-4 text-sm border border-red-500/50">⚠️ <?= $erreur ?></div>
            <?php endif; ?>
            <?php if($succes): ?>
                <div class="bg-teal-500/20 text-teal-400 p-3 rounded-lg mb-4 text-sm border border-teal-500/50">✅ <?= $succes ?></div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" name="nom" placeholder="Nom" class="w-full p-3 rounded-full neon-input outline-none text-sm" required>
                    <input type="text" name="prenom" placeholder="Prénom" class="w-full p-3 rounded-full neon-input outline-none text-sm" required>
                </div>
                <input type="email" name="email" placeholder="Email" class="w-full p-3 rounded-full neon-input outline-none text-sm" required>
                <input type="password" name="mot_de_passe" placeholder="Password" class="w-full p-3 rounded-full neon-input outline-none text-sm" required>
                
                <div class="relative">
                    <label class="text-teal-400 text-xs font-bold ml-4 mb-1 block">Rôle de l'utilisateur</label>
                    <select name="role" class="w-full p-3 rounded-full neon-input outline-none text-sm appearance-none cursor-pointer" required>
                        <option value="coach">Coach (Accès limité)</option>
                        <option value="admin">Administrateur (Accès total)</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-teal-500 hover:bg-teal-400 text-slate-900 font-bold py-3 rounded-full transition transform hover:scale-105 shadow-lg mt-4">
                    Register
                </button>
            </form>

            <div class="mt-6 text-center text-xs text-gray-400">
                Already have an account? <a href="login.php" class="text-teal-400 font-bold hover:underline">Login here</a>
            </div>
        </div>
    </div>

</body>
</html>