
<?php
session_start();

// Connexion à la base de données
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'agencevoyage';

$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    die("Échec de la connexion: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

// Sécurisation contre les injections SQL
$email = $conn->real_escape_string($email);
$password = $conn->real_escape_string($password);

$query = "SELECT * FROM users WHERE email='$email'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Vérifier le mot de passe (supposez que les mots de passe sont stockés en utilisant password_hash)
    if (password_verify($password, $row['password'])) {
        // Création de la session
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_email'] = $email;
        // Redirection vers une page sécurisée
        header("Location: secure_page.php");
    } else {
        echo "Mot de passe incorrect";
    }
} else {
    echo "Aucun utilisateur trouvé avec cet email";
}

$conn->close();
?>
