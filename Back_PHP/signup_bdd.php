<?php
require_once "conn.php"; 
$pdo = $conn;

try {
    // Vérifier si le formulaire est soumis
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Récupérer et valider les données du formulaire
        $login = $_POST["login"];
        $compte_id = ($_POST["compte_id"]);
        $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL); // Validation email
        $password = $_POST["password"];

        $hashed_password = password_hash($password, PASSWORD_DEFAULT); // Hashage du mdp

        $sql = "INSERT INTO user (user_login, user_password, user_compte_id, user_mail)
                VALUES (:login, :password, :compte_id, :email)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ":login" => $login,
            ":compte_id" => $compte_id,
            ":email" => $email,
            ":password" => $hashed_password
        ]);
        header("Location: index.html"); 
        exit;
    }
} catch (PDOException $e) {
    $error = ($e->getMessage());
}
?>
