<?php
session_start();
require_once('../../config.php');
if (!isset($_SESSION['email'])) {
    header("Location: /accueil.php");
    exit();
}

$userEmail = $_SESSION['email'];
$stmt = $bdd->prepare("SELECT * FROM utilisateurs WHERE mail = :email");
$stmt->execute(array(':email' => $userEmail));
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt = $bdd->prepare("SELECT * from aeroports ");
$stmt->execute();
$aeroports = $stmt->fetchAll(PDO::FETCH_ASSOC);


if ($user && $user['type'] === 'compagnie') {
    $stmtAllBillets = $bdd->prepare("select v.*,ad.nomAeroport as departure ,ar.nomAeroport as arrivage from vols v, aeroports ad, aeroports ar where v.Adepart=ad.IATA_CODE and v.Aarrive=ar.IATA_CODE and softDelete <1 and noCompagnies =:noCompagnie");
    $stmtAllBillets->execute(array(':noCompagnie' => $user['nom']));
    $billets = $stmtAllBillets->fetchAll(PDO::FETCH_ASSOC);
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
    if (isset($_POST['sDidVol'])) {
        
    
        $idVol = $_POST['sDidVol'];
    
        $stmt = $bdd->prepare("UPDATE VOLS SET softDelete = 1  WHERE idVol = :id");
        $stmt->execute([':id' => $idVol]);
    
        
    }
    if (isset($_POST['idVol'],$_POST['dateVol'],$_POST['jourSemaine'],$_POST['heureDepart'],$_POST['heureArrive'])) {
        $idVol = $_POST['idVol'];
        $jourSemaine = getDayNum($_POST['jourSemaine']);
        $dateVol = date('Y-m-d', strtotime($_POST['dateVol']));
        $heureDepart = $_POST['heureDepart'];
        $heureArrive =  $_POST['heureArrive'];
    
        $stmt = $bdd->prepare("UPDATE vols SET jourSemaine = :jourSemaine, dateVol = :dateVol, heureDepart = :heureDepart , heureArrive = :heureArrive WHERE idVol = :idVol");
        $stmt->execute(array(':jourSemaine' => $jourSemaine, ':dateVol' => $dateVol, ':heureDepart'=>$heureDepart,':heureArrive'=>$heureArrive,':idVol'=>$idVol));
    }
    if (isset($_POST['newIdVol'], $_POST['newNumVol'],$_POST['newDateVol'],$_POST['newJourSemaine'],$_POST['newHeureDepart'],$_POST['newHeureArrive'],$_POST['distance'],$_POST['depart'],$_POST['arrive'])) {
        $newNumVol = $_POST['newNumVol'];
        $newDateVol = date('Y-m-d', strtotime($_POST['newDateVol']));
        $newJourSemaine = getDayNum($_POST['newJourSemaine']);
        $newHeureDepart = $_POST['newHeureDepart'];
        $newHeureArrive = $_POST['newHeureArrive'];
        $distance = $_POST['distance'];
        $depart = $_POST['depart'];
        $arrive = $_POST['arrive'];
    
        $stmt = $bdd->prepare("SELECT IATA_CODE FROM aeroports where nomAeroport = :depart");
        $stmt->execute(array(':depart'=>$depart));
        $departIataCode = $stmt->fetch(PDO::FETCH_ASSOC)['IATA_CODE'];
        $stmt = $bdd->prepare("SELECT IATA_CODE FROM aeroports where nomAeroport = :arrive");
        $stmt->execute(array(':arrive'=>$arrive));
        $arriveIataCode = $stmt->fetch(PDO::FETCH_ASSOC)['IATA_CODE'];
        $stmt = $bdd->prepare("INSERT INTO vols (numVol, dateVol, jourSemaine, heureDepart, heureArrive, distance, Adepart, Aarrive,noCompagnies) VALUES (:numVol, :dateVol, :jourSemaine, :heureDepart, :heureArrive, :distance, :depart, :arrive,:noCompagnies)");
        $stmt->execute(array(':numVol' => $newNumVol, ':dateVol' => $newDateVol, ':jourSemaine' => $newJourSemaine, ':heureDepart' => $newHeureDepart, ':heureArrive' => $newHeureArrive, ':distance' => $distance, ':depart' => $departIataCode, ':arrive' => $arriveIataCode,':noCompagnies'=>$user["nom"]));
    }
    header("Location: compagnieVols.php"); 
    exit();
}
function getDayName($jourSemaine) {
    switch ($jourSemaine) {
        case 1:
            return 'Lundi';
        case 2:
            return 'Mardi';
        case 3:
            return 'Mercredi';
        case 4:
            return 'Jeudi';
        case 5:
            return 'Vendredi';
        case 6:
            return 'Samedi';
        case 7:
            return 'Dimanche';
        default:
            return 'Invalide';
    }
}
function getDayNum($jourSemaine) {
    switch ($jourSemaine) {
        case 'Lundi':
            return 1;
        case 'Mardi':
            return 2;
        case 'Mercredi':
            return 3;
        case 'Jeudi':
            return 4;
        case 'Vendredi':
            return 5 ;
        case 'Samedi':
            return 6;
        case 'Dimanche':
            return 7;
        default:
            return 4;
    }}

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Compagnie</title>
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
          <li><a href="compagnie.php">Dashboard</a></li>
         
          <li style="color:orange; font-size:20px;  margin-left: 100px;
"><?php echo $user['prenom']; ?></li>
            <li class="logout"><a class="material-symbols-outlined" href="?logout">logout</a></li>


        </ul>
      </nav>


      
    </header>

    <!-- SIDEBAR -->
    <div class="sidebar">

    <a href="compagnieVols.php" class="active" title='Billets'><span class="material-symbols-outlined">
airplane_ticket
</span></a>
<a href="compagnieReservations.php" class="active" title='Reservations'><span class="material-symbols-outlined">
auto_stories
</span></a>

    <a href="compagnieProfil.php" class="active" title='Profil'><span class="material-symbols-outlined">
person
</span></a>


  </div>
  </div>
  <div class="dashboard">
    <div class="tabSearch">  <input style="margin-top:10px;"type="text" id="searchInput" placeholder="Rechercher..." onkeyup="searchUsers()"> <?php echo  "<h3 class='tabSearchCount'>" .count($billets)." Vols</h3>" ?>
    <button id="openPopup" class="modifier" title="Ajouter" style="margin-top: 20px; width:10%"><span class="material-symbols-outlined">ADD</span>
</button>

</div>
  <table id='billetTable'>
            <thead>
                <tr>
                    <th>Id</th>
                    <th>numéro Vol</th>
                    <th>Date Vol</th>
                    <th>Jour Semaine</th>
                    <th>Heure Départ</th>
                    <th>Heure Arrivée</th>
                    <th>Aéroport Départ</th>
                    <th>Aéroport Arrivée</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($billets as $index => $billet): ?>
                    <?php $popupId = 'popup' . $index;?>

                    <tr>
                        <td><?php echo $billet['idVol']; ?></td>
                        <td><?php echo $billet['numVol']; ?></td>
                        <td><?php echo $billet['dateVol']; ?></td>
                        <td><?php echo getDayName($billet['jourSemaine']); ?></td>
                        <td><?php echo $billet['heureDepart']; ?></td>
                        <td><?php echo $billet['heureArrive']; ?></td>
                        <td><?php echo $billet['departure']; ?></td>
                        <td><?php echo $billet['arrivage']; ?></td>

                        <td style="display:flex;justify-content: space-evenly;"><button id="openPopup<?php echo $index; ?>" class="modifier" title="Modifier"><span class="material-symbols-outlined">
edit
                </span></button>
<form action="compagnieVols.php" onsubmit="return confirmDelete();" method="post"> <input type="hidden" name="sDidVol" value="<?php echo $billet['idVol']; ?>">
</button><button type="submit" class="archive"  title="Archiver"><span class="material-symbols-outlined">
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
echo '<form  action="compagnieVols.php" method="post" id="signUp">';
echo '<input  name="idVol" type="hidden" value="' . $billet['idVol'] . '" />';
echo '<label for="dateVol" >Nom</label>';
echo '<input type="date" name="dateVol" value="' . $billet['dateVol'] . '" placeholder="' . $billet['dateVol'] . '" />';
echo '<label for="jourSemaine" >Jour de Semaine</label>';
echo'<select name="jourSemaine" >';
echo'<option value="Lundi">Lundi</option>';
echo'<option value="Mardi">Mardi</option>';
echo'<option value="Mercredi">Mercredi</option>';
echo'<option value="Jeudi">Jeudi</option>';
echo'<option value="Vendredi">Vendredi</option>';
echo'<option value="Samedi">Samedi</option>';
echo'<option value="Dimanche">Dimanche</option>';
echo'</select>';
echo '<label for="heureDepart" >Heure de départ</label>';
echo '<input type="time" name="heureDepart" value="' . $billet['heureDepart'] . '" placeholder="' . $billet['heureDepart'] . '" />';
echo '<label for="heureArrive" >Heure d\'arrivée</label>';
echo '<input type="time" name="heureArrive" value="' . $billet['heureArrive'] . '" placeholder="' . $billet['heureDepart'] . '" />';
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
      <form action="compagnieVols.php" method="post" id="signUp">
        <input name="newIdVol" type="hidden"/>
        <label for="newNumVol">Numéro de Vol</label>
        <input type="number" name="newNumVol"/>
        <label for="newDateVol">Nom</label>
        <input type="date" name="newDateVol"/>
        <label for="newJourSemaine">Jour de Semaine</label>
        <select name="newJourSemaine">
          <option value="Lundi">Lundi</option>
          <option value="Mardi">Mardi</option>
          <option value="Mercredi">Mercredi</option>
          <option value="Jeudi">Jeudi</option>
          <option value="Vendredi">Vendredi</option>
          <option value="Samedi">Samedi</option>
          <option value="Dimanche">Dimanche</option>
        </select>
        <label for="newHeureDepart">Heure de départ</label>
        <input type="time" name="newHeureDepart" />
        <label for="newHeureArrive">Heure d'arrivée</label>
        <input type="time" name="newHeureArrive"/>
        <label for="distance">distance</label>
        <input type="number" name="distance"/>
<label for="depart">Aéroport de départ</label>
<input type="text" placeholder="Départ" list="liste_departs" name="depart" id="depart" />
<datalist id="liste_departs">
    <?php foreach ($aeroports as $aeroport): ?>
        <option value='<?php echo $aeroport["nomAeroport"]; ?>'>
    <?php endforeach; ?>
</datalist>

<label for="arrive">Aéroport d'arrivée</label>
<input type="text" placeholder="Arrivée" list="liste_arrivees" name="arrive" id="arrive" />
<datalist id="liste_arrivees">
    <?php foreach ($aeroports as $aeroport): ?>
        <option value='<?php echo $aeroport["nomAeroport"]; ?>'>
    <?php endforeach; ?>
</datalist>



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
        return confirm("Etes vous sûre de vouloir définitivement supprimer ce vol?");
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