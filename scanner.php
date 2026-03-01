<?php 
require_once 'config/db.php'; 
include 'includes/header.php'; 

$message = "";
$nom_membre = "";

if (isset($_POST['id_membre']) && !empty($_POST['id_membre'])) {
    $id = $_POST['id_membre'];

    // 1. Vérifier si le membre existe et récupérer son nom
    $stmt_m = $pdo->prepare("SELECT nom, prenom FROM membres WHERE id = ?");
    $stmt_m->execute([$id]);
    $membre = $stmt_m->fetch();

    if ($membre) {
        $nom_membre = $membre['prenom'] . " " . $membre['nom'];
        
        // 2. Vérifier si l'abonnement est valide
        $stmt_s = $pdo->prepare("SELECT COUNT(*) FROM souscriptions WHERE id_membre = ? AND date_fin >= CURDATE()");
        $stmt_s->execute([$id]);
        $est_actif = $stmt_s->fetchColumn();

        if ($est_actif > 0) {
            // 3. Enregistrer la présence
            $stmt_p = $pdo->prepare("INSERT INTO presences (id_membre) VALUES (?)");
            $stmt_p->execute([$id]);
            $message = "success";
        } else {
            $message = "expire";
        }
    } else {
        $message = "inconnu";
    }
}
?>

<div class="container mx-auto px-4 py-12 text-center">
    <div class="max-w-md  mx-auto bg-white p-10 rounded-3xl shadow-2xl border-t-8 <?= ($message == 'success') ? 'border-green-500' : 'border-emerald-100' ?>">
        
        <h2 class="text-2xl font-black text-gray-800 mb-2">SCANNER D'ENTRÉE</h2>
        <p class="text-gray-400 text-sm mb-8 italic">Passez la carte devant le lecteur</p>

        <?php if ($message == "success"): ?>
    <div class="mb-6 p-6 bg-green-100 rounded-2xl">
        <p class="text-5xl mb-2">✅</p>
        <p class="text-green-700 font-bold text-xl uppercase">Accès Autorisé</p>
        <p class="text-green-600 font-medium"><?= $nom_membre ?></p>
    </div>
    <script>
        new Audio('https://actions.google.com/sounds/v1/foley/beeps_interaction.ogg').play();
    </script>
<?php elseif ($message == "expire"): ?>
    <div class="mb-6 p-6 bg-red-100 rounded-2xl">
        <p class="text-5xl mb-2">❌</p>
        <p class="text-red-700 font-bold text-xl">ABONNEMENT EXPIRÉ</p>
        <p class="text-red-600"><?= $nom_membre ?></p>
    </div>
    <script>
        new Audio('https://actions.google.com/sounds/v1/alarms/beep_short.ogg').play();
    </script>
<?php endif; ?>

        <form id="scanForm" action="" method="POST">
            <input type="text" name="id_membre" id="id_membre" autofocus 
                   class="opacity-0 absolute" autocomplete="off">
            
            <div class="animate-pulse flex flex-col items-center">
                <div class="w-24 h-24 border-4 border-dashed border-emerald-200 rounded-full flex items-center justify-center mb-4">
                    <span class="text-3xl">📡</span>
                </div>
                <p class="text-orange-400 font-semibold">En attente du scan...</p>
            </div>
        </form>
    </div>
</div>

<script>
    // --- ÉTAPE A : DÉFINITION DES SONS (AU DÉBUT DU SCRIPT) ---
    const soundSuccess = new Audio('assets/sounds/success.mp3'); 
    const soundError = new Audio('assets/sounds/error.mp3');

    // --- ÉTAPE B : LA FONCTION DE DÉCLENCHEMENT ---
    function playStatusSound() {
        const msg = "<?= $message ?>";
        if (msg === "success") {
            soundSuccess.play().catch(e => console.log("L'audio nécessite un clic sur la page."));
        } else if (msg === "expire" || msg === "inconnu") {
            soundError.play().catch(e => console.log("L'audio nécessite un clic sur la page."));
        }
    }

    // --- ÉTAPE C : LE RESTE DU CODE (LOGIQUE DE SCAN) ---
    const input = document.getElementById('id_membre');
    
    window.onload = () => {
        input.focus();
        // On appelle la fonction dès que la page charge
        playStatusSound();
    };

    // Déblocage de l'audio lors d'un clic
    document.addEventListener('click', () => {
        input.focus();
        // Trick pour "réveiller" l'audio du navigateur
        soundSuccess.muted = true;
        soundSuccess.play().then(() => { soundSuccess.muted = false; });
    });

    input.addEventListener('input', (e) => {
        if (e.target.value.length >= 1) {
            document.getElementById('scanForm').submit();
        }
    });

    <?php if ($message != ""): ?>
        setTimeout(() => { window.location.href = 'scanner.php'; }, 3000);
    <?php endif; ?>
</script>