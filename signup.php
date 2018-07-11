<?php
// login.php
require 'init.php';
require LIB_PATH . DS . 'user.php';
require LIB_PATH . DS . 'validator.php';

$errors = [];
$email = $_POST['email'] ?? null;
$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;
$confirmPassword = $_POST['confirmPassword'] ?? null;


// Validation du formulaire.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (!validUsername($username, 3, 12)) {
        $errors[] = 'Identifiant incorrect';
    }
    if (!validEmail($email, 10, 80)) {
        $errors[] = 'Email incorrect';
    }
    if (!validPassword($password, $confirmPassword)) {
        $errors[] = 'Mot de passe invalid ou ne correspond pas';
    }

    if (empty($errors)) {
        //nettoyage des données
        $username = strip_tags($username);
        $email = strip_tags($email);
        $password = strip_tags($password);
    }

    if (signup($db, $username, $email, $password) === 1 ) {
        //  echo $db->lastInsertId();

        $user = authenticate($db, $username, $password);
        if ($user) {
            $_SESSION['user'] = $user;
            header('Location: index.php');
        }
    }
}

// Affichage de la vue.
$title = "Page de connexion";
$styles = [BASE_URL.'/views/'.THEME.'/css/signin.css'];

include THEME_PATH . DS . 'signup.phtml';