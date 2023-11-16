<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_register";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

if (isset($_POST['add_tag'])) {
    $tag = $_POST['tag'];

    $sql = "INSERT INTO tags (name) VALUES (?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tag);

    if ($stmt->execute()) {
        echo "Tag adăugat cu succes.";
    } else {
        echo "Eroare: " . $sql . "<br>" . $conn->error;
    }
    $stmt->close();
}

$conn->close();
?>
