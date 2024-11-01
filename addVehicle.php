<?php
$conn = oci_connect(
    'c##QLBANXE',
    '123',
    'localhost:1521/orcl21'
);

if (isset($_POST['ma_xe_moi']) && isset($_POST['dong_xe']) && isset($_POST['phien_ban']) && isset($_POST['hang_xe']) && isset($_POST['phan_khuc']) && isset($_POST['dong_co']) && isset($_POST['gianiemyet']) && isset($_POST['damphan'])) {
    $maxe = $_POST['ma_xe_moi'];
    $dongxe = $_POST['dong_xe'];
    $phienban = $_POST['phien_ban'];
    $hangxe = $_POST['hang_xe'];
    $phankhuc = $_POST['phan_khuc'];
    $dongco = $_POST['dong_co'];
    $gianiemyet = $_POST['gianiemyet'];
    $damphan = $_POST['damphan'];
    addCars($conn, $maxe, $dongxe, $phienban, $hangxe, $phankhuc, $dongco, $gianiemyet, $damphan);
} else {
    echo "Dữ liệu không đầy đủ.";
}

function addCars($conn, $maxe, $dongxe, $phienban, $hangxe, $phankhuc, $dongco, $gianiemyet, $damphan)
{
    $sql = "BEGIN NHAP_XE(:maxe, :dongxe, :phienban, :hangxe, :phankhuc, :dongco, :gianiemyet, :damphan); END;";
    $result = oci_parse($conn, $sql);

    if (!$result) {
        $e = oci_error($conn);
        echo "Lỗi parse SQL: " . htmlentities($e['message']);
        return;
    }

    oci_bind_by_name($result, ':maxe', $maxe);
    oci_bind_by_name($result, ':dongxe', $dongxe);
    oci_bind_by_name($result, ':phienban', $phienban);
    oci_bind_by_name($result, ':hangxe', $hangxe);
    oci_bind_by_name($result, ':phankhuc', $phankhuc);
    oci_bind_by_name($result, ':dongco', $dongco);
    oci_bind_by_name($result, ':gianiemyet', $gianiemyet);
    oci_bind_by_name($result, ':damphan', $damphan);

    if (oci_execute($result)) {
        echo "Thêm xe thành công!";
    } else {
        $e = oci_error($result);
        echo "Lỗi thêm xe: " . htmlentities($e['message']);
    }

    oci_free_statement($result);
    oci_close($conn);
}
?>