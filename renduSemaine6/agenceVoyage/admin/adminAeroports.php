<?php
session_start();
require_once('../../config.php');
if (!isset($_SESSION['email'])) {
    header("Location: /accueil.php");
    exit();
}

$userEmail = $_SESSION['email'];
$stmt = $bdd->prepare("SELECT type, prenom FROM utilisateurs WHERE mail = :email");
$stmt->execute(array(':email' => $userEmail));
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt = $bdd->prepare("SELECT * from aeroports ");
$stmt->execute();
$aeroports = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($user && $user['type'] === 'admin') {
    if (isset($_GET['logout'])) {
        session_destroy();
        header("Location: ../accueil.php");
        exit();
    }
  
} else {
    header("Location: /accueil.php"); 
    exit();
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
if (isset($_POST['idAeroport'],  $_POST['aeroport'],$_POST['ville'], $_POST['region'], $_POST['pays'], $_POST['latitude'], $_POST['longitude'])) {
    $idAeroport = $_POST['idAeroport'];
    $nomAeroport = $_POST['aeroport'];
    $ville = $_POST['ville'];
    $region = $_POST['region'];
    $pays = $_POST['pays'];
    $latitude = $_POST['latitude'];
    $longitude = $_POST['longitude'];

    $stmt = $bdd->prepare("UPDATE aeroports SET nomAeroport = :aeroport ,ville = :ville, region = :region, pays = :pays, latitude = :latitude, longitude = :longitude WHERE IATA_CODE = :idAeroport");
    $stmt->execute(array(
        ':ville' => $ville,
        ':region' => $region,
        ':pays' => $pays,
        ':latitude' => $latitude,
        ':longitude' => $longitude,
        ':idAeroport' => $idAeroport,
        ':aeroport' => $nomAeroport
    ));
}
if (isset($_POST['newCodeAeroport'],$_POST['newNom'], $_POST['newVille'], $_POST['newRegion'], $_POST['newPays'], $_POST['newLatitude'], $_POST['newLongitude'])) {
    $code = $_POST['newCodeAeroport'];
    $newNom = $_POST['newNom'];
    $newVille = $_POST['newVille'];
    $newRegion = $_POST['newRegion'];
    $newPays = $_POST['newPays'];
    $newLatitude = $_POST['newLatitude'];
    $newLongitude = $_POST['newLongitude'];

    $stmt = $bdd->prepare("INSERT INTO aeroports (IATA_CODE, nomAeroport, ville, region, pays, longitude, latitude) VALUES (:code, :newNom, :newVille, :newRegion, :newPays, :newLatitude, :newLongitude)");
    $stmt->execute(array(
        ':code' => $code,
        ':newNom' => $newNom,
        ':newVille' => $newVille,
        ':newRegion' => $newRegion,
        ':newPays' => $newPays,
        ':newLatitude' => $newLatitude,
        ':newLongitude' => $newLongitude,
    ));}
    if (isset($_POST['delAeroport'])) {
        $codeAeroport = $_POST['delAeroport'];
    
        $stmt = $bdd->prepare("DELETE FROM aeroports WHERE IATA_CODE = :codeAeroport");
        $stmt->execute(array(':codeAeroport' => $codeAeroport));
    }



    header("Location: adminAeroports.php"); 
    exit();
    
}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin</title>
    <link rel="stylesheet" type="text/css" href="../style.css" />
    <link rel="stylesheet" type="text/css" href="../dashboardCards.css" />

    <link rel="icon" href="../images/Icon.png" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  </head>
  <body>
    <header class="header" id="navbar">
      <img
        class="logo"
        width="100px"
        height="66"
        src="../images/logo.png"
        alt="logo"
      />

      <nav>
        <ul class="navigation">
        <li><a href="admin.php">Dashboard</a></li>
         
         <li style="color:orange; font-size:20px;  margin-left: 100px;
"><?php echo $user['prenom']; ?></li>
           <li class="logout"><a class="material-symbols-outlined" href="?logout">logout</a></li>

        </ul>
      </nav>


      
    </header>

    <!-- SIDEBAR -->
    <div class="sidebar">

    <a href="adminBillets.php" class="active" title='Vols'><span class="material-symbols-outlined">
airplane_ticket
</span></a>
<a href="adminAeroports.php" class="active" title='Aéroports'><span class="material-symbols-outlined">
flight
</span></a>
<a href="adminUtilisateurs.php" class="active" title='Utilisateurs'><span class="material-symbols-outlined">
group
</span></a>
    <a href="adminProfil.php" class="active" title='Profil'><span class="material-symbols-outlined">
person
</span></a>
<a href="adminCompagnies.php" class="active" title='Compagnies'><span class="material-symbols-outlined">
airlines</span></a>

<a href="adminArchives.php" class="active" title='Archives'><span class="material-symbols-outlined">
history
</span></a>

  </div>
  <div class="dashboard">
    <div class="tabSearch">  <input style="margin-top:10px;"type="text" id="searchInput" placeholder="Rechercher..." onkeyup="searchUsers()"> <?php echo  "<h3 class='tabSearchCount'>" .count($aeroports)." Aéroports</h3>" ?>
    <button id="openPopup" class="modifier" title="Ajouter" style="margin-top: 20px; width:10%"><span class="material-symbols-outlined">ADD</span>
</button>

</div>
  <table id='billetTable'>
            <thead>
                <tr>
                    <th>Code</th>
                    <th>Aeroport</th>
                    <th>Ville</th>
                    <th>Région</th>
                    <th>Pays</th>
                    <th>Latitude</th>
                    <th>Longitude</th>
                    <th>Actions</th>

                    
                </tr>
            </thead>
            <tbody>
                <?php foreach ($aeroports as $index => $aeroport): ?>
                    <?php $popupId = 'popup' . $index;?>

                    <tr>
                        <td><?php echo $aeroport['IATA_CODE']; ?></td>
                        <td><?php echo $aeroport['nomAeroport']; ?></td>

                        <td><?php echo $aeroport['ville']; ?></td>
                        <td><?php echo $aeroport['region']; ?></td>
                        <td><?php echo $aeroport['pays']; ?></td>
                        <td><?php echo $aeroport['latitude']; ?></td>
                        <td><?php echo $aeroport['longitude']; ?></td>

                        <td style="display:flex;justify-content: space-evenly;"><button id="openPopup<?php echo $index; ?>" class="modifier" title="Modifier"><span class="material-symbols-outlined">
edit
                </span></button>
                
<form action="adminAeroports.php" method="post" onsubmit="return confirmDelete();"> <input type="hidden" name="delAeroport" value="<?php echo $aeroport['IATA_CODE']; ?>">
<button type="submit" class="archive"  title="Supprimer"><span class="material-symbols-outlined">
delete
</span></button></form>
</td>

                    </tr>
                    <?php
echo '<div id="' . $popupId . '" class="popup">';
echo '<div class="popup-content">';
echo '<div class="sign_up_form">';
echo '<span class="close" id="closePopup' . $index . '"">&times;</span>';
echo '<h2>Modifier</h2>';
echo '<form  action="adminAeroports.php" method="post" id="signUp">';
echo '<input  name="idAeroport" type="hidden" value="' . $aeroport['IATA_CODE'] . '" />';
echo '<label for="aeroport" >Aerport</label>';
echo '<input type="text" name="aeroport" value="' . $aeroport['nomAeroport'] . '" placeholder="' . $aeroport['nomAeroport'] . '" />';

echo '<label for="ville">Ville</label>';
echo '<input type="text" name="ville" value="' . $aeroport['ville'] . '" placeholder="' . $aeroport['ville'] . '" />';

echo '<label for="region">Région</label>';
echo '<input type="text" maxlength="2" style="text-transform:uppercase" name="region" value="' . $aeroport['region'] . '" placeholder="' . $aeroport['region'] . '" />';

echo '<label for="pays">Pays</label>';
echo '<input type="text" maxlength="3" style="text-transform:uppercase" name="pays" value="' . $aeroport['pays'] . '" placeholder="' . $aeroport['pays'] . '" />';

echo '<label for="latitude">Latitude</label>';
echo '<input type="text" name="latitude" value="' . $aeroport['latitude'] . '" placeholder="' . $aeroport['latitude'] . '" />';

echo '<label for="longitude">Longitude</label>';
echo '<input type="text" name="longitude" value="' . $aeroport['longitude'] . '" placeholder="' . $aeroport['longitude'] . '" />';

echo '<button class="signup_button" name="signUp" type="submit">Confirmer</button>';

echo '</form>';
echo '</div>';
echo '</div>';
echo '</div>';
//script
echo '<script>';
echo 'document.getElementById("openPopup' . $index . '").addEventListener("click", function () {';
echo 'document.getElementById("' . $popupId . '").style.display = "block";';
echo '});';
echo 'document.getElementById("closePopup' . $index . '").addEventListener("click", function() {';
    echo 'document.getElementById("' . $popupId . '").style.display = "none";';
    echo '});';
echo '</script>';
?>
 <?php endforeach; ?>
            </tbody>
        </table>
  </div>
  <div id="popup" class="popup">
  <div class="popup-content">
    <div class="sign_up_form">
      <span class="close" id="closePopup">&times;</span>
      <h2>Ajouter</h2>
      <form action="adminAeroports.php" method="post" id="signUp">
        <label for="newCodeAeroport">Code</label>
        <input name="newCodeAeroport" style="text-transform:uppercase" maxlength="3" type="text"/>
        <label for="newNom">Aeroport</label>
        <input type="text" name="newNom"/>
        <label for="newVille">Ville</label>
        <input type="text" name="newVille"/>

        <label for="newRegion">Région</label>
        <input type="text" style="text-transform:uppercase" name="newRegion" maxlength="2"/>
        <label for="newPays">Pays</label>
        <input type="text" style="text-transform:uppercase" name="newPays" maxlength="3"/>
        <label for="latitude">Latitude</label>
        <input type="number" name="newLatitude"/>
        <label for="longitude">Longitude</label>
        <input type="number" name="newLongitude"/>
        
<button class="signup_button" name="signUp" type="submit">Confirmer</button>

      </form>
    </div>
  </div>
</div>


    <script src="../script.js"></script>
    <script>
        function logout() {
            window.location.href = "accueil.html";
        }
        function confirmDelete() {
        return confirm("Etes vous sûre de vouloir définitivement supprimer cet Aéroport?");
    }
        function searchUsers() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("billetTable");
        tr = table.getElementsByTagName("tr");

        for (i = 0; i < tr.length; i++) {
            td = tr[i].getElementsByTagName("td");
            for (var j = 0; j < td.length; j++) {
                if (td[j]) {
                    txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                        break; 
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }
    }
    </script>
</body>
</html>