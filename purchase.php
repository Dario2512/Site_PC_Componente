<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_register";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

$username = $_SESSION['username'];

$sql_user = "SELECT id FROM Users WHERE username = '$username'";
$result_user = $conn->query($sql_user);

if ($result_user->num_rows > 0) {
    $row_user = $result_user->fetch_assoc();
    $user_id = $row_user['id'];
    $sql_insert_order = "INSERT INTO Orders (UserID) VALUES ($user_id)";
    $conn->query($sql_insert_order);
    $order_id = $conn->insert_id;
    $sql_cart = "SELECT product_id, quantity FROM Cart WHERE user_id = $user_id";
    $result_cart = $conn->query($sql_cart);

    if ($result_cart->num_rows > 0) {
        while ($row_cart = $result_cart->fetch_assoc()) {
            $product_id = $row_cart['product_id'];
            $quantity = $row_cart['quantity'];
            $sql_insert_details = "INSERT INTO OrderDetails (OrderID, ProductID, Quantity) VALUES ($order_id, $product_id, $quantity)";
            $conn->query($sql_insert_details);
        }
        $sql_delete_cart = "DELETE FROM Cart WHERE user_id = $user_id";
        $conn->query($sql_delete_cart);

        echo "Comenzile au fost adăugate cu succes în tabela Orders!";
    } else {
        echo "Coșul este gol. Nu există produse de adăugat în comandă.";
    }
} else {
    echo "Utilizatorul nu a fost găsit.";
}

$conn->close();
?>
