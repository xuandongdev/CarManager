<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
    <link rel="stylesheet" href="style.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
    <title>XuanDong Car</title>
</head>

<header class="header">
    <nav>
        <div class="nav__bar">
            <div class="logo nav__logo">
                <a href="index.php"><img src="assets/logo1.png" alt="logo" /></a>
            </div>
            <div class="nav__menu__btn" id="menu-btn">
                <i class="ri-menu-3-line"></i>
            </div>
        </div>
        <ul class="nav__links" id="nav-links">
            <li><a href="index.php">Thông tin xe</a></li>
            <li><a href="deleteLog.php">Kiểm tra xe</a></li>
            <li><a href="changedPriceLog.php">Kiểm tra giá</a></li>
            <li><a href="vehicleTotalPrice.php">Giá lăn bánh</a></li>
            <button class="btn">Contact Us</button>
        </ul>
    </nav>
    <div class="section__container header__container" id="home">
        <div class="header__content">
            <h1>We Are Qualified & Professional</h1>
        </div>
    </div>
</header>

<body>
    <?php
    $conn = oci_connect(
        'c##XUANDONGTEST',
        '123456',
        'localhost:1521/orcl21',
        'AL32UTF8'
    );

    if (!$conn) {
        $e = oci_error();
        echo "Kết nối thất bại: " . htmlentities($e['message']);
    } else {
        echo "<script>console.log('Connection successded');</script>";
    }
    $sql = "SELECT MA_XE, MODIFIED_BY FROM DATA_LOG_DELETE";
    $result = oci_parse($conn, $sql);
    oci_execute($result);
    echo '<div class="container mt-3">';
    echo '<h1>BẢNG THEO DÕI VIỆC XÓA XE</h1>';
    echo '<table class="table table-bordered">';
    echo '<thead>';
    echo '<tr>
            <th>Mã xe đã xóa</th>
            <th>Người thực hiện</th>
    </tr>';
    echo '</thead>';
    while ($row = oci_fetch_assoc($result)) {
        echo '<tbody>';
        echo '<tr>';
        echo "<td>" . htmlspecialchars($row['MA_XE']) . "</td>";
        echo "<td>" . htmlspecialchars($row['MODIFIED_BY']) . "</td>";
        echo '</tr>';
        echo '</tbody>';
    }
    echo '</table>';
    echo '</div>';
    ?>
</body>

<div class="footer__bar">
    Copyright © XuanDongDev.
</div>
<script src="https://unpkg.com/scrollreveal"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script src="main.js"></script>
</body>

</html>