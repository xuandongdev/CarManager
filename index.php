<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
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
      <div class="logo">
        <a href="#"><img src="assets/logo1.png" alt="logo" /></a>
      </div>
      <div class="nav__menu__btn" id="menu-btn">
        <i class="ri-menu-3-line"></i>
      </div>
    </div>
    <ul class="nav__links" id="nav-links">
      <li><a href="#info">Thông tin xe</a></li>
      <li><a href="vehicleTotalPrice.php">Giá lăn bánh</a></li>
      <button class="btn"><a href="login.php">Login</a></button>
    </ul>
  </nav>
  <div class="section__container header__container" id="home">
    <div class="header__content">
      <h1>We Are Qualified & Professional</h1>
    </div>
  </div>
</header>

<body>
  <section id="info">
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
      echo "<script>console.log('Connection succeeded');</script>";
    }

    $sql = "SELECT MA_XE, DONG_XE, PHIEN_BAN, PHAN_KHUC, DONG_CO, GIA_NIEM_YET, DAM_PHAN FROM XE";
    $result = oci_parse($conn, $sql);
    oci_execute($result);

    echo '<div class="container mt-3">';
    echo '<h1>BẢNG GIÁ XE</h1>';
    echo '<table class="table table-bordered">';
    echo '<thead>';
    echo '<tr>
            <th>Mã xe</th>
            <th>Dòng xe</th>
            <th>Phiên bản</th>
            <th>Phân khúc</th>
            <th>Động cơ</th>
            <th>Giá niêm yết</th>
            <th>Đàm phán</th>
          </tr>';
    echo '</thead>';
    echo '<tbody>';

    while ($row = oci_fetch_assoc($result)) {
      echo '<tr>';
      echo "<td>" . htmlspecialchars($row['MA_XE']) . "</td>";
      echo "<td>" . htmlspecialchars($row['DONG_XE']) . "</td>";
      echo "<td>" . htmlspecialchars($row['PHIEN_BAN']) . "</td>";
      echo "<td>" . htmlspecialchars($row['PHAN_KHUC']) . "</td>";
      echo "<td>" . htmlspecialchars($row['DONG_CO']) . "</td>";
      echo "<td>" . htmlspecialchars($row['GIA_NIEM_YET']) . "</td>";
      echo "<td>" . htmlspecialchars($row['DAM_PHAN']) . "</td>";
      echo '</tr>';
    }

    echo '</tbody>';
    echo '</table>';
    echo '</div>';
    ?>
  </section>
</body>

<section class="section__container testimonial__container" id="client">
  <p class="section__subheader">CLIENT TESTIMONIALS</p>
  <h2 class="section__header">100% Approved By Customers</h2>
  <!-- Slider main container -->
  <div class="swiper">
    <!-- Additional required wrapper -->
    <div class="swiper-wrapper">
      <!-- Slides -->
      <div class="swiper-slide">
        <div class="testimonial__card">
          <img src="assets/nixinh.jpg" alt="testimonial" />
          <p>
            "Tôi đã có một trải nghiệm tuyệt vời khi mua xe tại đây. Nhân viên rất nhiệt tình và chu đáo, họ giải đáp
            mọi thắc mắc của tôi một cách tận tâm, từ tính năng xe đến các lựa chọn tài chính. Không khí tại cửa hàng
            thoải mái và thân thiện, giúp tôi có cảm giác yên tâm khi ra quyết định. Đây thực sự là một nơi đáng để
            tin tưởng!
          </p>
          <h4>- Ni Xinh.</h4>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="testimonial__card">
          <img src="assets/ngochoa.jpg" alt="testimonial" />
          <p>
            "Tôi rất hài lòng với sự chuyên nghiệp và tận tâm của đội ngũ tại cửa hàng. Họ không chỉ giúp tôi chọn lựa
            mẫu xe phù hợp mà còn hỗ trợ chi tiết về các thủ tục mua xe và đăng ký. Nhờ vậy, tôi cảm thấy an tâm và
            tin tưởng vào quyết định của mình. Tôi chắc chắn sẽ giới thiệu cửa hàng cho bạn bè và người thân!"
          </p>
          <h4>- Hoa Trinh Ngoc.</h4>
        </div>
      </div>
      <div class="swiper-slide">
        <div class="testimonial__card">
          <img src="assets/huudan.jpg" alt="testimonial" />
          <p>
            "Trải nghiệm mua xe tại cửa hàng vượt ngoài mong đợi của tôi. Sự chuyên nghiệp, minh bạch và tư vấn rõ
            ràng từ đội ngũ bán hàng giúp tôi chọn được chiếc xe ưng ý mà không gặp bất kỳ khó khăn nào. Tôi đặc biệt
            ấn tượng với dịch vụ hậu mãi và sự hỗ trợ nhiệt tình ngay cả sau khi mua xe."
          </p>
          <h4>- Dan Duong.</h4>
        </div>
      </div>
    </div>
    <!-- If we need pagination -->
    <div class="swiper-pagination"></div>
  </div>
</section>

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
      <h4>Our Socials</h4>
      <ul class="footer__socials">
        <li>
          <a href="https://www.facebook.com/nauXgnoD.Y/">
            <i class="ri-facebook-fill"></i>
          </a>
        </li>
        <li>
          <a href="https://www.instagram.com/__xuandong/">
            <i class="ri-instagram-line"></i></a>
        </li>
        <li>
          <a href="https://github.com/xuandongdev">
            <i class="ri-github-fill"></i>
          </a>
        </li>
        <li>
          <a href="https://www.linkedin.com/in/xuandongdev/">
            <i class="ri-linkedin-box-fill"></i>
          </a>
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

<script src="https://unpkg.com/scrollreveal"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
<script src="main.js"></script>
</body>

</html>