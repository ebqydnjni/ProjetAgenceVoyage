<?php
session_start();
require_once('../../config.php');
if (!isset($_SESSION['email'])) {
    header("Location: ../accueil.php");
    exit();
}

$userEmail = $_SESSION['email'];
$userPasword  = $_SESSION['password'];
$stmt = $bdd->prepare("SELECT type, prenom,id FROM utilisateurs WHERE mail = :email");
$stmt->execute(array(':email' => $userEmail));
$user = $stmt->fetch(PDO::FETCH_ASSOC);


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
  <div class="dashboard">
    <div class="sign_up_form">
    <form  action="clientProfile.php" method="post" id="modifyProfile">
        
              <label for="name" >Nom</label>
              <input type="text" name="newNom" placeholder="Nom" />
              <label for="text" >Prénom</label>
              <input type="text" name="newPrenom" placeholder="Prénom" />
              <label for="mail" >Mail</label>
              <input type="mail" name="newMail" placeholder="adresse mail" />
              <label for="password" >Mot de passe</label>
              <input type="password" name = "newMdp" placeholder="mot de passe" />
             

              </select>
              <button class="signup_button" name="signUp" onclick="modifyProfile()">Modifier</button>
              


              
            </form>
            <?php

if(isset($_POST['newMail'], $_POST['newPrenom'], $_POST['newNom'], $_POST['newMdp'])) {
    $newMail = $_POST['newMail'];
    $newPrenom = $_POST['newPrenom'];
    $newNom = $_POST['newNom'];
    $newPassword = password_hash($_POST['newMdp'], PASSWORD_DEFAULT);
    

    $stmt = $bdd->prepare("UPDATE utilisateurs SET prenom = :newPrenom, nom = :newNom, mdp = :newPassword, mail=:newMail  WHERE id = :userId");
    $stmt->execute(array(':newPrenom' => $newPrenom, ':newNom' => $newNom, ':newPassword' => $newPassword,':newMail' => $newMail, ':userId' => $user['id']));

    $stmt = $bdd->prepare("UPDATE clients SET prenom = :newPrenom, nom = :newNom, mdp = :newPassword, email=:newMail  WHERE noClient = :userId");
    $stmt->execute(array(':newPrenom' => $newPrenom, ':newNom' => $newNom, ':newPassword' => $newPassword,':newMail' => $newMail, ':userId' => $user['id']));
    header("Location: ../accueil.php"); 
    exit();
} 
?>
    </div>
  

  </div>

    <script src="script.js"></script>
    <script>
        function logout() {
            window.location.href = "accueil.html";
        }
        function modifyProfile(){
      document.getElementById("modifyProfile").submit();
    }
    </scri
    </script>


<script>
    
</script>
</body>
</html>