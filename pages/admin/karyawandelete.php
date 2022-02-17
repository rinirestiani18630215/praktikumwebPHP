<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $database = new Database();
    $db = $database->getConnection();

    $deletePengguna = "DELETE FROM pengguna WHERE id = ?";
    $stmt = $db->prepare($deletePengguna);
    $stmt->bindParam(1, $_GET['id']);
    if ($stmt->execute()) {
        $deleteKaryawan = "DELETE FROM karyawan WHERE id = ?";
        $stmtKaryawan = $db->prepare($deleteKaryawan);
        $stmtKaryawan->bindParam(1, $_GET['id']);
        if ($stmtKaryawan->execute()) {
            $_SESSION['hasil'] = true;
            $_SESSION['pesan'] = "Berhasil delete data";
        } else {
            $_SESSION['hasil'] = false;
            $_SESSION['pesan'] = "Gagal delete data";
        }
    } else {
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Gagal delete data";
    }
}
echo "<meta http-equiv='refresh' content='0;url=?page=karyawanread'>";
