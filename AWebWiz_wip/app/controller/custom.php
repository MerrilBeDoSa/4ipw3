<?php

require_once '../app/view/border.php';

// Fonction pour afficher la page
function main_border()
{
    return join("\n", [
        ctrl_head(),  // Inclure l'en-tête
        html_border_page(), // Afficher la vue
        html_foot() // Inclure le pied de page
    ]);
}

// Endpoint AJAX pour changer la bordure
if (isset($_POST['border_type'])) {
    $borderType = $_POST['border_type'];

    // Traitement de la logique ici (ex : enregistrer en base de données, logs, etc.)
    $response = [
        "status" => "success",
        "message" => "La bordure a été mise à jour avec succès !",
        "border" => $borderType
    ];

    // Répondre en JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
