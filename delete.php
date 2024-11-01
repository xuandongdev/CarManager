<?php
$conn = oci_connect('c##XUANDONGTEST', '123456', 'localhost:1521/orcl21');


if (isset($_POST['MA_XE'])) {
    $idToDelete = $_POST['MA_XE'];
    delete($conn, $idToDelete);
}

function delete($conn, $id)
{
    $sql = "BEGIN XOA_XE(:id); END;";
    $result = oci_parse($conn, $sql);
    oci_bind_by_name($result, ':id', $id);

    if (oci_execute($result)) {
        echo "Xóa thành công!";
    } else {
        $e = oci_error($result);
        echo "Lỗi khi xóa: " . htmlentities($e['message']);
    }
}
?>