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

        $delete_query = "DELETE FROM product_tags WHERE product_id = $product_id AND tag_id = $tag_id";

        if ($conn->query($delete_query) === TRUE) {
            header('Location:filtre.php');
        } else {
            echo "Eroare la ștergerea tag-ului pentru acest produs: " . $conn->error;
        }

        $conn->close();
    } else {
        echo "Datele necesare nu au fost primite.";
    }
} else {
    echo "Acces nepermis.";
}
?>
