<?php
session_start();
require_once('../../config.php');
if (!isset($_SESSION['email'])) {
    header("Location: ../accueil.php");
    exit();
}

$userEmail = $_SESSION['email'];
$stmt = $bdd->prepare("SELECT type, prenom FROM utilisateurs WHERE mail = :email");
$stmt->execute(array(':email' => $userEmail));
$user = $stmt->fetch(PDO::FETCH_ASSOC);


if ($user && $user['type'] === 'admin') {
    $stmtAllCompagnies = $bdd->prepare("SELECT * FROM compagnies ");
    $stmtAllCompagnies->execute();
    $compagnies = $stmtAllCompagnies->fetchAll(PDO::FETCH_ASSOC);
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
    if (isset($_POST['nomCompagnie']) && isset($_POST['noCompagnie'])) {
        $nomCompagnie = $_POST['nomCompagnie'];
        $noCompagnie = $_POST['noCompagnie'];

        $stmt = $bdd->prepare("UPDATE compagnies SET nomCompagnies = :nomCompagnie WHERE noCompagnies = :noCompagnie");
        $stmt->execute(array(':nomCompagnie' => $nomCompagnie, ':noCompagnie' => $noCompagnie));
    }

    if (isset($_POST['delCompagnie'])) {
        $noCompagnie = $_POST['delCompagnie'];

        $stmt = $bdd->prepare("DELETE FROM compagnies WHERE noCompagnies = :noCompagnie");
        $stmt->execute(array(':noCompagnie' => $noCompagnie));
    }

    if (isset($_POST['newNoCompagnie'], $_POST['newNomCompagnie'])) {
        $newNoCompagnie = $_POST['newNoCompagnie'];
        $newNomCompagnie = $_POST['newNomCompagnie'];

        $stmt = $bdd->prepare("INSERT INTO compagnies (noCompagnies, nomCompagnies) VALUES (:noCompagnie, :nomCompagnie)");
        $stmt->execute(array(':noCompagnie' => $newNoCompagnie, ':nomCompagnie' => $newNomCompagnie));
    }

    header("Location: adminCompagnies.php");
    exit();

    
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
    <a href="adminProfil.php" class="active" title='Profil'><span class="material-symbols-outlined">
person
</span></a>
<a href="adminCompagnies.php" class="active" title='Compagnies'><span class="material-symbols-outlined">
airlines</span></a>

<a href="adminArchives.php" class="active" title='Archives'><span class="material-symbols-outlined">
history
</span></a>
</span></a>


  </div>
  <div class="dashboard">
    <div class="tabSearch">  <input style="margin-top:10px;"type="text" id="searchInput" placeholder="Rechercher..." onkeyup="searchUsers()"> <?php echo  "<h3 class='tabSearchCount'>" .count($compagnies)." Compagnies</h3>" ?>
    <button id="openPopup" class="modifier" title="Ajouter" style="margin-top: 20px; width:10%"><span class="material-symbols-outlined">ADD</span>
</button>

</div>
  <table id='billetTable'>
            <thead>
                <tr>
                    <th>Compagnie</th>
                    <th>Numéro</th>
                    <th>Actions</th>
                   
                </tr>
            </thead>
            <tbody>
                <?php foreach ($compagnies as $index => $compagnie): ?>
                    <?php $popupId = 'popup' . $index;?>
                    <tr>
                        <td><?php echo $compagnie['nomCompagnies']; ?></td>
                        <td><?php echo $compagnie['noCompagnies']; ?></td>
                        
<td style="display:flex;justify-content: space-evenly;"><button id="openPopup<?php echo $index; ?>" class="modifier" title="Modifier"><span class="material-symbols-outlined">
edit
</span></button>
                        
<form action="adminCompagnies.php" method="post" onsubmit="return confirmDelete();"><input type="hidden" name="delCompagnie" value="<?php echo $compagnie['noCompagnies']; ?>">
<button type="submit" class="archive"  title="Archiver"><span class="material-symbols-outlined">
delete
</span></button></form>

</td>

                    </tr>
                    <?php //POPUP MODIFIER
 echo '<div id="' . $popupId . '" class="popup">';
 echo '<div class="popup-content">';
echo '<div class="sign_up_form">';
echo '<span class="close" id="closePopup' . $index . '"">&times;</span>';
echo '<h2>Modifier</h2>';
echo '<form  action="adminCompagnies.php" method="post" id="signUp">';
echo '<input  name="noCompagnie" maxlength="3" style="text-transform:uppercase" type="hidden" value="' . $compagnie['noCompagnies'] . '" placeholder="' . $compagnie['noCompagnies'] . '" />';
echo '<label for="nomCompagnie" >Nom</label>';
echo '<input type="text" name="nomCompagnie" value="' . $compagnie['nomCompagnies'] . '" placeholder="' . $compagnie['nomCompagnies'] . '" />';
echo '<button class="signup_button" name="signUp" type="submit">Confirmer</button>';
echo '</form>';
echo '</div>';
echo '</div>';
echo '</div>';


// POPUP MODIFIER (scipt)
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
      <form action="adminCompagnies.php" method="post" id="signUp">
        <label for="NewNoCompagnie">Numéro</label>
        <input name="newNoCompagnie" type="text" maxlength="3" style="text-transform:uppercase;" />
        <label for="newNomCompagnie">Nom</label>
        <input type="text" name="newNomCompagnie"  />
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
        return confirm("Etes vous sûre de vouloir définitivement supprimer cette compagnie?");
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