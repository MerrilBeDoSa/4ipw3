<?php
// Démarre la session immédiatement
//session_start();

// Inclure le modèle
require_once realpath(__DIR__ . '/../model/login.php');
require_once __DIR__ . '/../view/template.php';
require_once __DIR__ . '/../view/login.php';



// Démarrer la session si nécessaire
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}



function require_login() {
    if (!isset($_SESSION['login']['is_logged']) || !$_SESSION['login']['is_logged']) {
        // Rediriger l'utilisateur non connecté vers la page de connexion
        header("Location: login.php");
        exit;
    }
}

function main_login()
{
    // Initialise les variables
    $action = @$_GET['action'] ?: "";
    $msg = '';
    $menu = get_menucsv();

    // Gestion de la déconnexion via GET
    if ($action === 'logout') {
        unset($_SESSION['login']);
        session_destroy(); // Détruit la session
        // Redirection vers accueil avec confirmation
        $_SESSION['flash_message'] = "Vous avez été déconnecté avec succès.";
        //header("Location: /4ipw3/public/index.php");
        header("Location: ?page=home");
        exit;
    }

    // Gestion de la connexion via POST
    if (isset($_POST['set_login'])) {
        $login = $_POST['my_login'] ?? '';
        $password = $_POST['my_password'] ?? '';

        list($is_valid, $user_name, $role) = login_validate($login, $password);

        if ($is_valid) {
            $_SESSION['login']['is_logged'] = true;
            $_SESSION['login']['name'] = $user_name;
            $_SESSION['login']['role'] = $role;

            header("Location: .");
            exit; // Stoppe le script
        } else {
            unset($_SESSION['login']);
            $msg = "Identifiant ou mot de passe incorrect. Veuillez réessayer.";
        }
    }


    echo html_head($menu);
    // Affiche le contenu HTML
    echo <<<HTML
    <h1>Connexion</h1>
HTML;

    if (isset($_SESSION['login']['is_logged']) && $_SESSION['login']['is_logged']) {
        echo html_user_status(true, $_SESSION['login']['name']);
        echo html_logout_button();
    } else {
        echo html_user_status(false);
        echo form_login();

    }
    echo html_foot();
    echo html_message($msg);

}


// Fonction pour afficher le statut de l'utilisateur (connecté ou anonyme)

function display_user_status() {
    if (isset($_SESSION['login']['is_logged']) && $_SESSION['login']['is_logged']) {
        $name = htmlspecialchars($_SESSION['login']['name']);
        return "<div class='user-status logged-in'>Bonjour, <strong>$name</strong> 👋. Vous êtes identifié.</div>";
    } else {
        return "<div class='user-status not-logged-in'>👤 Utilisateur non identifié.</div>";
    }
}

?>
