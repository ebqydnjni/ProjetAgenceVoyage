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
          <li><a href="admin.php">Dashboard</a></li>
         
          <li style="color:orange; font-size:20px;  margin-left: 100px;
"><?php echo $user['prenom']; ?></li>
            <li class="logout"><a class="material-symbols-outlined" href="?logout">logout</a></li>


        </ul>
      </nav>


      
    </header>

    <!-- SIDEBAR -->
    <div class="sidebar">

    <a href="compagnieBillets.php" class="active" title='Billets'><span class="material-symbols-outlined">
airplane_ticket
</span></a>
<a href="compagnieReservations.php" class="active" title='Reservations'><span class="material-symbols-outlined">
auto_stories
</span></a>

    <a href="compagnieProfil.php" class="active" title='Profil'><span class="material-symbols-outlined">
person
</span></a>


  </div>

  <!-- Dashboard -->
<div class="dashboard">
  <!-- Carte 1 -->
  <div class="dashboard-format-container">
    <div class="dashboard_box">
      <div class="dashboard_item">
        <a href="compagnieVols.php" class="dashboard-item_link">
          <div class="dashboard-item_bg"></div>
          <div class="dashboard-item_title">
            <span class="material-symbols-outlined">airplane_ticket</span><br>
            Vols
          </div>
        </a>
      </div>
    </div>
  </div>

  <!--  Carte 2  -->
  <div class="dashboard-format-container">
    <div class="dashboard_box">
      <div class="dashboard_item">
        <a href="compagnieReservations.php" class="dashboard-item_link">
          <div class="dashboard-item_bg"></div>
          <div class="dashboard-item_title">
            <span class="material-symbols-outlined">auto_stories</span><br>
            Réservations
          </div>
        </a>
      </div>
    </div>
  </div>

  <!--  Carte 3 -->
  <div class="dashboard-format-container">
    <div class="dashboard_box">
      <div class="dashboard_item">
        <a href="compagnieProfil.php" class="dashboard-item_link">
          <div class="dashboard-item_bg"></div>
          <div class="dashboard-item_title">
            <span class="material-symbols-outlined">person</span><br>
            Profil
          </div>
        </a>
      </div>
    </div>
  </div>

  

    
    <script src="script.js"></script>
    <script>
    function logout() {
            window.location.href = "accueil.html";
        }
    </script>
  </body>
</html>
