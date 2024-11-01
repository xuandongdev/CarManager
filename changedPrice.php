<?php
$conn = oci_connect(
    'c##QLBANXE',
    '123',
    'localhost:1521/orcl21' 
);
if (isset($_POST['MA_LOAI_XE']) ) {
    $idToChange = $_POST['MA_LOAI_XE'];
    $cost = $_POST['GIA_NIEM_YET'];
    changecost($conn, $idToChange, $cost);
}
function changecost($conn,$id,$cost){
    $sql = "BEGIN CAP_NHAT_GIA_XE(:id, :cost); END;";
    $result = oci_parse($conn, $sql);
    oci_bind_by_name($result, ':id', $id);
    oci_bind_by_name($result, ':cost', $cost);
    if (oci_execute($result)) {
        echo "Cập nhật giá thành công!";
    } else {
        $e = oci_error($result);
        echo "Lỗi cập nhật giá: " . htmlentities($e['message']);
    }
}
?>