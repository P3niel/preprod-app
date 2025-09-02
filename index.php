<?php
require 'vendor/autoload.php';

use Aws\S3\S3Client;
use Aws\Ssm\SsmClient;

// --- S3 ---
$s3 = new S3Client([
    'version' => 'latest',
    'region'  => 'eu-west-3'
]);

$result = $s3->listObjectsV2([
    'Bucket' => 'kami-preprod-bucket-terraform'
]);

if (!empty($result['Contents'])) {
    echo "<h2>Objets dans le bucket :</h2>";
    foreach ($result['Contents'] as $obj) {
        echo $obj['Key'] . "<br>";
    }
} else {
    echo "<p>Aucun objet dans le bucket.</p>";
}

// --- SSM pour récupérer le mot de passe MySQL ---
$ssm = new SsmClient([
    'version' => 'latest',
    'region'  => 'eu-west-3'
]);

$param = $ssm->getParameter([
    'Name' => 'kami/preprod/db_password',
    'WithDecryption' => true
]);

$dbPassword = $param['Parameter']['Value'];

// --- MySQL ---
$host = "tek2-preprod-db.c9imqaw42h50.eu-west-3.rds.amazonaws.com";
$user = "kami";
$db   = "testdb";

$conn = new mysqli($host, $user, $dbPassword, $db);
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM users");
if (!$result) {
    die("Erreur SQL : " . $conn->error);
}

echo "<h1>Liste des utilisateurs :</h1>";
while($row = $result->fetch_assoc()) {
    echo "ID: " . $row["id"]. " - Nom: " . $row["name"]. "<br>";
}

$conn->close();
?>
