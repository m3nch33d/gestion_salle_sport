<?php
require_once 'includes/securite.php';
require_once 'config/db.php';

// Cette ligne remplace tous les anciens require manuels !
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    // Récupération des infos du membre
    $stmt = $pdo->prepare("SELECT nom, prenom, email FROM membres WHERE id = ?");
    $stmt->execute([$id]);
    $membre = $stmt->fetch();

    if ($membre && filter_var($membre['email'], FILTER_VALIDATE_EMAIL)) {
        $mail = new PHPMailer(true);

        try {
            // --- CONFIGURATION SERVEUR SMTP (Exemple GMAIL) ---
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'augustinkarl321@gmail.com'; // Ton adresse Gmail
            $mail->Password   = 'edef phtk vqvf efgq'; // MOT DE PASSE D'APPLICATION
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;
            $mail->CharSet    = 'UTF-8';

            // --- DESTINATAIRES ---
            $mail->setFrom('augustinkarl321@gmail.com', 'Dechouke Grès Fitness');
            $mail->addAddress($membre['email'], $membre['prenom'] . ' ' . $membre['nom']);

            // --- CONTENU ---
            $mail->isHTML(true);
            $mail->Subject = 'Notification d\'expiration - Dechouke Grès Fitness';
            
            // Ton message personnalisé
            $mail->Body = "
                <div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                    <h2 style='color: #14b8a6;'>Bonjour " . htmlspecialchars($membre['prenom']) . ",</h2>
                    <p>Nous vous informons que votre abonnement a <strong>expiré</strong>.</p>
                    <p>Afin de continuer à bénéficier de nos services sans interruption, nous vous invitons à procéder à son renouvellement dans les plus brefs délais.</p>
                    <p>Si vous avez besoin d’assistance ou d’informations supplémentaires, n’hésitez pas à nous contacter.</p>
                    <br>
                    <p>Cordialement,<br>
                    <strong>L'équipe Dechouke Grès Fitness</strong></p>
                </div>";

            $mail->send();
            header("Location: rapports.php?msg=success");
        } catch (Exception $e) {
            // En cas d'erreur, on l'affiche pour déboguer
            die("Erreur lors de l'envoi : {$mail->ErrorInfo}");
        }
    } else {
        header("Location: rapports.php?msg=error_email");
    }
}