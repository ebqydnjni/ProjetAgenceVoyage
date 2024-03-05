

<?php
session_start();
require_once('../config.php');
    $sql2="SELECT * FROM aeroports";
    $stmt2 = $bdd->prepare($sql2);

    $stmt2->execute();
    $cities = $stmt2->fetchAll(PDO::FETCH_ASSOC);
 if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signUp'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $mail = $_POST['mail'];
    $mot_de_passe = $_POST['mdp'];
    $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_DEFAULT);
    $typeUtilisateur = $_POST['type'];

    $sql = "INSERT INTO utilisateurs (prenom, nom, mail, mdp, type) VALUES (:nom, :prenom, :mail, :mot_de_passe, :typeUtilisateur)";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':mail', $mail);
    $stmt->bindParam(':mot_de_passe', $mot_de_passe_hache);
    $stmt->bindParam(':typeUtilisateur', $typeUtilisateur);
    $stmt->execute();
    echo "<script>alert('Inscription avec succès');</script>";
    echo "<script> openSignIn()</script>";
}

// Login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signIn'])) {
  
    $email = $_POST['mail'];
    $password = $_POST["password"];


    $stmt = $bdd->prepare("SELECT * FROM utilisateurs WHERE mail = :email");
    $stmt->execute(array(':email' => $email));
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    echo $user["mdp"];
    echo('***********************************');
    echo $Password;


   

if ($user&& password_verify($password,$user['mdp'])) {
    $_SESSION['email'] = $email;
    if ($user['type'] == 'admin') {
        header("Location: admin/admin.php");

    } elseif ($user['type'] == 'client') {
        header("Location: clients/client.php");
        exit();
    }
} else {
    $error = "Invalid email or password.";
    echo $error;
}

}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Billets</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="icon" href="images/Icon.png" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  </head>
  <body>
    <header class="navbar" id="navbar">
    <a href="accueil.php">
    <img
        class="logo"
        width="100px"
        height="66"
        src="images/logo.png"
        alt="logo"
      />
    </a>

      <nav>
        <ul class="navigation">
          <li><a href="#Recherche">Rechercher</a></li>
          <li><a href="accueil.php">A Propos</a></li>
          <li><a href="accueil.php">Contact</a></li>
          <li class="connection"><a id="openPopup2" href="#">Connexion</a></li>
          <li class="inscription">
            <button id="openPopup" type="submit">Inscription</button>
          </li>
        </ul>
      </nav>

      <!-- Sign up-->

      <div id="popup" class="popup">
        <div class="popup-content">
          <div class="sign_up_form">
            <span class="close" id="closePopup">&times;</span>
            <h2>Inscription</h2>
            <form  action="accueil.php" method="post" id="signUp">
              <label for="name" >Nom</label>
              <input type="text" name="nom" placeholder="Nom" />
              <label for="text" >Prénom</label>
              <input type="text" name="prenom" placeholder="Prénom" />
              <label for="mail" >Mail</label>
              <input type="mail" name="mail" placeholder="adresse mail" />
              <label for="password" >Mot de passe</label>
              <input type="password" name = "mdp" placeholder="mot de passe" />
              <label for="type" name="type">Qui êtes vous</label>
              <select name="type" id="">
                <option value="client">Client</option>
                <option value="compagnie">Compagnie</option>
              </select>
              <button class="signup_button" name="signUp" onclick="signUp()">S'inscrire</button>
              


              <h5>
                vous avez un compte?
                <span class="sign_in_span" id="signin-link">Se connecter</span>
              </h5>
            </form>
          </div>
        </div>
      </div>

      <!-- Sign In-->
      <div id="popup2" class="popup2">
        <div class="popup2-content">
          <div class="sign_in_form">
            <span class="close2" id="closePopup2">&times;</span>
            <h2>Connexion</h2>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" id="signIn">

              <label for="mail">Mail</label>
              <input type="mail" placeholder="adresse mail" name="mail" />
              <label for="password" >Mot de passe</label>
              <input type="password" name="password" placeholder="mot de passe" />
              <button class="signin_button" onclick="signIn()" name="signIn">Se connecter</button>
              <h5>
                vous n'avez pas de compte?
                <span class="sign_up_span" id="signup-link">S'inscrire</span>
              </h5>
            </form>
          </div>
        </div>
      </div>
    </header>

    <!-- SIDEBAR

    <div class="sidebar">
      <ul>
        <li><a href="#">Home</a></li>
        <li><a href="#">About</a></li>
        <li><a href="#">Services</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </div>-->

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

require_once('../config.php');
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
              <button   class="searchButton" onclick="submitForm()">
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
    require_once('../config.php');
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
        echo "<div class='billets_items' onclick='openSignIn()'>"; 
        echo "<div class='billet_title'>";
        echo "<img src='images/iconeBillet1.svg' alt='' class='iconeBillet' height='50px'><span>Negn<i class='go'>'go</i></span></div>";
        echo "<div class='billet_content'>";
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
        echo "<li> Départ : " . $vol['villeDepart'] . "</li>";
        echo "<li> Arrivée : " . $vol['villeArrive'] . "</li>";
        echo "<li> Heure : " . $vol['heureDepart'] . "</li>";
        echo "<li> Date : " . $vol['dateVol'] . "</li>";
        echo "<li><img class='codeBarre2' src='images/codeBarre.svg' alt='' height='100'></li>";
        echo "</ul>";
        echo "</div>";
        echo "<img class='codeBarre' src='images/codeBarre.svg' alt='' height='100'>";
        echo "</div>";
      }
    } else {
      echo "<h3 class='result_count'>Aucun vol trouvé </h3>";
    }
    echo "</content>";
  }
  ?>
</div>
</div>


      </div>

    </main>
    <script src="script.js"></script>
    <script>
    function submitForm() {
        document.getElementById("searchForm").submit();
    }
    </script>
  </body>
</html>
