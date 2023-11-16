<?php
session_start();
include('adminorders_logic.php');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - PC Components Store</title>
    <link rel="stylesheet" href="css/style.css">
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
        <div class="order-list">
            <?php
            foreach ($orders as $order_id => $order) {
                if (isset($order['Products']) && !empty($order['Products'])) {
                    echo '<div class="order-block">';
                    echo '<h3>Order ID: ' . (isset($order['OrderID']) ? $order['OrderID'] : '') . '</h3>';
                    echo '<p>User Name: ' . (isset($order['UserName']) ? $order['UserName'] : '') . '</p>';
                    echo '<p>Order Date: ' . (isset($order['OrderDate']) ? $order['OrderDate'] : '') . '</p>';
                    $total_price = 0;
                    foreach ($order['Products'] as $product) {
                        $total_price += $product['Quantity'] * $product['ProductPrice'];
                    }
                    echo '<p>Total Price: ' . $total_price . '</p>';
                    
                    echo '<table>
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>';
                    foreach ($order['Products'] as $product) {
                        echo '<tr>';
                        echo '<td>' . (isset($product['ProductName']) ? $product['ProductName'] : '') . '</td>';
                        echo '<td>' . (isset($product['Quantity']) ? $product['Quantity'] : '') . '</td>';
                        echo '<td>
                                <form method="post" action="">
                                    <input type="hidden" name="order_id" value="' . $order_id . '">
                                    <input type="hidden" name="product_id" value="' . $product['ProductID'] . '">
                                    <button type="submit" name="delete_order_item">Delete One Item</button>
                                </form>
                              </td>';
                        echo '</tr>';
                    }

                    echo '</tbody>
                        </table>';
                    echo '<p><a href="?delete_order=' . $order['OrderID'] . '">Delete Order</a></p>';
                    echo '</div>';
                }
            }
            ?>
        </div>
    </main>
    <footer>
    </footer>
    <script src="button.js"></script>
</body>
</html>
