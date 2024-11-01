function deleteItem(maLoaiXe) {
    if (confirm('Bạn có chắc chắn muốn xóa không?')) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "delete.php", true); // Đường dẫn tới file delete.php
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        
        xhr.onreadystatechange = function () {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    alert(xhr.responseText); 
                    location.reload(); 
                } else {
                    alert('Lỗi khi xóa: ' + xhr.status);
                }
            }
        };
        
        xhr.send("MA_LOAI_XE=" + maLoaiXe); 
    }
}
function openChangeCostForm(maLoaiXe){
    document.getElementById("change-cost-form").classList.add('show');
    document.getElementById("Ma_Xe").textContent ="Mã xe :" + maLoaiXe;
    document.getElementById("new_cost").value = maLoaiXe;
}
function closeChangeCostForm(){
    id = document.getElementById("new_cost").value;
    cost =  document.getElementById("cost").value;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "changecost.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                alert(xhr.responseText); 
                location.reload(); 
            } else {
                alert('Lỗi khi sửa: ' + xhr.status);
            }
        }
    };
    xhr.send("MA_LOAI_XE=" + encodeURIComponent(id) + "&GIA_NIEM_YET=" + encodeURIComponent(cost)); 
    document.getElementById("change-cost-form").classList.remove('show');
}
function addCar(){
    maLoaiXe = document.getElementById("ma_xe_moi").value;
    dongxe = document.getElementById("dong_xe").value;
    pb = document.getElementById("phien_ban").value;
    mhx = document.getElementById("hang_xe").value;
    pk = document.getElementById("phan_khuc").value;
    dongco = document.getElementById("dong_co").value;
    cost = document.getElementById("gia_xe").value;
    damphan = document.getElementById("dam_phan").value;
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "addCars.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded"); 
    xhr.onreadystatechange = function () {
        if (xhr.readyState === XMLHttpRequest.DONE) {
            if (xhr.status === 200) {
                alert(xhr.responseText); 
                location.reload(); 
            } else {
                alert('Lỗi khi thêm: ' + xhr.status);
            }
        }
    };
    xhr.send("ma_xe_moi=" + encodeURIComponent(maLoaiXe) + "&dong_xe=" + encodeURIComponent(dongxe) + "&phien_ban=" + encodeURIComponent(pb) + "&hang_xe=" + encodeURIComponent(mhx) + "&phan_khuc=" + encodeURIComponent(pk) + "&dong_co=" + encodeURIComponent(dongco) + "&gianiemyet=" + encodeURIComponent(cost) + "&damphan=" + encodeURIComponent(damphan));
}