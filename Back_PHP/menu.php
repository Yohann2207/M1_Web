<?php
session_start();
if (empty($_SESSION["user_id"])) {
    header("Location: index.html"); 
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <title>Menu</title>
    </head>
    <body>
        <h2>Bienvenue, <?php echo $_SESSION["user_login"]; ?> !</h2>
        <a href="logout.php">Se déconnecter</a>
    </body>
</html>
