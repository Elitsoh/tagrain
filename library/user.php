<?php
// user.php

function hasSession() {
    if (!isset($_SESSION['user'])) {
        header("Location: login.php");
        exit;
    }
}

function authenticate(PDO $pdo, $username, $password) {
    $sql = 'SELECT * FROM user WHERE username=?';
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute(array($username))) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            unset($user['password']);
            return $user;
        }
    }
    return false;
}

// http://blogger.com/account-validation.php?email=toto@gmail.com&token=465sdf6sdf44s6f5
function signup(PDO $pdo, $username, $email, $password) {
    $sql = "INSERT INTO user VALUES (NULL, :username, :email, :pass, :firstname, :lastname, :createdAt)";
    $stmt = $pdo->prepare($sql);

    $data = [
        'username' => $username,
        'email' => $email,
        'pass' => password_hash($password, PASSWORD_BCRYPT),
        'firstname' => '',
        'lastname' => '',
        'createdAt' => date('Y-m-d H:i:s'),
    ];

    if ($stmt->execute($data)) {
        return $stmt->rowCount();
    }
    return 0;
}

function isUserExists(PDO $pdo, $username) {
    $stmt = $pdo->prepare('SELECT username FROM user WHERE username=?');
    if ($stmt->execute(array($username))) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user !== false) {
            return true;
        }
    }
    return false;
}

