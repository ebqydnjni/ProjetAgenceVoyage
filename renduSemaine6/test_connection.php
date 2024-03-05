<?php
require_once('config.php');
$mot_de_passe_clair = 'Momodidi1!';
$mot_de_passe_hache = password_hash($mot_de_passe_clair, PASSWORD_DEFAULT);
$utilisateur = "Lamino";


try {
    // Prépare et exécute la requête SQL
    $query = $bdd->query("INSERT INTO utilisateurs (prenom, nom, mail, mdp, type)
    VALUES ('$utilisateur', 'Thiam', 'john.doe@example.com', '$mot_de_passe_hache', 'administrateur');");
    
   /* // Récupère les résultats de la requête
    $vols = $query->fetchAll(PDO::FETCH_ASSOC);
    
    // Affiche les résultats
    foreach ($vols as $vol) {
        echo "ID : " . $vol['idVol'] . "<br>";
        echo "numéro : " . $vol['numVol'] . "<br>";
        echo "date : " . $vol['dateVol'] . "<br>";
        echo "<br>";
    }*/
    echo "Utilisateur : $utilisateur Ajouté avec succès";
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
