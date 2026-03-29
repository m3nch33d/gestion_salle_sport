<?php 
require_once 'config/db.php'; 
include 'includes/header.php'; 

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM membres WHERE id = ?");
$stmt->execute([$id]);
$m = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $tel = $_POST['telephone'];
    $email = $_POST['email']; // AJOUT : Récupération de l'email
    $statut = $_POST['statut'];
    
    $photo = $m['photo']; 

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $upload_dir = 'assets/uploads/';
        if(!is_dir($upload_dir)) { mkdir($upload_dir, 0777, true); }

        $extension = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $nouveau_nom = time() . '_' . uniqid() . '.' . $extension;
        $destination = $upload_dir . $nouveau_nom;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $destination)) {
            if (!empty($m['photo']) && $m['photo'] != 'default.png' && file_exists($upload_dir . $m['photo'])) {
                unlink($upload_dir . $m['photo']);
            }
            $photo = $nouveau_nom;
        }
    }

    // AJOUT : "email=?" dans la requête SQL
    $sql = "UPDATE membres SET nom=?, prenom=?, telephone=?, email=?, statut=?, photo=? WHERE id=?";
    $pdo->prepare($sql)->execute([$nom, $prenom, $tel, $email, $statut, $photo, $id]);
    
    echo "<script>window.location.href='membres.php';</script>";
}
?>

<style>
    body { margin: 0; background: url('https://images.unsplash.com/photo-1534438327276-14e5300c3a48?q=80&w=2070') no-repeat center center fixed; background-size: cover; }
    body::before { content: ""; position: fixed; inset: 0; background: radial-gradient(circle at center, rgba(15, 23, 42, 0.4) 0%, rgba(2, 6, 23, 0.9) 100%); z-index: -1; }
    .glass-card { background: rgba(15, 23, 42, 0.65) !important; backdrop-filter: blur(40px); border: 1px solid rgba(255, 255, 255, 0.1); border-radius: 40px; }
    .input-glass { background: rgba(255, 255, 255, 0.08) !important; border: 1px solid rgba(255, 255, 255, 0.15) !important; color: white !important; border-radius: 15px; }
    .neon-text { color: #2dd4bf !important; text-shadow: 0 0 15px rgba(45, 212, 191, 0.5); }
    label { color: #2dd4bf !important; font-weight: 800; text-transform: uppercase; font-size: 0.75rem; }
</style>

<div class="container mx-auto px-4 py-8 md:py-12 relative z-10">
    <div class="text-center mb-10">
        <h1 class="text-4xl md:text-5xl font-black text-white uppercase italic tracking-tighter">Modifier <span class="neon-text">Membre</span></h1>
    </div>

    <div class="max-w-2xl mx-auto glass-card p-8 md:p-14">
        <form method="POST" enctype="multipart/form-data" class="space-y-6">
            
            <div class="flex flex-col items-center mb-6">
                <img src="assets/uploads/<?= !empty($m['photo']) ? $m['photo'] : 'default.png' ?>" 
                     class="w-32 h-32 rounded-full object-cover border-4 border-teal-500/30 shadow-2xl bg-slate-800">
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label>Nom</label>
                    <input type="text" name="nom" value="<?= htmlspecialchars($m['nom']) ?>" required class="w-full input-glass px-5 py-3 font-bold outline-none">
                </div>
                <div class="space-y-2">
                    <label>Prénom</label>
                    <input type="text" name="prenom" value="<?= htmlspecialchars($m['prenom']) ?>" required class="w-full input-glass px-5 py-3 font-bold outline-none">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label>Téléphone</label>
                    <input type="text" name="telephone" value="<?= htmlspecialchars($m['telephone']) ?>" required class="w-full input-glass px-5 py-3 font-bold outline-none">
                </div>
                <div class="space-y-2">
                    <label>Email Personnel</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($m['email']) ?>" required class="w-full input-glass px-5 py-3 font-bold outline-none">
                </div>
            </div>

            <div class="space-y-2">
                <label>Statut</label>
                <select name="statut" class="w-full input-glass px-5 py-3 font-bold outline-none cursor-pointer">
                    <option value="actif" <?= $m['statut'] == 'actif' ? 'selected' : '' ?> class="bg-slate-900">Actif</option>
                    <option value="inactif" <?= $m['statut'] == 'inactif' ? 'selected' : '' ?> class="bg-slate-900">Inactif</option>
                </select>
            </div>

            <div class="space-y-2 bg-white/5 p-4 rounded-xl border border-white/10">
                <label>Changer la photo</label>
                <input type="file" name="photo" class="w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-teal-500 file:text-slate-900 hover:file:bg-teal-400 cursor-pointer">
            </div>

            <div class="pt-6 flex flex-col md:flex-row gap-4">
                <button type="submit" class="w-full bg-teal-500 hover:bg-teal-400 text-slate-900 font-black py-4 rounded-2xl transition-all shadow-xl uppercase text-sm">
                    Enregistrer
                </button>
                <a href="membres.php" class="w-full bg-white/5 hover:bg-white/10 text-white border border-white/10 font-bold py-4 rounded-2xl transition-all text-center uppercase text-sm flex items-center justify-center">
                    Annuler
                </a>
            </div>
        </form>
    </div>
</div>