<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "database.php";

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }
    
    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $sql)) {
        mysqli_stmt_bind_param($stmt, "s", $username);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row["password"])) {
                $_SESSION["username"] = $username;
                $_SESSION['user_id'] = $row['id'];
                if ($row["id"] == 1) {
                    // daca e admin role merge pe pagina admin
                    header("Location: adminindex.php");
                } else {
                    // daca nu este admin merge in users
                    header("Location: products.html");
                }
                
                exit();
            } else {
                echo "Invalid username or password.";
            }
        } else {
            echo "Invalid username or password.";
        }
    } else {
        echo "Error in the SQL statement: " . mysqli_error($conn);
    }

    mysqli_close($conn);
}
?>
