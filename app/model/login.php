<?php
/**
 * login_validate() vérifie que le nom d'utilisateur et le mot de passe existent et correspondent
 * dans le fichier login.csv.
 *
 * @param string $input_login    Nom d'utilisateur entré par l'utilisateur.
 * @param string $input_password Mot de passe entré par l'utilisateur.
 * @return array                 [0] : true si les informations sont valides, false sinon.
 *                               [1] : Identifiant de l'utilisateur (ou null).
 *                               [2] : Rôle de l'utilisateur (ou null).
 */
function login_validate($input_login, $input_password)
{
    try {
        // Lecture du fichier
        $fh = fopen('../asset/database/login.csv', 'r');
        while (!feof($fh)) {
            $ligne = fgets($fh);
            $user_info = explode(';', trim($ligne)); // Format attendu : login;password;role

            // Vérifie que le login et le mot de passe correspondent
            if (isset($user_info[0], $user_info[1]) && $user_info[0] == $input_login && $user_info[1] == $input_password) {
                // L'utilisateur a été identifié
                fclose($fh);
                return array(true, $user_info[0], $user_info[2] ?? null);
            }
        }
        // L'utilisateur n'a pas été identifié
        fclose($fh);
        return array(false, null, null);
    } catch (Exception $e) {
        echo "Problème lors de la lecture du fichier login.csv : " . $e->getMessage();
        return array(false, null, null);
    }
}
?>
