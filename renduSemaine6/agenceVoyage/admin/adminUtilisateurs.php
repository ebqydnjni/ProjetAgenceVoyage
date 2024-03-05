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
    $stmtAllUsers = $bdd->prepare("SELECT * FROM utilisateurs");
    $stmtAllUsers->execute();
    $users = $stmtAllUsers->fetchAll(PDO::FETCH_ASSOC);
    if (isset($_GET['logout'])) {
      session_destroy();
      header("Location: ../accueil.php");
      exit();
  }
} else {
    header("Location: ../accueil.php"); 
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['newNom'],$_POST['newPrenom'],$_POST['newMail'],$_POST['newMdp'],$_POST['newType'])) {
        $nom = $_POST['newNom'];
        $prenom = $_POST['newPrenom'];
        $mail = $_POST['newMail'];
        $mdp = $_POST['newMdp'];
        $type = $_POST['newType'];

        $hashedPassword = password_hash($mdp, PASSWORD_DEFAULT);

        $stmt = $bdd->prepare("INSERT INTO utilisateurs (nom, prenom, mail, mdp,type) VALUES (:nom, :prenom, :mail, :mdp,:type)");
        $stmt->execute(array(':nom' => $nom, ':prenom' => $prenom, ':mail' => $mail, ':mdp' => $hashedPassword,':type' => $type));
        if ($type == 'client'){
        $lastInsertId = $bdd->lastInsertId();
    $sql = "INSERT INTO clients (noClient, nom, prenom, email, mdp) VALUES (:noClient,:nom, :prenom, :mail, :mdp)";
    $stmt = $bdd->prepare($sql);
    $stmt->bindParam(':noClient', $lastInsertId);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':mail', $mail);
    $stmt->bindParam(':mdp', $hashedPassword);
    $stmt->execute();}
        
       
    
}


    header("Location: adminUtilisateurs.php");
    exit();

    
}

?>



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
    <div class="tabSearch">  <input style="margin-top:10px;"type="text" id="searchInput" placeholder="Rechercher..." onkeyup="searchUsers()"> <?php echo  "<h3 class='tabSearchCount'>" .count($users)." Utilisateurs</h3>" ?>
    <button id="openPopup" class="modifier" title="Ajouter" style="margin-top: 20px; width:10%"><span class="material-symbols-outlined">ADD</span>
</button>
</div>
  <table id='userTable'>
            <thead>
                <tr>
                    <th>Prenom</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Type</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?php echo $user['prenom']; ?></td>
                        <td><?php echo $user['nom']; ?></td>
                        <td><?php echo $user['mail']; ?></td>
                        <td><?php echo $user['type']; ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        </div>
  <div id="popup" class="popup">
  <div class="popup-content">
    <div class="sign_up_form">
      <span class="close" id="closePopup">&times;</span>
      <h2>Ajouter</h2>
      <form action="adminUtilisateurs.php" method="post" id="signUp">
        <label for="newPrenom">Prenom</label>
        <input name="newPrenom" type="text"  />
        <label for="newNom">Nom</label>
        <input type="text" name="newNom"  />
        <label for="newMail">Mail</label>
        <input type="text" name="newMail"  />
        <label for="newMdp">Mot de passe</label>
        <input type="password" name="newMdp"  />
        <label for="type">Type</label>
              <select name="newType" id="">
                <option value="client">Client</option>
                <option value="compagnie">Compagnie</option>
                <option value="admin">Administrateur</option>
              </select>
        <button class="signup_button" name="signUp" type="submit">Confirmer</button>
      </form>
    </div>
  </div>
</div>
  </div>

    <script src="../script.js"></script>
    <script>
        function logout() {
            window.location.href = "accueil.html";
        }
        function searchUsers() {
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("userTable");
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