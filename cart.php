<?php
session_start();

// Verificare utilizator 
if (!isset($_SESSION['user_id'])) {
    echo "Utilizatorul nu este autentificat.";
    exit();
}

$user_id_session = $_SESSION['user_id'];

// Conectare la baza de date
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_register";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

// adaugare prod in cos
if (isset($_POST['add_to_cart'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $quantity = 1; // aici este cantitatea, mai trebe lucrat ca sa pot sa adaug cantitatea

    // Adauga comanda în tabelul 'cart'
    $order_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
    $order_stmt = $conn->prepare($order_sql);
    $order_stmt->bind_param("iii", $user_id, $product_id, $quantity);

    if ($order_stmt->execute()) {
        header("Location: products.html");
    } else {
        echo "Eroare: " . $order_sql . "<br>" . $conn->error;
    }

    $order_stmt->close();
}

// Plasare comanda
if (isset($_POST['place_order'])) {
    $user_id = $_SESSION['user_id'];

    // Goleste cosul de cumparaturi dupa plasarea comenzii
    $clear_cart_sql = "DELETE FROM cart WHERE user_id = $user_id";
    $conn->query($clear_cart_sql);

    echo "Comanda plasată cu succes!";
}

$conn->close();
?>
