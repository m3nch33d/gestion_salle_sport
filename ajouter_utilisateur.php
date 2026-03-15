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

    <div id="main-content" class="glass-card w-full max-w-lg p-6 md:p-8 rounded-[24px] md:rounded-[35px] shadow-2xl animate__animated animate__fadeInUp">
        
        <div class="text-center mb-8">
            <h1 class="text-2xl md:text-3xl font-black text-white uppercase italic">
                Nouveau <span class="text-teal-400">Staff</span>
            </h1>
        </div>

        <form id="staffForm" action="" method="POST" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="text" name="nom" placeholder="NOM" required 
                       class="input-field p-4 rounded-xl outline-none text-sm font-bold uppercase w-full">
                <input type="text" name="prenom" placeholder="PRÉNOM" required 
                       class="input-field p-4 rounded-xl outline-none text-sm font-bold uppercase w-full">
            </div>

            <input type="email" name="email" placeholder="ADRESSE EMAIL" required 
                   class="w-full input-field p-4 rounded-xl outline-none text-sm">

            <div class="relative">
                <label class="text-[9px] font-bold text-slate-500 uppercase ml-2 mb-1 block">Mot de passe (16 caractères)</label>
                <div class="flex gap-2">
                    <input type="password" name="mot_de_passe" id="mdp_input" required 
                           class="input-field flex-1 p-4 rounded-xl outline-none font-mono text-xs">
                    <button type="button" onclick="genererMDP()" class="bg-slate-700 px-4 rounded-xl flex items-center">
                        <img src="assets/images/refresh.png" class="w-4 h-4">
                    </button>
                </div>
            </div>

            <select name="role" class="w-full input-field p-4 rounded-xl outline-none text-sm font-bold">
                <option value="employe">Employé (Standard)</option>
                <option value="admin">Administrateur</option>
            </select>

            <button type="submit" id="submitBtn" class="w-full bg-teal-500 text-slate-900 font-black py-5 md:py-4 rounded-xl uppercase text-xs md:text-sm tracking-widest mt-4">
                Enregistrer le compte
            </button>
        </form>
    </div>
</body>
</html>