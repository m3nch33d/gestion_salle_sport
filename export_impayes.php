<?php
require_once 'config/db.php';

// 1. Nom du fichier avec la date du jour
$filename = "Liste_Relances_Impayes_" . date('d-m-Y') . ".csv";

// 2. Headers pour le téléchargement
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $filename);

// 3. Sortie PHP
$output = fopen('php://output', 'w');

// 4. En-tête du tableau CSV (Bom pour Excel - gère les accents)
fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
fputcsv($output, ['NOM DU MEMBRE', 'PRENOM', 'TELEPHONE', 'STATUT']);

// 5. Requête SQL des impayés (Même logique que ton Dashboard)
$sql = "SELECT m.nom, m.prenom, m.telephone, m.statut
        FROM membres m 
        LEFT JOIN souscriptions s ON m.id = s.id_membre AND s.date_fin >= CURDATE()
        WHERE s.id IS NULL AND m.statut = 'actif'
        ORDER BY m.nom ASC";

$stmt = $pdo->query($sql);

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, [
        mb_strtoupper($row['nom']), 
        $row['prenom'], 
        $row['telephone'], 
        'A RELANCER'
    ]);
}

fclose($output);
exit();
?>