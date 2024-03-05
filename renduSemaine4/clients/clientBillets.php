

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

$sql2="SELECT * FROM aeroports";
    $stmt2 = $bdd->prepare($sql2);

    $stmt2->execute();
    $cities = $stmt2->fetchAll(PDO::FETCH_ASSOC);

if ($user && $user['type'] === 'client' ) {
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
    <title>Client</title>
    <link rel="stylesheet" type="text/css" href="../style.css" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <link rel="icon" href="../images/Icon.png" />
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
        <li><a href="client.php">Dashboard</a></li>
         
         <li style="color:orange; font-size:20px;  margin-left: 100px;
"><?php echo $user['prenom']; ?></li>
           <li class="logout"><a class="material-symbols-outlined" href="?logout">logout</a></li>

        </ul>
      </nav>


      
    </header>

    <!-- SIDEBAR -->
    <div class="sidebar">

    <a href="clientBillets.php" class="active" title='Billets'><span class="material-symbols-outlined">
airplane_ticket
</span></a>
<a href="clientReservations.php" class="active" title='Reservations'><span class="material-symbols-outlined">
auto_stories
</span></a>

    <a href="clientProfile.php" class="active" title='Profil'><span class="material-symbols-outlined">
person
</span></a>



  </div>

    <main>
    <content id="Recherche">
        <div class="mainTitle">
          <h2>Profitez au m<span class="ax">ax!</span></h2>
          <h3>Profitez de nos services de voyages inédits</h3>
        </div>
        <div class="searchBlock">
          <div class="voyageBlock1">
            <svg
              xmlns="http://www.w3.org/2000/svg"
              width="24"
              height="24"
              viewBox="0 0 24 24"
              fill="none"
            >
              <path
                d="M12 2C6.48 2 2 6.48 2 12C2 17.52 6.48 22 12 22C17.52 22 22 17.52 22 12C22 6.48 17.52 2 12 2ZM11 19.93C7.05 19.44 4 16.08 4 12C4 11.38 4.08 10.79 4.21 10.21L9 15V16C9 17.1 9.9 18 11 18V19.93ZM17.9 17.39C17.64 16.58 16.9 16 16 16H15V13C15 12.45 14.55 12 14 12H8V10H10C10.55 10 11 9.55 11 9V7H13C14.1 7 15 6.1 15 5V4.59C17.93 5.78 20 8.65 20 12C20 14.08 19.2 15.97 17.9 17.39Z"
                fill="#FA8B02"
              /></svg
            ><span class="fontResultat">Recherche</span>
          </div>

<?php
if(isset($_POST['depart'], $_POST['arrivee'])) {

require_once('../../config.php');
$villeDepart = $_POST['depart'];
$villeArrivee = $_POST['arrivee'];


    //select v.*,ad.ville as villeDepart,ar.ville as villeArrive, ad.nomAeroport as departure ,ar.nomAeroport as arrivage from vols v, aeroports ad, aeroports ar where v.Adepart=ad.IATA_CODE and v.Aarrive=ar.IATA_CODE Adepart = :aeroport_depart AND Aarrive = :aeroport_arrivee AND dateVol = :date_voyage OR heureDepart = :heure_voyage;


$sql = "SELECT 
v.*,
ad.ville AS villeDepart,
ar.ville AS villeArrive,
ad.nomAeroport AS departure,
ar.nomAeroport AS arrivage 
FROM 
vols v,
aeroports ad,
aeroports ar 
WHERE 
v.Adepart = ad.IATA_CODE 
AND v.Aarrive = ar.IATA_CODE
and ad.ville = :depart
and ar.ville = :arrivee;
";
$stmt = $bdd->prepare($sql);
$stmt->execute(array(':depart' => $villeDepart, ':arrivee' => $villeArrivee));
$vols = $stmt->fetchAll(PDO::FETCH_ASSOC);


$sql2="SELECT * FROM aeroports";
    $stmt2 = $bdd->prepare($sql2);

    $stmt2->execute();
    $cities = $stmt2->fetchAll(PDO::FETCH_ASSOC);}
   
   
   
  

    ?>

          <div class="search">
            <form class="searchForm" id="searchForm" method="post" action="">
              <input type="text" placeholder="Départ" name="depart" list="liste_departs" id="" />
              <datalist id="liste_departs">
              <?php foreach ($cities as $city): ?>
        <option value='<?php echo $city["ville"]; ?>'>
    <?php endforeach; ?>
  </datalist>
              <input type="text" placeholder="Arrivée" list="liste_arrivees" name="arrivee" id="" />
              <datalist id="liste_arrivees">
              <?php foreach ($cities as $city): ?>
        <option value='<?php echo $city["ville"]; ?>'>
    <?php endforeach; ?>
              </datalist>
              <button  class="searchButton" onclick="submitForm()">
                <svg
                  xmlns="http://www.w3.org/2000/svg"
                  width="24"
                  height="24"
                  viewBox="0 0 33 32"
                  fill="none"
                >
                  <path
                    d="M28.5003 28L22.519 22.008M25.8337 14C25.8337 17.0058 24.6396 19.8885 22.5142 22.0139C20.3888 24.1393 17.5061 25.3334 14.5003 25.3334C11.4945 25.3334 8.61186 24.1393 6.48645 22.0139C4.36104 19.8885 3.16699 17.0058 3.16699 14C3.16699 10.9942 4.36104 8.11156 6.48645 5.98614C8.61186 3.86073 11.4945 2.66669 14.5003 2.66669C17.5061 2.66669 20.3888 3.86073 22.5142 5.98614C24.6396 8.11156 25.8337 10.9942 25.8337 14V14Z"
                    stroke="white"
                    stroke-width="2"
                    stroke-linecap="round"
                  />
                </svg>
              </button>
            </form>
          </div>
        </div>
      </content>
      <div class='billet_main'>
  <?php
  if(isset($_POST['depart'], $_POST['arrivee']))  {
    require_once('../../config.php');
    $villeDepart = $_POST['depart'];
    $villeArrivee = $_POST['arrivee'];

    $sql = "SELECT 
              v.*,
              ad.ville AS villeDepart,
              ar.ville AS villeArrive,
              ad.nomAeroport AS departure,
              ar.nomAeroport AS arrivage 
            FROM 
              vols v,
              aeroports ad,
              aeroports ar 
            WHERE 
              v.Adepart = ad.IATA_CODE 
              AND v.Aarrive = ar.IATA_CODE
              AND ad.ville = :depart
              AND ar.ville = :arrivee;";
    $stmt = $bdd->prepare($sql);
    $stmt->execute(array(':depart' => $villeDepart, ':arrivee' => $villeArrivee));
    $vols = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql2 = "SELECT * FROM aeroports";
    $stmt2 = $bdd->prepare($sql2);
    $stmt2->execute();
    $cities = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    if ($vols) {
      if (count($vols) <2) {
        echo "<h3 class='result_count'>".count($vols)." Résultat</h3>";
      } else {
        echo "<h3 class='result_count'>".count($vols)." Résultats</h3>";
      }
      echo "<content id='billets' class='billets'>";
      foreach ($vols as $vol) {
        echo" <div class='billets_items'>"; 
        echo "<div class='billet_title'>";
        echo "<img src='../images/iconeBillet1.svg' alt='' class='iconeBillet' height='50px'><span>Negn<i class='go'>'go</i></span></div>";
        echo "<div class='billet_content'>";
        echo "<form id='' action='clientReservations.php' method='post'>";
        echo "<input type='hidden' name='id_vol' value='" . $vol['idVol'] . "'>";
        echo "<input type='hidden' name='id_client' value='" . $user['id'] ."'>";

        echo "<ul>";
        echo "<li>VOL</li>";
        echo "<li>" . $vol['numVol'] . "</li>";
        echo "</ul>";
        echo "<ul>";
        echo "<li>DATE</li>";
        echo "<li class='content_title'>" . $vol['dateVol'] . "</li>";
        echo "</ul>";
        echo "<ul>";
        echo "<li>HEURE</li>";
        echo "<li>" . $vol['heureDepart'] . "</li>";
        echo "</ul>";
        echo "</div>";

        echo "<div class='billets_items2'>";
        echo "<ul>";
        echo "<li style='margin-top:3px'>" . $user['prenom']." ".$user['nom']. "</li>";

        echo "<li> Départ : " . $vol['villeDepart'] . "</li>";
        echo "<li> Arrivée : " . $vol['villeArrive'] . "</li>";
        echo "<li> Heure : " . $vol['heureDepart'] . "</li>";
        echo "<li> Date : " . $vol['dateVol'] . "</li>";
        echo "<li><img class='codeBarre2' src='../images/codeBarre.svg' alt='' height='100'></li>";
        echo "</ul>";
        echo "</div>";
        echo "<img class='codeBarre' src='../images/codeBarre.svg' alt='' height='100'>";
        echo "</div>";
        echo "<div class='reserver'>";

        echo "<input  type='submit' value='Réserver' style=''>"; 
        echo "</div>";
        echo "</form>";


      }}
    }else {
      echo "<h3 class='result_count'>Aucun vol trouvé </h3>";
    }
    echo "</content>";
  
  ?>
</div>
</div>


      </div>

    </main>
    <script src="../script.js"></script>
    <script>
    function submitForm() {
        document.getElementById("searchForm").submit();
    }
    </script>
  </body>
</html>
