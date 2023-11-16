<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - PC Components Store</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/filtre.css">
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
        <form method="post" action="procesare_connect_product_tag.php">
            <label for="product_id">Selectează produs:</label>
            <select id="product_id" name="product_id">
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "login_register";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Conexiune eșuată: " . $conn->connect_error);
                }

                $sql = "SELECT id, name FROM products";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
                    }
                } else {
                    echo '<option value="">Nu există produse</option>';
                }
                $conn->close();
                ?>
            </select>

            <label for="tag_id">Selectează tag:</label>
            <select id="tag_id" name="tag_id">
                <?php
                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Conexiune eșuată: " . $conn->connect_error);
                }

                $sql_tags = "SELECT id, name FROM tags";
                $result_tags = $conn->query($sql_tags);

                if ($result_tags->num_rows > 0) {
                    while ($row_tags = $result_tags->fetch_assoc()) {
                        echo '<option value="' . $row_tags['id'] . '">' . $row_tags['name'] . '</option>';
                    }
                } else {
                    echo '<option value="">Nu există tag-uri</option>';
                }
                $conn->close();
                ?>
            </select>

            <button type="submit">Conectare Produs - Tag</button>
        </form>
        <form method="post" action="procesare_adaugare_tag_nou.php">
            <label for="tag_nou">Adaugă un tag nou:</label>
            <input type="text" id="tag_nou" name="tag_nou">
            <button type="submit">Adaugă Tag Nou</button>
        </form>
        <table>
        <caption></caption>
        <thead>
            <tr>
                <th>Produs</th>
                <th>Tag</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            
        <?php
        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Conexiune eșuată: " . $conn->connect_error);
        }

        $sql_product_tag = "SELECT products.name AS product_name, tags.name AS tag_name, product_tags.product_id, product_tags.tag_id
                            FROM products
                            INNER JOIN product_tags ON products.id = product_tags.product_id
                            INNER JOIN tags ON product_tags.tag_id = tags.id";

        $result_product_tag = $conn->query($sql_product_tag);

        if ($result_product_tag->num_rows > 0) {
            while ($row_product_tag = $result_product_tag->fetch_assoc()) {
                echo '<tr>';
                echo '<td>' . $row_product_tag['product_name'] . '</td>';
                echo '<td>' . $row_product_tag['tag_name'] . '</td>';
                echo '<td><form method="post" action="procesare_stergere_tag.php">';
                echo '<input type="hidden" name="product_id" value="' . $row_product_tag['product_id'] . '">';
                echo '<input type="hidden" name="tag_id" value="' . $row_product_tag['tag_id'] . '">';
                echo '<button type="submit">Șterge</button>';
                echo '</form></td>';
                echo '</tr>';
            }
        } else {
            echo '<tr><td colspan="3">Nu există produse asociate cu taguri</td></tr>';
        }
        $conn->close();
        ?>
        </tbody>
    </table>
    </main>
    <footer>
    </footer>
    <script src="button.js"></script>
</body>
</html>
