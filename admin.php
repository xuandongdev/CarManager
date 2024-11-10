<?php
session_start();
// Nếu session không tồn tại hoặc người dùng không có quyền, chuyển hướng sang index
if (!isset($_SESSION['username'])) {
  header('Location: index.php');
  exit;
}

$username = $_SESSION['username'];
$role = $_SESSION['role']; // Lấy vai trò từ session (cần đảm bảo rằng vai trò đã được lưu trong session khi đăng nhập)

// Kết nối đến Oracle Database
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // Lấy giá trị từ form và thực hiện thao tác tương ứng
  $action = $_POST['action'];

  if ($action == 'them_xe') {
    // Kiểm tra quyền trước khi thực hiện thêm xe
    if ($role == 'STORE MANAGER' || $role == 'ADMIN') {
      // Thêm xe
      $ma_xe = $_POST['ma_xe'];
      $ma_hang = $_POST['ma_hang'];
      $dong_xe = $_POST['dong_xe'];
      $phien_ban = $_POST['phien_ban'];
      $phan_khuc = $_POST['phan_khuc'];
      $dong_co = $_POST['dong_co'];
      $gia_nyet = $_POST['gia_nyet'];
      $dam_phan = $_POST['dam_phan'];

      // Gọi thủ tục THEM_XE
      $query = "BEGIN THEM_XE(:ma_xe, :ma_hang, :dong_xe, :phien_ban, :phan_khuc, :dong_co, :gia_nyet, :dam_phan); END;";
      $addVehicle = oci_parse($conn, $query);
      oci_bind_by_name($addVehicle, ":ma_xe", $ma_xe);
      oci_bind_by_name($addVehicle, ":ma_hang", $ma_hang);
      oci_bind_by_name($addVehicle, ":dong_xe", $dong_xe);
      oci_bind_by_name($addVehicle, ":phien_ban", $phien_ban);
      oci_bind_by_name($addVehicle, ":phan_khuc", $phan_khuc);
      oci_bind_by_name($addVehicle, ":dong_co", $dong_co);
      oci_bind_by_name($addVehicle, ":gia_nyet", $gia_nyet);
      oci_bind_by_name($addVehicle, ":dam_phan", $dam_phan);

      if (oci_execute($addVehicle)) {
        $message = "Thêm xe thành công!";
      } else {
        $message = "Thêm xe thất bại.";
      }
    } else {
      $message = "Bạn không có quyền thực hiện thao tác này.";
    }
  } elseif ($action == 'cap_nhat_gia') {
    // Kiểm tra quyền trước khi thực hiện cập nhật giá
    if ($role == 'STORE MANAGER' || $role == 'SALER' || $role == 'ADMIN') {
      // Cập nhật giá xe
      $ma_xe = $_POST['ma_xe'];
      $gia_xe = $_POST['gia_xe'];

      // Lấy tên người dùng từ session
      $username = $_SESSION['username']; 

      // Gọi thủ tục CAP_NHAT_GIA và truyền tên người dùng vào
      $query = "BEGIN CAP_NHAT_GIA(:ma_xe, :gia_xe, :modified_by); END;";
      $updatePrice = oci_parse($conn, $query);
      oci_bind_by_name($updatePrice, ":ma_xe", $ma_xe);
      oci_bind_by_name($updatePrice, ":gia_xe", $gia_xe);
      oci_bind_by_name($updatePrice, ":modified_by", $username); // Truyền tên người thực hiện thao tác

      if (oci_execute($updatePrice)) {
        $message = "Cập nhật giá xe thành công!";
      } else {
        $message = "Cập nhật giá xe thất bại.";
      }
    } else {
      $message = "Bạn không có quyền thực hiện thao tác này.";
    }
  } elseif ($action == 'xoa_xe') {
    // Kiểm tra quyền trước khi thực hiện xóa xe
    if ($role == 'STORE MANAGER' || $role == 'ADMIN') {
      // Xóa xe
      $ma_xe = $_POST['ma_xe'];

      // Lấy tên người dùng từ session
      $username = $_SESSION['username']; 

      // Gọi thủ tục XOA_XE và truyền tên người dùng vào
      $query = "BEGIN XOA_XE(:ma_xe, :modified_by); END;";
      $deleteVehicle = oci_parse($conn, $query);
      oci_bind_by_name($deleteVehicle, ":ma_xe", $ma_xe);
      oci_bind_by_name($deleteVehicle, ":modified_by", $username);

      if (oci_execute($deleteVehicle)) {
        $message = "Xóa xe thành công!";
      } else {
        $message = "Xóa xe thất bại.";
      }
    } else {
      $message = "Bạn không có quyền thực hiện thao tác này.";
    }
  }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
  <title>XuanDongCar - Admin</title>
</head>

<header class="header">
  <nav>
    <div class="nav__bar">
      <div class="logo">
        <a href="#"><img src="assets/logo1.png" alt="logo" /></a>
      </div>
      <div class="nav__menu__btn" id="menu-btn">
        <i class="ri-menu-3-line"></i>
      </div>
    </div>
    <ul class="nav__links" id="nav-links">
      <li><a href="index.php" target="_blank">Thông tin xe</a></li>
      <li><a href="vehicleTotalPrice.php" target="_blank">Giá lăn bánh</a></li>
      <li><a href="#addVehicle">Thêm xe mới</a></li>
      <li><a href="#updatePrice">Cập nhật giá</a></li>
      <li><a href="#deleteVehicle">Xóa xe cũ</a></li>
      <button class="btn" type="submit" name="logout"><a href="logout.php">Logout</a></button>
    </ul>
  </nav>
  <div class="section__container header__container" id="home">
    <div class="header__content">
      <h1>We Are Qualified & Professional</h1>
    </div>
  </div>
</header>

<body>
  <h1 style="text-align: center;">Quản lý xe - Admin</h1>
  <?php if (isset($message)): ?>
    <div class="alert alert-info text-center" role="alert">
      <?php echo $message; ?>
    </div>
  <?php endif; ?>

  <!-- Thêm xe -->
  <div id="addVehicle" class="container mt-5">
    <h2 class="mb-4">Thêm Xe</h2>
    <form method="POST" class="row g-3">
      <input type="hidden" name="action" value="them_xe">

      <div class="col-md-6">
        <label for="ma_xe" class="form-label">Mã Xe:</label>
        <input type="text" name="ma_xe" id="ma_xe" class="form-control" required>
      </div>

      <div class="col-md-6">
        <label for="ma_hang" class="form-label">Mã Hãng:</label>
        <input type="text" name="ma_hang" id="ma_hang" class="form-control" required>
      </div>

      <div class="col-md-6">
        <label for="dong_xe" class="form-label">Dòng Xe:</label>
        <input type="text" name="dong_xe" id="dong_xe" class="form-control" required>
      </div>

      <div class="col-md-6">
        <label for="phien_ban" class="form-label">Phiên Bản:</label>
        <input type="text" name="phien_ban" id="phien_ban" class="form-control" required>
      </div>

      <div class="col-md-6">
        <label for="phan_khuc" class="form-label">Phân Khúc:</label>
        <input type="text" name="phan_khuc" id="phan_khuc" class="form-control" required>
      </div>

      <div class="col-md-6">
        <label for="dong_co" class="form-label">Dòng Cơ:</label>
        <input type="text" name="dong_co" id="dong_co" class="form-control" required>
      </div>

      <div class="col-md-6">
        <label for="gia_nyet" class="form-label">Giá Niêm Yết:</label>
        <input type="number" name="gia_nyet" id="gia_nyet" class="form-control" required>
      </div>

      <div class="col-md-6">
        <label for="dam_phan" class="form-label">Đàm Phán:</label>
        <input type="text" name="dam_phan" id="dam_phan" class="form-control" required>
      </div>

      <div class="col-12">
        <button type="submit" class="btn btn-primary w-100">Thêm Xe</button>
      </div>
    </form>
  </div>

  <!-- Cập nhật giá xe -->
  <div id="updatePrice" class="container mt-5">
    <h2 class="mb-4">Cập Nhật Giá Xe</h2>
    <form method="POST" class="row g-3">
      <input type="hidden" name="action" value="cap_nhat_gia">

      <div class="col-md-6">
        <label for="ma_xe" class="form-label">Mã Xe:</label>
        <input type="text" name="ma_xe" id="ma_xe" class="form-control" required>
      </div>

      <div class="col-md-6">
        <label for="gia_xe" class="form-label">Giá Xe Mới:</label>
        <input type="number" name="gia_xe" id="gia_xe" class="form-control" required>
      </div>

      <div class="col-12">
        <button type="submit" class="btn btn-warning w-100">Cập Nhật Giá</button>
      </div>
    </form>
  </div>

  <!-- Xóa xe -->
  <div id="deleteVehicle" class="container mt-5">
    <h2 class="mb-4">Xóa Xe</h2>
    <form method="POST" class="row g-3">
      <input type="hidden" name="action" value="xoa_xe">

      <div class="col-md-6">
        <label for="ma_xe" class="form-label">Mã Xe:</label>
        <input type="text" name="ma_xe" id="ma_xe" class="form-control" required>
      </div>

      <div class="col-12">
        <button type="submit" class="btn btn-danger w-100">Xóa Xe</button>
      </div>
    </form>
  </div>

</body>

<footer class="footer">
  <div class="section__container footer__container">
    <div class="footer__col">
      <div class="logo footer__logo">
        <a href="#"><img src="assets/logo2.png" alt="logo" /></a>
      </div>
      <p class="section__description">
        With a rich legacy spanning 25 years, our commitment to excellence
        in car servicing is unwavering.
      </p>
    </div>
    <div class="footer__col">
      <h4>Admin Control</h4>
      <ul class="footer__control">
        <li>
          <a href="changedPriceLog.php" target="_blank">Theo dõi thay đổi giá
            <i class="ri-facebook-fill"></i>
          </a>
        </li>
        <li>
          <a href="deleteLog.php" target="_blank">Theo dõi xe đã xóa
            <i class="ri-instagram-line"></i></a>
        </li>
      </ul>
    </div>
    <div class="footer__col">
      <h4>Contact Info</h4>
      <ul class="footer__links">
        <li>
          <p>
            Experience the magic of a rejuvenated ride as we pamper your car
            with precision care
          </p>
        </li>
        <li>
          <p>Phone: <span>07 8386 7979</span></p>
        </li>
        <li>
          <p>Email: <span>xuandong.contact@carselling.com</span></p>
        </li>
      </ul>
    </div>
  </div>
</footer>
<div class="footer__bar">
  Copyright © XuanDongDev.
</div>
<script src="main.js"></script>
</body>

</html>