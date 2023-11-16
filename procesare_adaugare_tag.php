<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['tag_nou'])) {
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "login_register";
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Conexiune eșuată: " . $conn->connect_error);
        }
        $tag_nou = $_POST['tag_nou'];
        $stmt = $conn->prepare("INSERT INTO tags (name) VALUES (?)");
        $stmt->bind_param("s", $tag_nou);
        if ($stmt->execute()) {
            echo "Tagul a fost adăugat cu succes!";
        } else {
            echo "Eroare la adăugarea tag-ului: " . $conn->error;
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Vă rugăm să introduceți un tag pentru a-l adăuga!";
    }
}
?>
