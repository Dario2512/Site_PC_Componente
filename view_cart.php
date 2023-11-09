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

// Afis produsele din cosul userului
$user_id = $_SESSION['user_id'];

$cart_sql = "SELECT products.name, cart.quantity FROM cart
             INNER JOIN products ON cart.product_id = products.id
             WHERE cart.user_id = $user_id";
$cart_result = $conn->query($cart_sql);

if ($cart_result->num_rows > 0) {
    echo '<h2>Produse în Coș:</h2>';
    echo '<ul>';
    while ($cart_row = $cart_result->fetch_assoc()) {
        echo '<li>' . $cart_row['name'] . ' - Cantitate: ' . $cart_row['quantity'] . '</li>';
    }
    echo '</ul>';
} else {
    echo '<p>Coșul este gol.</p>';
}

$conn->close();
?>
