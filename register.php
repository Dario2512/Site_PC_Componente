<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    require_once "database.php";

    if (!$conn) {
        die("Database connection failed: " . mysqli_connect_error());
    }

    $username = $_POST["username"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];


    if ($password !== $confirmPassword) {
        echo "Passwords do not match.";
    }elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email is not valid.";
    } elseif (strlen($password) < 8) {
        echo "Password must be at least 8 characters long.";
    } else {

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = mysqli_stmt_init($conn);

        if (mysqli_stmt_prepare($stmt, $sql)) {
            mysqli_stmt_bind_param($stmt, "ss", $username, $email);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                echo "Username or email already exists.";
            } else {

                $insertSql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                $stmt = mysqli_stmt_init($conn);

                if (mysqli_stmt_prepare($stmt, $insertSql)) {
                    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $passwordHash);
                    mysqli_stmt_execute($stmt);
                    $_SESSION["username"] = $username;
                    header("Location: index.html");
                    
                } else {
                    echo "Something went wrong during registration.";
                }
            }
        } else {
            echo "Error in the SQL statement: " . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
}
?>
