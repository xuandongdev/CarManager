<?php
session_start(); // Khởi động session

// Kết nối đến Oracle Database
$conn = oci_connect('c##XUANDONGTEST', '123456', 'localhost:1521/orcl21', 'AL32UTF8');
$message = '';

// Xử lý đăng ký khi người dùng nhấn nút "Register"
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $phone = $_POST['phone'];
    $email = $_POST['email'] ?? null;

    // Kiểm tra xem tên người dùng đã tồn tại chưa
    $checkSql = "SELECT COUNT(*) AS COUNT FROM ACCOUNTS WHERE USER_NAME = :username";
    $checkStid = oci_parse($conn, $checkSql);
    oci_bind_by_name($checkStid, ":username", $username);
    oci_execute($checkStid);
    $row = oci_fetch_assoc($checkStid);

    if ($row['COUNT'] > 0) {
        $message = "Username already exists!";
    } else {
        // Băm mật khẩu và chèn dữ liệu vào bảng ACCOUNTS
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $insertSql = "INSERT INTO ACCOUNTS (USER_NAME, PASS_WORD, PHONE, EMAIL) 
                        VALUES (:username, :password, :phone, :email)";
        $insertStid = oci_parse($conn, $insertSql);

        oci_bind_by_name($insertStid, ":username", $username);
        oci_bind_by_name($insertStid, ":password", $hashedPassword);
        oci_bind_by_name($insertStid, ":phone", $phone);
        oci_bind_by_name($insertStid, ":email", $email);

        if (oci_execute($insertStid)) {
            // Đăng ký thành công, chuyển hướng đến trang login.php
            header("Location: login.php");
            exit(); // Dừng việc thực thi để tránh gửi thêm dữ liệu
        } else {
            $message = "Error during registration!";
        }

        oci_free_statement($insertStid);
    }

    oci_free_statement($checkStid);
}

// Đóng kết nối
oci_close($conn);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>

    <header class="header">
        <div class="logo">
            <a href="index.php"><img src="assets/logo1.png" alt="logo" /></a>
        </div>
    </header>

    <div class="wrapper">
        <form action="register.php" method="POST">
            <h1>Register</h1>

            <!-- Hiển thị thông báo lỗi -->
            <?php if ($message): ?>
                <p class="message"><?= htmlspecialchars($message) ?></p>
            <?php endif; ?>

            <div class="input-box">
                <input type="text" name="username" placeholder="Username" required>
            </div>
            <div class="input-box">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <div class="input-box">
                <input type="email" name="email" placeholder="Email">
            </div>
            <div class="input-box">
                <input type="text" name="phone" placeholder="Phone" required>
            </div>
            <div class="remember-forgot">
                <label><input type="checkbox" name="remember">Remember me!</label>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
    </div>

</body>

</html>