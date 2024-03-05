<?php
$host = 'localhost';
$dbname = 'agencedevoyage';
$username = 'root';
$password = '';
$connection = new mysqli($host, $username, $password, $dbname);

try {
    $bdd = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Active le mode d'erreur PDO pour afficher les erreurs
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur : " . $e->getMessage());
}
?>
