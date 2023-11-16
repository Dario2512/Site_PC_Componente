<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "login_register";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexiune eșuată: " . $conn->connect_error);
}


if (isset($_POST['delete_order_item'])) {
    $order_id_to_delete_item = $_POST['order_id'];
    $product_id_to_delete_item = $_POST['product_id'];

    
    $select_order_item_sql = "SELECT Quantity FROM orderdetails WHERE OrderID = ? AND ProductID = ? LIMIT 1";
    $select_order_item_stmt = $conn->prepare($select_order_item_sql);
    $select_order_item_stmt->bind_param("ii", $order_id_to_delete_item, $product_id_to_delete_item);
    $select_order_item_stmt->execute();
    $select_order_item_result = $select_order_item_stmt->get_result();

    if ($select_order_item_result->num_rows > 0) {
        $quantity = $select_order_item_result->fetch_assoc()['Quantity'];

        
        if ($quantity > 1) {
            $update_quantity_sql = "UPDATE orderdetails SET Quantity = Quantity - 1 WHERE OrderID = ? AND ProductID = ? LIMIT 1";
            $update_quantity_stmt = $conn->prepare($update_quantity_sql);
            $update_quantity_stmt->bind_param("ii", $order_id_to_delete_item, $product_id_to_delete_item);
            $update_quantity_stmt->execute();
        } else {
            $delete_order_item_sql = "DELETE FROM orderdetails WHERE OrderID = ? AND ProductID = ? LIMIT 1";
            $delete_order_item_stmt = $conn->prepare($delete_order_item_sql);
            $delete_order_item_stmt->bind_param("ii", $order_id_to_delete_item, $product_id_to_delete_item);
            $delete_order_item_stmt->execute();
        }

        
        header("Location: adminorders.php");
    } else {
        echo "Eroare la obținerea informațiilor despre item-ul comenzii: " . $conn->error;
    }

    $select_order_item_stmt->close();
    if (isset($update_quantity_stmt)) {
        $update_quantity_stmt->close();
    }
    if (isset($delete_order_item_stmt)) {
        $delete_order_item_stmt->close();
    }
}


if (isset($_GET['delete_order'])) {
    $order_id_to_delete = $_GET['delete_order'];

    $delete_order_details_sql = "DELETE FROM orderdetails WHERE OrderID = ?";
    $delete_order_details_stmt = $conn->prepare($delete_order_details_sql);
    $delete_order_details_stmt->bind_param("i", $order_id_to_delete);

    if ($delete_order_details_stmt->execute()) {
        $delete_order_sql = "DELETE FROM orders WHERE OrderID = ?";
        $delete_order_stmt = $conn->prepare($delete_order_sql);
        $delete_order_stmt->bind_param("i", $order_id_to_delete);

        if ($delete_order_stmt->execute()) {
            header("Location: adminorders.php");
        } else {
            echo "Eroare la ștergerea comenzii: " . $conn->error;
        }

        $delete_order_stmt->close();
    } else {
        echo "Eroare la ștergerea detaliilor comenzii: " . $conn->error;
    }

    $delete_order_details_stmt->close();
}


$sql = "SELECT users.username as UserName, orders.OrderID, orders.UserID, orders.OrderDate
        FROM orders
        JOIN users ON orders.UserID = users.id";
$result = $conn->query($sql);
$orders = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $order_id = $row['OrderID'];
        $orders[$order_id]['OrderID'] = $row['OrderID'];
        $orders[$order_id]['UserName'] = $row['UserName'];
        $orders[$order_id]['OrderDate'] = $row['OrderDate'];
    }
}


foreach ($orders as $order_id => $order) {
    $sql_products = "SELECT orderdetails.Quantity, products.name as ProductName, products.price as ProductPrice, orderdetails.ProductID
                    FROM orderdetails
                    JOIN products ON orderdetails.ProductID = products.id
                    WHERE orderdetails.OrderID = $order_id";
    $result_products = $conn->query($sql_products);

    $orders[$order_id]['Products'] = []; 

    if ($result_products && $result_products->num_rows > 0) {
        while ($product_row = $result_products->fetch_assoc()) {
            $product_id = isset($product_row['ProductID']) ? $product_row['ProductID'] : null;

            $orders[$order_id]['Products'][] = [
                'ProductID' => $product_id,
                'ProductName' => $product_row['ProductName'],
                'Quantity' => $product_row['Quantity'],
                'ProductPrice' => $product_row['ProductPrice'],
            ];
        }
    }
    
}
?>
