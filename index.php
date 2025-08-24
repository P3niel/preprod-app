<?php
$host = "tek2-preprod-db.c9imqaw42h50.eu-west-3.rds.amazonaws.com";
$user = "admin";
$pass = "Devops1403!";
$db   = "testdb";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM users");
echo "<h1>Liste des utilisateurs :</h1>";
while($row = $result->fetch_assoc()) {
    echo "ID: " . $row["id"]. " - Nom: " . $row["name"]. "<br>";
}
$conn->close();
?>
