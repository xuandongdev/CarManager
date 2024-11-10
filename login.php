<?php
    session_start(); // Khởi động session

    // Kết nối đến Oracle Database
    $conn = oci_connect(
        'c##XUANDONGTEST',
        '123456',
        'localhost:1521/orcl21',
        'AL32UTF8'
    );

    if ($conn) {
        // Kiểm tra xem form đã được submit hay chưa
        if (isset($_POST['username'], $_POST['password'])) {
            // Lấy thông tin từ form
            $username = $_POST['username'];
            $password = $_POST['password'];

            // Truy vấn để kiểm tra người dùng và mật khẩu
            $sql = "SELECT USER_NAME, PASS_WORD, ROLE FROM ACCOUNTS WHERE USER_NAME = :username";
            $result = oci_parse($conn, $sql);

            // Liên kết giá trị tham số vào câu lệnh SQL
            oci_bind_by_name($result, ":username", $username);

            // Thực thi câu lệnh SQL
            oci_execute($result);

            // Kiểm tra xem có tìm thấy người dùng không
            if ($row = oci_fetch_assoc($result)) {
                // Kiểm tra mật khẩu đã được hash
                if (($row['PASS_WORD'] === '12345' && $password === '12345') ||
                    ($row['PASS_WORD'] === '123456' && $password === '123456') ||
                    password_verify($password, $row['PASS_WORD'])) {
                    
                    // Đăng nhập thành công, lưu thông tin vào session
                    $_SESSION['username'] = $row['USER_NAME'];
                    $_SESSION['role'] = $row['ROLE'];

                    // Kiểm tra vai trò và chuyển hướng
                    if ($row['ROLE'] === 'ADMIN' || $row['ROLE'] === 'STORE MANAGER' || $row['ROLE'] === 'SALER') {
                        $_SESSION['username'] = $username;
                        header("Location: admin.php");
                    } else {
                        header("Location: index.php");
                    }
                    exit();
                } else {
                    // Mật khẩu không đúng
                    $error_message = "Invalid password!";
                }
            } else {
                // Không tìm thấy người dùng
                $error_message = "User not found!";
            }

            // Giải phóng tài nguyên
            oci_free_statement($result);
        }

        // Đóng kết nối
        oci_close($conn);
    } else {
        $error_message = "Database connection failed!";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles.css">
</head>

<header class="header">
    <div class="logo">
        <a href="index.php"><img src="assets/logo1.png" alt="logo" /></a>
    </div>
</header>

<body>
    <div class="wrapper">
        <form method="POST" action="">
            <h1>Login</h1>
            <div class="input-box">
                <input type="text" placeholder="Username" name="username" required>
            </div>
            <div class="input-box">
                <input type="password" placeholder="Password" name="password" required>
            </div>
            <div class="remember-forgot">
                <label><input type="checkbox">Remember me!</label>
                <a href="#">Forgot Password?</a>
            </div>
            <button type="submit" class="btn">Login</button>
            <div class="register">
                <p>Create an account now!
                    <a href="register.php">Register</a>
                </p>
            </div>
        </form>

        <?php
        // Hiển thị thông báo lỗi nếu có
        if (isset($error_message)) {
            echo "<p style='color: red;'>$error_message</p>";
        }
        ?>
    </div>
</body>

</html>
