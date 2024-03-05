<?php
session_start();
require_once('../../config.php');
if (!isset($_SESSION['email'])) {
    header("Location: ..//accueil.php");
    exit();
}

$userEmail = $_SESSION['email'];
$stmt = $bdd->prepare("SELECT type, prenom FROM utilisateurs WHERE mail = :email");
$stmt->execute(array(':email' => $userEmail));
$user = $stmt->fetch(PDO::FETCH_ASSOC);


if ($user && $user['type'] === 'admin') {
    $stmtAllBillets = $bdd->prepare("select v.*,ad.nomAeroport as departure ,ar.nomAeroport as arrivage from vols v, aeroports ad, aeroports ar where v.Adepart=ad.IATA_CODE and v.Aarrive=ar.IATA_CODE and softDelete =2;");
    $stmtAllBillets->execute();
    $billets = $stmtAllBillets->fetchAll(PDO::FETCH_ASSOC);
    if (isset($_GET['logout'])) {
        session_destroy();
        header("Location: ../accueil.php");
        exit();
    }
} else {
    header("Location: ../accueil.php"); 
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
            return 'Invalid Day';
    }
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
<a href="adminCompagnies.php" class="active" title='Compagnies'><span class="material-symbols-outlined">
airlines</span></a>

    <a href="adminProfil.php" class="active" title='Profil'><span class="material-symbols-outlined">
person
</span></a>


  </div>
  <div class="dashboard">
    <div class="tabSearch">  <input style="margin-top:10px;"type="text" id="searchInput" placeholder="Rechercher..." onkeyup="searchUsers()"> <?php echo  "<h3 class='tabSearchCount'>" .count($billets)." Vols</h3>" ?>
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
                </tr>
            </thead>
            <tbody>
                <?php foreach ($billets as $billet): ?>
                    <tr>
                        <td><?php echo $billet['idVol']; ?></td>
                        <td><?php echo $billet['numVol']; ?></td>
                        <td><?php echo $billet['dateVol']; ?></td>
                        <td><?php echo getDayName($billet['jourSemaine']); ?></td>
                        <td><?php echo $billet['heureDepart']; ?></td>
                        <td><?php echo $billet['heureArrive']; ?></td>
                        <td><?php echo $billet['departure']; ?></td>
                        <td><?php echo $billet['arrivage']; ?></td>

<td><form action="adminArchives.php" method="post" onsubmit="return confirmDelete();"><input type="hidden" name="delVol" value="<?php echo $billet['idVol']; ?>"><button class="archive" title="Su"><span class="material-symbols-outlined">
delete</span></button></form>
<form action="adminArchives.php" method="post" ><input type="hidden" name="idVol" value="<?php echo $billet['idVol']; ?>">
</button><button type="submit" class="modifier"  title="Restaurer"><span class="material-symbols-outlined">
recycling
</span></button></form>
</td>

                    </tr>
                    <?php
if (isset($_POST['idVol'])) {
    

    $idVol = $_POST['idVol'];

    $stmt = $bdd->prepare("UPDATE VOLS SET softDelete = 1  WHERE idVol = :id");
    $stmt->execute([':id' => $idVol]);

    header("Location: adminArchives.php"); 
    exit();
}
if (isset($_POST['delVol'])) {
    

    $idDelVol = $_POST['delVol'];

    $stmt = $bdd->prepare("UPDATE VOLS SET softDelete = 3  WHERE idVol = :id");
    $stmt->execute([':id' => $idDelVol]);

    header("Location: adminArchives.php"); 
    exit();
}
    

 endforeach; ?>
            </tbody>
        </table>
  </div>
  <?php
  $stmt = $bdd->prepare("DELETE FROM VOLS WHERE softDelete = 3");
  $stmt->execute();
  ?>


    <script src="script.js"></script>
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