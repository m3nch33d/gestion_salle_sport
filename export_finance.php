<?php
require_once 'includes/securite.php';
require_once 'config/db.php';

// 1. Récupération des données (même logique que ton rapport)
$sql = "SELECT MONTH(date_paiement) as mois, SUM(montant_paye) as total 
        FROM paiements 
        WHERE YEAR(date_paiement) = YEAR(CURDATE()) 
        GROUP BY MONTH(date_paiement)
        ORDER BY mois DESC";

try {
    $stmt = $pdo->query($sql);
    $donnees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $nom_mois = [
        1=>"Janvier", 2=>"Fevrier", 3=>"Mars", 4=>"Avril", 5=>"Mai", 6=>"Juin", 
        7=>"Juillet", 8=>"Aout", 9=>"Septembre", 10=>"Octobre", 11=>"Novembre", 12=>"Decembre"
    ];

    // 2. Configuration des headers pour forcer le téléchargement du fichier CSV
    $filename = "Rapport_Financier_DechoukeGres_" . date('Y-m-d') . ".csv";
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="' . $filename . '"');

    // 3. Création du fichier de sortie (mémoire tampon)
    $output = fopen('php://output', 'w');

    // Ajout de la ligne d'en-tête (Colonnes du tableau)
    fputcsv($output, ['MOIS', 'REVENUS (HTG)', 'STATUT']);

    // 4. Insertion des données
    foreach ($donnees as $ligne) {
        fputcsv($output, [
            $nom_mois[$ligne['mois']],
            $ligne['total'],
            'Encaisse'
        ]);
    }

    fclose($output);
    exit;

} catch (PDOException $e) {
    die("Erreur lors de l'exportation : " . $e->getMessage());
}
?>