<?php 
require_once 'config/db.php'; 
require_once 'includes/securite.php';
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
        if(!is_dir('public/uploads/')) { mkdir('public/uploads/', 0777, true); }
        move_uploaded_file($_FILES['photo']['tmp_name'], 'public/uploads/' . $photo);
    }

    try {
        $sql = "INSERT INTO membres (nom, prenom, telephone, email, date_naissance, photo) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nom, $prenom, $telephone, $email, $date_naissance, $photo]);
        
        $message = "<div class='bg-emerald-500/20 border border-emerald-500 text-emerald-200 p-4 rounded-xl mb-6 animate__animated animate__bounceIn'>✅ Membre ajouté avec succès !</div>";
    } catch (PDOException $e) {
        $message = "<div class='bg-rose-500/20 border border-rose-500 text-rose-200 p-4 rounded-xl mb-6'>❌ Erreur : " . $e->getMessage() . "</div>";
    }
}
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>

<style>
    body { margin: 0; overflow-x: hidden; font-family: 'Inter', sans-serif; }
    #video-bg { position: fixed; right: 0; bottom: 0; min-width: 100%; min-height: 100%; z-index: -2; object-fit: cover; filter: brightness(0.3); }
    .video-overlay { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.4); z-index: -1; }
    
    .glass-panel { 
        background: rgba(15, 23, 42, 0.7) !important; 
        backdrop-filter: blur(20px); 
        border: 1px solid rgba(255, 255, 255, 0.1); 
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }

    .glass-input { 
        background: rgba(255, 255, 255, 0.05) !important; 
        border: 1px solid rgba(255, 255, 255, 0.1) !important; 
        color: white !important; 
    }
    .glass-input:focus { border-color: #14b8a6 !important; background: rgba(255, 255, 255, 0.1) !important; }
    
    input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1); cursor: pointer; }
    label { color: #94a3b8 !important; font-size: 10px; font-weight: 800; letter-spacing: 0.1em; text-transform: uppercase; margin-bottom: 8px; display: block; }
    main { background: transparent !important; }
</style>

<video autoplay muted loop playsinline id="video-bg">
    <source src="assets/videos/background.mp4" type="video/mp4">
</video>
<div class="video-overlay"></div>

<div id="main-content" class="container mx-auto px-4 py-8 md:py-12 animate__animated animate__fadeInUp">
    <div class="max-w-2xl mx-auto glass-panel rounded-[30px] md:rounded-[40px] overflow-hidden">
        
        <div class="bg-teal-500 px-6 py-6 md:py-8">
            <h2 class="text-slate-900 text-2xl md:text-3xl font-black uppercase tracking-tighter">Nouvel Adhérent</h2>
            <p class="text-teal-900 font-bold text-xs uppercase opacity-80">Enregistrement système Dechouke Grès</p>
        </div>
        
        <form action="" method="POST" enctype="multipart/form-data" class="p-6 md:p-10 space-y-6">
            <?= $message ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label>Nom de famille</label>
                    <input type="text" name="nom" required class="w-full px-5 py-4 glass-input rounded-2xl outline-none transition-all">
                </div>
                <div>
                    <label>Prénom</label>
                    <input type="text" name="prenom" required class="w-full px-5 py-4 glass-input rounded-2xl outline-none transition-all">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label>Téléphone</label>
                    <input type="text" name="telephone" placeholder="+509..." class="w-full px-5 py-4 glass-input rounded-2xl outline-none transition-all">
                </div>
                <div>
                    <label>Email personnel</label>
                    <input type="email" name="email" placeholder="exemple@mail.com" class="w-full px-5 py-4 glass-input rounded-2xl outline-none transition-all">
                </div>
            </div>

            <div>
                <label>Date de naissance</label>
                <input type="date" name="date_naissance" class="w-full px-5 py-4 glass-input rounded-2xl outline-none transition-all">
            </div>

            <div class="bg-white/5 p-6 rounded-2xl border border-white/5">
                <label>Photo de profil (Format .jpg ou .png)</label>
                <input type="file" name="photo" class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-6 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-teal-500 file:text-slate-900 hover:file:bg-teal-400 transition cursor-pointer">
            </div>

            <div class="flex flex-col md:flex-row items-center justify-between gap-6 pt-6 border-t border-white/10">
                <a href="membres.php" class="order-2 md:order-1 text-slate-400 hover:text-white transition-colors font-black uppercase text-[10px] tracking-widest">Annuler la saisie</a>
                <button type="submit" class="order-1 md:order-2 w-full md:w-auto bg-teal-500 hover:bg-teal-400 text-slate-900 font-black py-4 px-10 rounded-2xl transition-all transform hover:scale-105 active:scale-95 shadow-xl shadow-teal-500/20 uppercase text-xs">
                    Enregistrer l'adhérent
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const container = document.getElementById('main-content');
    const links = document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not([href^="tel:"])');

    links.forEach(link => {
        link.addEventListener('click', function(e) {
            if (link.hostname === window.location.hostname) {
                e.preventDefault();
                const destination = this.href;
                container.classList.remove('animate__fadeInUp');
                container.classList.add('animate__fadeOutDown');
                setTimeout(() => { window.location.href = destination; }, 500);
            }
        });
    });
});
</script>

</main> </body>
</html>