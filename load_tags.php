<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_register";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

$sql = "SELECT name FROM tags";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $tags = array();
    while ($row = $result->fetch_assoc()) {
        $tags[] = $row['name'];
    }
    echo json_encode($tags);
} else {
    echo json_encode(array());
}

$conn->close();
?>
