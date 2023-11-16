<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['product_id'], $_POST['tag_id'])) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "login_register";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Conexiune eșuată: " . $conn->connect_error);
        }
        $product_id = $_POST['product_id'];
        $tag_id = $_POST['tag_id'];
        $check_product_query = "SELECT * FROM products WHERE id = $product_id";
        $check_tag_query = "SELECT * FROM tags WHERE id = $tag_id";
        $product_result = $conn->query($check_product_query);
        $tag_result = $conn->query($check_tag_query);

        if ($product_result->num_rows > 0 && $tag_result->num_rows > 0) {
            $insert_query = "INSERT INTO product_tags (product_id, tag_id) VALUES ($product_id, $tag_id)";

            if ($conn->query($insert_query) === TRUE) {
                header('Location:filtre.php');
            } else {
                echo "Eroare la conectarea produsului la tag: " . $conn->error;
            }
        } else {
            echo "Produsul sau tag-ul nu există în baza de date.";
        }

        $conn->close();
    } else {
        echo "Datele necesare nu au fost primite.";
    }
} else {
    echo "Acces nepermis.";
}
?>
