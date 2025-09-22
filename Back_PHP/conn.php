<?php
try {
    $conn = new PDO("mysql:host=localhost;dbname=login_m1", "root", "");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $message = $e->getMessage();
}
