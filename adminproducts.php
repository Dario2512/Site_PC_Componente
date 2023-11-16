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

if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $price = intval($_POST['price']);

    $picture = null;

    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === 0) {
        if (exif_imagetype($_FILES['picture']['tmp_name']) != false) {
            $picture = file_get_contents($_FILES['picture']['tmp_name']);
        } else {
            echo "Fișierul încărcat nu este o imagine validă.";
        }
    } else {
        echo "Eroare la încărcarea imaginii.";
    }

    $sql = "INSERT INTO products (name, price, picture) VALUES (?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sis", $name, $price, $picture);

    if ($stmt->execute()) {
        echo "Produs adăugat cu succes.";
    } else {
        echo "Eroare: " . $sql . "<br>" . $conn->error;
    }
    $stmt->close();
}

$sql = "SELECT * FROM products";
$result = $conn->query($sql);
$products = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <script>
        function openModal() {
            document.getElementById('add-product-section').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('add-product-section').style.display = 'none';
        }
    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - PC Components Store</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
       
    </style>
</head>
<body>
    <header>
            <div class="contact-info">
                <a href="login.html">Log out</a>
            </div>
            <h1>PC Components Store</h1>
            <nav>
                <ul>
                    <li><a href="adminorders.php">Orders</a></li> 
                    <li><a href="adminproducts.php">Products</a></li>
                    <li><a href="filtre.php">Filtre</a></li> 
                </ul>
                <button id="menu-button">&#9776;</button>
            </nav>
        </header>
    <main>
    <div id="add-product">
    <button onclick=" openModal()">Adăugare produs +</button>
    </div>

    <section id="add-product-section"  style="display: none;" >
    <div class="modal">
        <span class="close" onclick="closeModal()">&times;</span>
        <h2>Adăugare produs nou</h2>
        <form method="post" action="adminproducts.php" enctype="multipart/form-data">
            <label for="name">Nume produs:</label>
            <input type="text" id="name" name="name" required>

            <label for="price">Preț:</label>
            <input type="number" id="price" name="price" required>
            <label for="picture">Imagine:</label>
            <input type="file" id="picture" name="picture" accept="image/*" required>
            <button type="submit" name="add_product">Adaugă produs</button>
        </form>
    </div>
</section>
    <section id="edit-product-section" style="display: none;">
        <div class="modal">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Modificare produs</h2>
            <form method="post" action="adminproducts.php">
                <input type="hidden" id="edit-product-id" name="edit_product_id">
                <label for="edit-name">Nume produs:</label>
                <input type="text" id="edit-name" name="edit_name" required>

                <label for="edit-price">Preț:</label>
                <input type="number" id="edit-price" name="edit_price" required>

                <button type="submit" name="save_changes">Salvează modificările</button>
            </form>
        </div>
    </section>
        <?php
        if (isset($_POST['delete_product'])) {
            $product_id = intval($_POST['product_id']);
            $delete_sql = "DELETE FROM products WHERE id = ?";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bind_param("i", $product_id);

            if ($delete_stmt->execute()) {
                header("Location: adminproducts.php");
            } else {
                echo "Eroare la eliminarea produsului: " . $conn->error;
            }
            $delete_stmt->close();
        }
        if (isset($_POST['edit_product'])) {
            $product_id = intval($_POST['product_id']);
            
            // Selectează produsul pentru editare
            $select_sql = "SELECT * FROM products WHERE id = ?";
            $select_stmt = $conn->prepare($select_sql);
            $select_stmt->bind_param("i", $product_id);
            $select_stmt->execute();
            $selected_product = $select_stmt->get_result()->fetch_assoc();
            
            // Afiseaza modalul pentru editare
            echo '<script>';
            echo 'document.addEventListener("DOMContentLoaded", function() {';
            echo 'document.getElementById("edit-product-section").style.display = "block";';
            echo 'document.getElementById("edit-name").value = "' . $selected_product['name'] . '";';
            echo 'document.getElementById("edit-price").value = "' . $selected_product['price'] . '";';
            echo 'document.getElementById("edit-product-id").value = "' . $product_id . '";';
            echo '});';
            echo '</script>';
        }
        
        if (isset($_POST['save_changes'])) {
            $edit_product_id = intval($_POST['edit_product_id']);
            $edit_name = $_POST['edit_name'];
            $edit_price = intval($_POST['edit_price']);
        
            $update_sql = "UPDATE products SET name = ?, price = ? WHERE id = ?";
            $update_stmt = $conn->prepare($update_sql);
            $update_stmt->bind_param("sii", $edit_name, $edit_price, $edit_product_id);
        
            if ($update_stmt->execute()) {
                header("Location: adminproducts.php");
            } else {
                echo "Eroare la actualizarea produsului: " . $conn->error;
            }
            $update_stmt->close();
        }
        ?>
<div class="product-list">
    <?php
    if (isset($_POST['edit_product'])) {
        $product_id = intval($_POST['product_id']);
        $select_sql = "SELECT * FROM products WHERE id = ?";
        $select_stmt = $conn->prepare($select_sql);
        $select_stmt->bind_param("i", $product_id);
        $select_stmt->execute();
        $selected_product = $select_stmt->get_result()->fetch_assoc();
        
        echo '<script>';
        echo 'document.addEventListener("DOMContentLoaded", function() {';
        echo 'document.getElementById("edit-product-section").style.display = "block";';
        echo 'document.getElementById("edit-name").value = "' . $selected_product['name'] . '";';
        echo 'document.getElementById("edit-price").value = "' . $selected_product['price'] . '";';
        echo 'document.getElementById("edit-product-id").value = "' . $product_id . '";';
        echo '});';
        echo '</script>';
    }    
    foreach ($products as $product) {
        echo '<div class="product-card">';
        echo '<img src="data:image/jpeg;base64,' . base64_encode($product['picture']) . '" alt="' . $product['name'] . '" width="200" height="200">';
        echo '<h2>' . $product['name'] . '</h2>';
        echo '<p style="font-size: 18px; color: #333; font-weight: bold;"> Price: ' . number_format($product['price'], 2) . ' Lei</p>';
        echo '<form method="post" action="adminproducts.php">';
        echo '<input type="hidden" name="product_id" value="' . $product['id'] . '">';
        echo '<button type="submit" name="delete_product">Elimină</button>';
        echo '<form method="post" action="adminproducts.php">';
        echo '<input type="hidden" name="product_id" value="' . $product['id'] . '">';
        echo '<button type="submit" name="edit_product">Modifică</button>';
        echo '</form>';
        echo '</div>';
    }
    if (isset($_POST['save_changes'])) {
        $edit_product_id = intval($_POST['edit_product_id']);
        $edit_name = $_POST['edit_name'];
        $edit_price = intval($_POST['edit_price']);
    
        $update_sql = "UPDATE products SET name = ?, price = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sii", $edit_name, $edit_price, $edit_product_id);
    
        if ($update_stmt->execute()) {
            header("Location: adminproducts.php");
        } else {
            echo "Eroare la actualizarea produsului: " . $conn->error;
        }
        $update_stmt->close();
    }
    ?>
</div>
    </main>
    <footer>
    </footer>
    <script src="button.js"></script>
    <script>
        function closeEditModal() {
        document.getElementById('edit-product-section').style.display = 'none';
    }
</script>
</body>
</html>
