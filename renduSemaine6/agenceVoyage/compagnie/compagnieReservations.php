<?php
session_start();
require_once('../../config.php');
if (!isset($_SESSION['email'])) {
    header("Location: ../accueil.php");
    exit();
}

$userEmail = $_SESSION['email'];
$userPasword  = $_SESSION['password'];
$stmt = $bdd->prepare("SELECT type, prenom,nom,id FROM utilisateurs WHERE mail = :email");
$stmt->execute(array(':email' => $userEmail));
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$stmt = $bdd->prepare("SELECT DISTINCT b.*, v.*, ad.nomAeroport AS departure, ar.nomAeroport AS arrivage,ad.ville as villeDepart,ar.ville as villeArrive
FROM billets b
INNER JOIN vols v ON b.idVol = v.idVol
INNER JOIN aeroports ad ON v.Adepart = ad.IATA_CODE
INNER JOIN aeroports ar ON v.Aarrive = ar.IATA_CODE
where paye = 1
and noCompagnies= :noCompagnies");

$stmt->execute(array(':noCompagnies' => $user['nom']));
$billets = $stmt->fetchAll(PDO::FETCH_ASSOC);



if ($user && $user['type'] === 'compagnie' ) {
  if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../accueil.php");
    exit();
}

} else {
    header("Location: ../accueil.php"); 
    exit();
}

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
    <div class="tabSearch">  <input style="margin-top:10px;"type="text" id="searchInput" placeholder="Rechercher..." onkeyup="searchUsers()"> <?php echo  "<h3 class='tabSearchCount'>" .count($billets)." Réservations</h3>" ?>
</div>
  <table id='userTable'>

            <thead>
                <tr>
                    <th>Id</th>
                    <th>Départ</th>
                    <th>Arrivée</th>
                    <th>Prix</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($billets as $index=> $billet): ?>
                  <?php $popupId = 'popup' . $index;?>

                    <tr>
                        <td><?php echo $billet['idBillet']; ?></td>
                        <td><?php echo $billet['departure']; ?></td>
                        <td><?php echo $billet['arrivage']; ?></td>
                        <td><?php echo $billet['prix']; ?></td>
                        <?php
if ($billet["paye"]) {
    echo "<td style='color:green'>payé</td>";
} else {
    echo "<td style='color:red'>impayé</td>";
}
?>
<td style="display:flex;justify-content: space-evenly;"><button class="modifier" id="openPopup<?php echo $index; ?>" title="Voir Details"><span class="material-symbols-outlined">
visibility
</span>

</td>


                    </tr>

                <?php
$stmt = $bdd->prepare("SELECT * FROM utilisateurs WHERE id = :noClient");
$stmt->execute(array(':noClient' => $billet['noClient']));
$user2 = $stmt->fetch(PDO::FETCH_ASSOC);
 echo '<div id="' . $popupId . '" class="popup">';
 echo '<div class="popup-content">';
echo '<div class="sign_up_form">';
echo '<span class="close" id="closePopup' . $index . '"">&times;</span>';
echo '<h2>Détails</h2>';


$todayDate = date('Y-m-d');
echo '
<div class="receipt_container">
  <div class="receipt-header">
    <h1>Détails du billet</h1>
    <p>Date: '.$billet["dateAchat"].'</p>
  </div>

  <div class="receipt-items">
    <div class="item">
      <span>Prenom</span>
      <span>'.$user2['prenom'].'</span>
    </div>
    <div class="item">
      <span>Nom</span>
      <span'.$user2['nom'].'</span>
    </div>
    <div class="receipt-items">
    <div class="item">
      <span>Numéro client</span>
      <span>'.$user2['id'].'</span>
    </div><hr>
    <div class="item">
      <span>Numéro Billet</span>
      <span>'.$billet['idBillet'].'</span>
    </div>
  </div><hr>
  <div class="receipt-items">
    <div class="item">
      <span>Numéro Vol</span>
      <span>'.$billet['numVol'].'</span>
    </div>
    <div class="receipt-items">
    <div class="item">
      <span>Date Vol</span>
      <span>'.$billet['dateVol'].'</span>
    </div><hr>
    <div class="receipt-items">
    <div class="item">
      <span>Aeroport de départ</span>
      <span style="color:#eb8f1e;">'.$billet['departure'].'</span>
    </div>
    <div class="receipt-items">
    <div class="item">
      <span>Heure de départ</span>
      <span>'.$billet['heureDepart'].'</span>
    </div>
    <div class="item">
    <span>Ville de départ</span>
    <span>'.$billet['villeDepart'].'</span>
  </div><hr>
    <div class="receipt-items">
    <div class="item">
      <span>Aéroport d\'arrivée</span>
      <span style="color:#eb8f1e;">'.$billet['arrivage'].'</span>
    </div>
    <div class="receipt-items">
    <div class="item">
      <span>Heure d\'arrivée</span>
      <span>'.$billet['heureArrive'].'</span>
    </div>
    <div class="receipt-items">
    <div class="item">
      <span>Ville d\'arrivée</span>
      <span>'.$billet['villeArrive'].'</span>
    </div><hr>
<div class="total">
<span>Prix</span>
<span>'.$billet['prix'].'</span>
</div>';
if ($billet['paye']){
echo'<div class="total">
<span>Statut</span>
<span style="color:green">Payé</span>
</div>';}
else{
 echo' <div class="total">
  <span>Statut</span>
  <span style="color:red">Impayé</span>
  </div>';
  

  echo '<form  action="clientReservations.php" method="post" id="signUp">';

 echo'type="hidden" name="payBillet" value="' . $billet['idBillet'] . '">';
 

echo '<button style="margin-right:300px" class="signup_button" name="signUp" type="submit">Payer</button>';
echo '</form>';
}

echo '</div>';
echo '</div>';
echo '</div>';
echo '<script>';
echo 'document.getElementById("openPopup' . $index . '").addEventListener("click", function () {';
echo 'document.getElementById("' . $popupId . '").style.display = "block";';
echo '});';
echo 'document.getElementById("closePopup' . $index . '").addEventListener("click", function() {';
    echo 'document.getElementById("' . $popupId . '").style.display = "none";';
    echo '});';
    echo '</script>';

               endforeach; ?>
            </tbody>
        </table>
        
  </div>

    




    </main>
    <script src="script.js"></>
    <script>

function confirmDelete() {
        return confirm("Etes vous sûre de vouloir supprimer cette réservation?");
    }
    </script>
  </body>
</html>
