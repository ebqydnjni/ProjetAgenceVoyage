<?php
session_start();
require_once('../config.php');
$sql = 'SELECT * FROM compagnies';
$stmt = $bdd->prepare($sql);
$stmt->execute();
$compagnies = $stmt->fetchAll();
/*foreach ($compagnies as $compagnie){
  $prenom = $compagnie['nomCompagnies'];
  $nom = $compagnie['noCompagnies'];
  $mdp = 'compagnie123';
  $mdpHash = password_hash($mdp, PASSWORD_DEFAULT);
  $mail = $compagnie['noCompagnies'].'@negnGo.sn';
  $typeCompagnie = 'compagnie';
  $sql = "INSERT INTO  utilisateurs(prenom,nom,mail,mdp,type) values(:prenom,:nom,:mail,:mdp,:typeCompagnie)";
  $stmt = $bdd->prepare($sql);
  $stmt->execute(array('prenom' => $prenom, 'nom' => $nom, 'mail' => $mail, 'mdp' => $mdpHash, ':typeCompagnie' => $typeCompagnie));

}*/


// Sign Up
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signUp'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $mail = $_POST['mail'];
    $mot_de_passe = $_POST['mdp'];
    $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_DEFAULT);
    $typeUtilisateur = $_POST['type'];
  if ($typeUtilisateur == 'client') {
    $sql = "INSERT INTO utilisateurs (prenom, nom, mail, mdp, type) VALUES (:nom, :prenom, :mail, :mot_de_passe, :typeUtilisateur)";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':mail', $mail);
    $stmt->bindParam(':mot_de_passe', $mot_de_passe_hache);
    $stmt->bindParam(':typeUtilisateur', $typeUtilisateur);
    $stmt->execute();
    $lastInsertId = $bdd->lastInsertId();
    $sql = "INSERT INTO clients (noClient, nom, prenom, email, mdp) VALUES (:noClient,:nom, :prenom, :mail, :mot_de_passe)";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':noClient', $lastInsertId);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':mail', $mail);
    $stmt->bindParam(':mot_de_passe', $mot_de_passe_hache);
    $stmt->execute();
    echo "<script>alert('Inscription avec succès');</script>";
    echo "<script> openSignIn()</script>";}
  if ($typeUtilisateur == 'compagnie') {
      $sql = "INSERT INTO utilisateurs (prenom, nom, mail, mdp, type) VALUES (:nom, :prenom, :mail, :mot_de_passe, :typeUtilisateur)";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':mail', $mail);
    $stmt->bindParam(':mot_de_passe', $mot_de_passe_hache);
    $stmt->bindParam(':typeUtilisateur', $typeUtilisateur);
    $stmt->execute();
    $lastInsertId = $bdd->lastInsertId();
    $sql = "INSERT INTO compagnies (noCompagnies, nomCompagnies) VALUES (:noCompagnie,:nomCompagnie)";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':noCompagnie', $nom);
    $stmt->bindParam(':nomCompagnie', $prenom);

    $stmt->execute();
    echo "<script>alert('Inscription avec succès');</script>";
    echo "<script> openSignIn()</script>";}
    }


// sign in
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['signIn'])) {
    $email = $_POST['mail'];
    $password = $_POST["password"];


    $stmt = $bdd->prepare("SELECT * FROM utilisateurs WHERE mail = :email");
    $stmt->execute(array(':email' => $email,));
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
   


   

if ($user&& password_verify($password,$user['mdp'])) {
    $_SESSION['email'] = $email;
    $_SESSION['password'] = $password;
    if ($user['type'] == 'admin') {
        header("Location: admin/admin.php");

    } elseif ($user['type'] == 'client') {
        header("Location: clients/client.php");
        exit();
    }elseif ($user['type'] == 'compagnie') {
      header("Location: compagnie/compagnie.php");
      exit();
} else {
    $error = "Invalid email or password.";
    echo $error;
}

}}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Accueil</title>
    <link rel="stylesheet" type="text/css" href="style.css" />
    <link rel="stylesheet" type="text/css" href="card.css" />
    <link rel="icon" href="images/Icon.png" />
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  </head>
  <body>
    <header class="header" id="navbar">
      <img
        class="logo"
        width="100px"
        height="66"
        src="images/logo.png"
        alt="logo"
      />
      
      <nav>
        <ul class="navigation">
          <li><a href="#Recherche">Rechercher</a></li>
          <li><a href="#Destination">Destinations</a></li>

          <li><a href="#Apropos">A Propos</a></li>
          <li><a href="#Contact">Contact</a></li>
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
<?php $sql2="SELECT * FROM aeroports";
    $stmt2 = $bdd->prepare($sql2);

    $stmt2->execute();
    $cities = $stmt2->fetchAll(PDO::FETCH_ASSOC);
   
   
   
  

    ?>

          <div class="search">
            <form class="searchForm" id="searchForm" method="post" action="billet.php">
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
              <button type="button"  class="searchButton" onclick="submitForm()">
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

      <div id="destination" class="mainTitle">
        <div class="destination">      <h2 " style="background-color:rgba(75, 71, 71, 0.604); margin-bottom:50px; border-radius:12px; ">DESTINATIO<span class="ax">NS!</span></h2>
</div>

      </div>
<div class="maincontainer">
<div class = "mycontainer">
    <div class = "mycard">
      <div class = "myimage">
        <img href = "#" src ="images/paris.jpg">
      </div>
      <div class = "mycontent">
        <h4>Paris</h4><br>
<p>Paris, la ville des lumières, enchante avec ses boulevards élégants, ses monuments emblématiques et ses cafés animés. Chaque coin de la capitale française raconte une histoire riche en art, en histoire et en romance.</p>      
</div>
    </div>    
  </div>
  <div class = "mycontainer">
    <div class = "mycard">
      <div class = "myimage">
        <img href = "#" src ="images/southAfrica.jpg">
      </div>
      <div class = "mycontent">
        <h4>Afrique du Sud</h4><br>
<p>Les paysages d'Afrique du Sud captivent l'âme avec leurs savanes infinies, leurs sommets imposants et leurs littoraux spectaculaires. Chaque coin offre une expérience visuelle époustouflante, unissant la beauté brute de la nature à la richesse culturelle du pays.</p>      
</div>
    </div>    
  </div>
  <div class = "mycontainer">
    <div class = "mycard">
      <div class = "myimage">
        <img href = "#" src ="images/singapour.jpg">
      </div>
      <div class = "mycontent">
        <h4>Singapour</h4><br>
<p>Singapour, la cité-état insulaire, fascine par son mélange saisissant de modernité futuriste et de traditions asiatiques. Des gratte-ciel étincelants aux jardins botaniques luxuriants, en passant par les délices culinaires des marchés de rue.</p>      
</div>
    </div>    
  </div>
</div>
<div class="maincontainer">
<div class = "mycontainer">
    <div class = "mycard">
      <div class = "myimage">
        <img href = "#" src ="images/newYork.jpg">
      </div>
      <div class = "mycontent">
        <h4>New York</h4><br>
<p>New York, la ville qui ne dort jamais, ensorcelle par son skyline emblématique, ses quartiers éclectiques et son rythme effréné. Des gratte-ciel étincelants de Manhattan aux rives paisibles de Central Park, en passant par les lumières éblouissantes de Times Square et la diversité culturelle de Brooklyn.</p>      
</div>
    </div>    
  </div>
  <div class = "mycontainer">
    <div class = "mycard">
      <div class = "myimage">
        <img href = "#" src ="images/london.jpg">
      </div>
      <div class = "mycontent">
        <h4>Londres</h4><br>
<p>Londres, la métropole cosmopolite, charme par son histoire millénaire, ses emblématiques monuments et son énergie vibrante. Des rives de la Tamise aux ruelles pavées de Covent Garden, en passant par les musées de renommée mondiale.</p>      
</div>
    </div>    
  </div>
  <div class = "mycontainer">
    <div class = "mycard">
      <div class = "myimage">
        <img href = "#" src ="images/brazil.jpg">
      </div>
      <div class = "mycontent">
        <h4>Brésil</h4><br>
<p>Le Brésil, terre de contrastes, séduit par sa diversité époustouflante de paysages. Des plages de sable blanc de Rio de Janeiro à la luxuriante forêt amazonienne, en passant par les festifs carnavals de Salvador.</p>      
</div>
    </div>    
  </div>
</div>


      <div class="Apropos">
        <content id="Apropos" class="Apropos_content_1">
          <img
            class="Apropos_Image_1"
            src="images/apropos1.png"
            width="202px"
            height="279px"
            alt=""
          />
          <span class="Apropos_text">
            <h3 class="Apropos_title">
              LA COMPAGNIE <span class="percentage">100%</span> ÉTUDIANTE
            </h3>

            <h4>
              Nous sommes un groupe d'étudiants passionés par l'informatique et
              les avancées technologique. Dans cette optique, nous avons décidés
              de créer l'agence de voyage <i>Negn'Go</i>, "Allons-y". Un mélange
              de wolof et d'anglais pour mettre en exergue la liberté et pousser
              nos perspectives à leurs paroxisme. Participez à cette expérience
              formidable et réservez vos billets à des prix somptueux!
            </h4>
          </span>
        </content>
        
        <content id="Contact" class="Apropos_content_2">
          <div class="contact">
            <h3 class="contact_title">Comment nous joindre?</h3>
            <h4 class="contact_text">
              Vous avez des requêtes spéciales, des retours ou des suggestions ? Contactez-nous par mail ou par téléphone. Nous sommes à votre disposition 
            </h4>
            <button>Contactez nous</button>
          </div>
        </content>
        <div class="Apropos_image_2">
          <img src="images/Apropos2.png" width="400px" height="400px" alt="" />
        </div>


      </div>

    </main>
    <script src="script.js"></script>
    <script>
      
    function submitForm() {
        document.getElementById("searchForm").submit();
    };
    function signIn(){
    document.getElementById("signIn").submit();
};
    function signUp(){
      document.getElementById("signUp").submit();
    }
    </script>
  </body>
</html>



