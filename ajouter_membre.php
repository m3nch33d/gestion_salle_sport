<?php 
require_once 'config/db.php'; 
include 'includes/header.php'; 

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $telephone = $_POST['telephone'];
    $email = $_POST['email'];
    $date_naissance = $_POST['date_naissance'];
    
    $photo = "default.png";
    if (!empty($_FILES['photo']['name'])) {
        $photo = time() . '_' . $_FILES['photo']['name'];
        move_uploaded_file($_FILES['photo']['tmp_name'], 'public/uploads/' . $photo);
    }

    try {
        $sql = "INSERT INTO membres (nom, prenom, telephone, email, date_naissance, photo) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $prenom, $telephone, $email, $date_naissance, $photo]);
        
        $message = "<div class='bg-green-500/20 border border-green-500 text-green-200 p-3 rounded-xl mb-4 animate__animated animate__fadeIn'>✅ Membre ajouté avec succès !</div>";
    } catch (PDOException $e) {
        $message = "<div class='bg-red-500/20 border border-red-500 text-red-200 p-3 rounded-xl mb-4'>❌ Erreur : " . $e->getMessage() . "</div>";
    }
}
?>

<style>
    /* Configuration de la vidéo d'arrière-plan */
    #video-bg {
        position: fixed;
        right: 0;
        bottom: 0;
        min-width: 100%;
        min-height: 100%;
        z-index: -2;
        object-fit: cover;
        filter: brightness(0.4); /* Assombrit la vidéo pour la lisibilité */
    }

    /* Overlay pour teinter la vidéo */
    .video-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.3);
        z-index: -1;
    }

    /* Style Glassmorphism */
    .glass-panel {
        background: rgba(255, 255, 255, 0.07) !important;
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.15);
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.5);
    }

    .glass-input {
        background: rgba(15, 23, 42, 0.6) !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: white !important;
    }

    .glass-input:focus {
        border-color: #10b981 !important; /* Couleur Emerald */
        box-shadow: 0 0 10px rgba(16, 185, 129, 0.3);
    }
     
     /* Rend l'icône du calendrier blanche */
input[type="date"]::-webkit-calendar-picker-indicator {
    filter: invert(1); /* Inverse le noir en blanc */
    cursor: pointer;
    opacity: 0.8;
    transition: opacity 0.3s;
}

input[type="date"]::-webkit-calendar-picker-indicator:hover {
    opacity: 1;
}

    label { color: #d1d5db !important; } /* Texte gris clair */
</style>

<video autoplay muted loop id="video-bg">
    <source src="assets/videos/background.mp4" type="video/mp4">
    Votre navigateur ne supporte pas les vidéos HTML5.
</video>
<div class="video-overlay"></div>

<div class="container mx-auto px-4 py-12 relative z-10">
    <div class="max-w-2xl mx-auto glass-panel rounded-[30px] overflow-hidden animate__animated animate__fadeInUp">
        
        <div class="bg-teal-500/80 px-6 py-5 border-b border-white/10">
            <h2 class="text-white text-2xl font-black uppercase tracking-tight">Inscrire un nouvel Adhérent</h2>
        </div>
        
        <form action="" method="POST" enctype="multipart/form-data" class="p-8">
            <?= $message ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2 uppercase tracking-widest">Nom</label>
                    <input type="text" name="nom" required class="w-full px-4 py-3 glass-input rounded-xl outline-none transition-all">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2 uppercase tracking-widest">Prénom</label>
                    <input type="text" name="prenom" required class="w-full px-4 py-3 glass-input rounded-xl outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2 uppercase tracking-widest">Téléphone</label>
                    <input type="text" name="telephone" class="w-full px-4 py-3 glass-input rounded-xl outline-none transition-all">
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-bold mb-2 uppercase tracking-widest">Email</label>
                    <input type="email" name="email" class="w-full px-4 py-3 glass-input rounded-xl outline-none transition-all">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-bold mb-2 uppercase tracking-widest">Date de naissance</label>
                <input type="date" name="date_naissance" class="w-full px-4 py-3 glass-input rounded-xl outline-none transition-all">
            </div>

            <div class="mb-8">
                <label class="block text-sm font-bold mb-2 uppercase tracking-widest">Photo de profil</label>
                <input type="file" name="photo" class="w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-6 file:rounded-full file:border-0 file:text-sm file:font-bold file:bg-teal-500 file:text-white hover:file:bg-teal-300 transition cursor-pointer">
            </div>

            <div class="flex items-center justify-between border-t border-white/10 pt-6">
                <a href="membres.php" class="text-gray-400 hover:text-white transition-colors font-bold uppercase text-xs tracking-widest">Annuler</a>
                <button type="submit" class="bg-teal-500 hover:bg-yeal-400 text-slate-900 font-black py-3 px-8 rounded-full transition-all transform hover:scale-105 active:scale-95 shadow-lg shadow-emerald-500/20">
                    ENREGISTRER L'ADHÉRENT
                </button>
            </div>
        </form>
    </div>
</div>

</main> </body>
</html>