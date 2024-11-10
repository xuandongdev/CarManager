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
  <div class="section__container header__container">
    <div class="header__content">
      <h1>We Are Qualified & Professional</h1>
    </div>
  </div>
</header>

<body>
<div class="container mt-3" id="totalPrice">
    <h1>Chi Tiết Giá Lăn Bánh</h1>
    <form method="POST" action="" >
        <label for="MA_XE_LB">Nhập mã xe:</label>
        <input type="text" id="MA_XE_LB" name="MA_XE_LB" required>
        <button type="submit" class="btn btn-primary">Xem chi tiết</button>
    </form>

    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $ma_xe = $_POST['MA_XE_LB'];
        $conn = oci_connect('c##XUANDONGTEST', '123456', 'localhost:1521/orcl21', 'AL32UTF8');
        
        if (!$conn) {
            $e = oci_error();
            echo "Kết nối thất bại: " . htmlentities($e['message']);
            exit;
        }

        // Lấy giá niêm yết
        $sql = "SELECT GIA_NIEM_YET FROM XE WHERE MA_XE = :ma_xe";
        $result = oci_parse($conn, $sql);
        oci_bind_by_name($result, ':ma_xe', $ma_xe);
        oci_execute($result);
        
        // Kiểm tra kết quả
        if ($row = oci_fetch_assoc($result)) {
            $giaNiemYet = $row['GIA_NIEM_YET'];

            // Lấy phí từ bảng GIA_LAN_BANH với MA_TINH = 14 (Cần Thơ)
            $sql = "SELECT PHI_TRUOC_BA, PHI_SD_DUONG_BO, BAO_HIEM_TNDS, PHI_DK_BIEN_SO, PHI_DANG_KIEM 
                    FROM GIA_LAN_BANH WHERE MA_TINH = 14";
            $result = oci_parse($conn, $sql);
            oci_execute($result);
            $fees = oci_fetch_assoc($result);

            // Tính tổng giá lăn bánh (chỉ đơn giản cộng các phí)
            $tongGiaLanBanh = $giaNiemYet 
                            + ($giaNiemYet * $fees['PHI_TRUOC_BA']) 
                            + $fees['PHI_SD_DUONG_BO'] 
                            + $fees['BAO_HIEM_TNDS'] 
                            + $fees['PHI_DK_BIEN_SO'] 
                            + $fees['PHI_DANG_KIEM'];

            // Hiển thị thông tin chi tiết
            echo "<h2>Thông tin chi tiết cho mã xe: $ma_xe</h2>";
            echo "<ul>";
            echo "<li>Giá niêm yết: " . number_format($giaNiemYet, 0, ',', '.') . " VND</li>";
            echo "<li>Phí trước bạ: " . number_format($fees['PHI_TRUOC_BA'] * $giaNiemYet, 0, ',', '.') . " VND</li>";
            echo "<li>Phí sử dụng đường bộ: " . number_format($fees['PHI_SD_DUONG_BO'], 0, ',', '.') . " VND</li>";
            echo "<li>Bảo hiểm TNDS: " . number_format($fees['BAO_HIEM_TNDS'], 0, ',', '.') . " VND</li>";
            echo "<li>Phí đăng ký biển số: " . number_format($fees['PHI_DK_BIEN_SO'], 0, ',', '.') . " VND</li>";
            echo "<li>Phí đăng kiểm: " . number_format($fees['PHI_DANG_KIEM'], 0, ',', '.') . " VND</li>";
            echo "</ul>";
            echo "<h3>Tổng giá lăn bánh: " . number_format($tongGiaLanBanh, 0, ',', '.') . " VND</h3>";
        } else {
            echo "<p>Không tìm thấy thông tin cho mã xe: $ma_xe</p>";
        }

        // Đóng kết nối
        oci_close($conn);
    }
    ?>
</div>
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
  <div class="section__container subscribe__container">
    <div class="subscribe__content">
      <p class="section__subheader">OUR NEWSLETTER</p>
      <h2 class="section__header">Subscribe To Our Newsletter</h2>
      <p class="section__description">
        Subscribe to our newsletter and receive exclusive content, expert
        insights, and special offers delivered directly to your inbox.
      </p>
    </div>
    <div class="subscribe__form">
      <form action="/">
        <input type="text" placeholder="Your Email" />
        <button class="btn">Subscribe</button>
      </form>
    </div>
  </div>
  <div class="section__container footer__container">
    <div class="footer__col">
      <div class="logo footer__logo">
        <a href="#"><img src="assets/logo2.png" alt="logo" /></a>
      </div>
      <p class="section__description">
        With a rich legacy spanning 25 years, our commitment to excellence
        in car servicing is unwavering.
      </p>
      <ul class="footer__socials">
        <li>
          <a href="#"><i class="ri-facebook-fill"></i></a>
        </li>
        <li>
          <a href="#"><i class="ri-google-fill"></i></a>
        </li>
        <li>
          <a href="#"><i class="ri-instagram-line"></i></a>
        </li>
        <li>
          <a href="#"><i class="ri-youtube-line"></i></a>
        </li>
      </ul>
    </div>
    <div class="footer__col">
      <h4>Our Services</h4>
      <ul class="footer__links">
        <li><a href="#">Skilled Mechanics</a></li>
        <li><a href="#">Routine Maintenance</a></li>
        <li><a href="#">Customized Solutions</a></li>
        <li><a href="#">Competitive Pricing</a></li>
        <li><a href="#">Satisfaction Guaranteed</a></li>
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