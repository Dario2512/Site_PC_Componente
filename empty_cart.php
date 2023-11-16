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

$user_id = $_SESSION['user_id'];

$delete_cart_sql = "DELETE FROM cart WHERE user_id = $user_id";
$conn->query($delete_cart_sql);

echo '<p>Coșul a fost golit.</p>';

$conn->close();
?>
