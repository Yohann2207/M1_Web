<?php
session_start();
require_once "conn.php"; 

$pdo = $conn;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérer les données du formulaire
    $login = $_POST["login"];
    $password = $_POST["password"];
        try {
            // Rechercher l'utilisateur dans la base de données
            $prep = $pdo->prepare("SELECT * FROM user WHERE user_login = ?");
            $prep->execute([$login]);
            $user = $prep->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user["user_password"])) {
                $_SESSION["user_id"] = $user["user_id"];
                $_SESSION["user_login"] = $user["user_login"];
                header("Location: menu.php");
                exit;
            } else {
                // Identifiants incorrects
                header("Location: index.html");
            }
        } catch (PDOException $e) {
            $error = $e->getMessage();
        }
    }
?>