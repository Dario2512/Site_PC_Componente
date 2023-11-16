<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "Utilizatorul nu este autentificat.";
    exit();
}

$user_id_session = $_SESSION['user_id'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_register";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}

if (isset($_POST['add_to_cart'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];
    $quantity = 1; 
    $check_sql = "SELECT * FROM cart WHERE user_id = ? AND product_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $product_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $update_sql = "UPDATE cart SET quantity = quantity + ? WHERE user_id = ? AND product_id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("iii", $quantity, $user_id, $product_id);
        $update_stmt->execute();
        $update_stmt->close();
    } else {
        $order_sql = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)";
        $order_stmt = $conn->prepare($order_sql);
        $order_stmt->bind_param("iii", $user_id, $product_id, $quantity);
        $order_stmt->execute();
        $order_stmt->close();
    }

    header("Location: products.html");
}

$conn->close();
?>
