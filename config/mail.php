<?php
// config/mail.php

function envoyerEmailRecuperation($email_destinataire, $lien) {
    // Ta clé API Resend
    $apiKey = 're_iSXyqnu3_Lq7W7fSXZewWeJPSBkuaUYVa'; 

    $data = [
        "from" => "Dechouke Fitness <onboarding@resend.dev>",
        "to" => [$email_destinataire], 
        "subject" => "Réinitialisation de mot de passe",
        "html" => "
            <div style='font-family: sans-serif; border: 2px solid #14b8a6; padding: 25px; border-radius: 20px; background-color: #f8fafc;'>
                <h2 style='color: #0f172a; margin-top: 0;'>Bonjour Coach,</h2>
                <p style='color: #334155; font-size: 16px;'>Tu as demandé à réinitialiser ton mot de passe pour l'administration de <strong>Dechouke Fitness</strong>.</p>
                <div style='text-align: center; margin: 30px 0;'>
                    <a href='$lien' style='background-color: #14b8a6; color: white; padding: 15px 30px; text-decoration: none; border-radius: 50px; font-weight: bold; font-size: 18px; display: inline-block;'>RÉINITIALISER MON MOT DE PASSE</a>
                </div>
                <p style='color: #ef4444; font-weight: bold;'>Attention : Ce lien expire dans exactement 2 minutes.</p>
                <hr style='border: 0; border-top: 1px solid #e2e8f0; margin: 20px 0;'>
                <p style='font-size: 12px; color: #94a3b8;'>Si tu n'es pas à l'origine de cette demande, ignore simplement cet e-mail.</p>
            </div>
        "
    ];

    $ch = curl_init('https://api.resend.com/emails');
    
    // Options de l'envoi
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json'
    ]);
    
    // --- CORRECTION POUR L'ERREUR CODE 0 (SSL) ---
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    // ----------------------------------------------

    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Debugging : Affiche l'erreur si ça échoue encore
    if ($httpCode !== 200 && $httpCode !== 201) {
        echo "<div style='background:red; color:white; padding:15px; border-radius:10px; margin:10px;'>";
        echo "<strong>ERREUR RESEND ($httpCode) :</strong> " . htmlspecialchars($result);
        echo "</div>";
    }

    return $result;
}