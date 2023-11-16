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
if (isset($_GET['tag'])) {
    $tag = $_GET['tag'];
    $sql = "SELECT products.id, products.name, products.price, products.picture, GROUP_CONCAT(tags.name) AS product_tags
            FROM products
            LEFT JOIN product_tags ON products.id = product_tags.product_id
            LEFT JOIN tags ON product_tags.tag_id = tags.id
            WHERE tags.name = '$tag'
            GROUP BY products.id";
} else {
    $sql = "SELECT products.id, products.name, products.price, products.picture, GROUP_CONCAT(tags.name) AS product_tags
            FROM products
            LEFT JOIN product_tags ON products.id = product_tags.product_id
            LEFT JOIN tags ON product_tags.tag_id = tags.id
            GROUP BY products.id";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<div class="product-list">';
    while ($row = $result->fetch_assoc()) {
        echo '<div class="product-card">';
        echo '<img src="data:image/jpeg;base64,' . base64_encode($row['picture']) . '" alt="' . $row['name'] . '" width="200" height="200">';
        echo '<h2>' . $row['name'] . '</h2>';
        echo '<p style="font-size: 18px; color: #333; font-weight: bold;"> Price: ' . number_format($row['price'], 2) . ' Lei</p>';

        if (isset($_SESSION['username'])) {
            echo '<form method="post" action="cart.php">';
            echo '<input type="hidden" name="product_id" value="' . $row['id'] . '">';
            echo '<button type="submit" name="add_to_cart">Adaugă în coș</button>';
            echo '</form>';
        } else {
            echo '<p class="error">Autentifică-te pentru a adăuga în coș</p>';
        }

        echo '</div>';
    }
    echo '</div>';
} else {
    echo "Nu s-au găsit produse.";
}

$conn->close();
?>
