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

if ($user && $user['type'] === 'compagnie') {
  if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ../accueil.php");
    exit();
}
  
} else {
    header("Location: /accueil.php"); 
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
    <div class="sign_up_form">
    <form  action="compagnieProfil.php" method="post" id="modifyProfile">
        
              
              <label for="text" >Nom Compagnie</label>
              <input type="text" name="newPrenom" value="<?php echo $user['prenom']?>" placeholder="<?php echo $user['prenom']?>" />
              <label for="mail" >Mail</label>
              <input type="mail" name="newMail" value="<?php echo $userEmail?>"  placeholder="<?php echo $userEmail?>" />
              <label for="password" >Mot de passe</label>
              <input type="password" name = "newMdp" placeholder="mot de passe" />
             

              </select>
              <button class="signup_button" name="signUp" onclick="modifyProfile()">Modifier</button>
              


              
            </form>
            <?php

if(isset($_POST['newMail'], $_POST['newPrenom'], $_POST['newNom'], $_POST['newMdp'])) {
    $newPrenom = $_POST['newPrenom'];
    $newMail = $_POST['newMail'];

    $newPassword = password_hash($_POST['newMdp'], PASSWORD_DEFAULT);
    

    $stmt = $bdd->prepare("UPDATE utilisateurs SET prenom = :newPrenom,  mdp = :newPassword, mail=:newMail  WHERE id = :userId");
    $stmt->execute(array(':newPrenom' => $newPrenom, ':newPassword' => $newPassword,':newMail' => $newMail, ':userId' => $user['id']));
     
    $stmt = $bdd -> prepare("UPDATE compagnie SET nomCompagnies = :newPrenom, where noCompagnies = :newNom");
    $stmt->execute(array(':newPrenom' => $newPrenom, ':newNom' => $newNom));
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