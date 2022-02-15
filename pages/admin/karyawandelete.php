<?php
    if (isset($_GET['id_karyawan'])){
        $id_karyawan = $_GET['id_karyawan'];
        $id_pengguna = $_GET['id_pengguna'];

        $database = new Database();
        $db = $database->getConnection();

        $deleteSql = "DELETE FROM karyawan WHERE id = ?";
        $stmt = $db->prepare($deleteSql);
        $stmt->bindParam(1, $id_karyawan);
        if ($stmt->execute()){
            $_SESSION['hasil'] = true;
            $_SESSION['pesan'] = "data karyawan berhasil dihapus";
        } else {
            $_SESSION['hasil'] = false;
            $_SESSION['pesan'] = "data karyawan gagal dihapus";
        }

        $deleteSql = "DELETE FROM pengguna WHERE id = ?";
        $stmt = $db->prepare($deleteSql);
        $stmt->bindParam(1, $id_pengguna);
        if ($stmt->execute()){
            $_SESSION['hasil'] = true;
            $_SESSION['pesan'] = "data pengguna berhasil dihapus";
        } else {
            $_SESSION['hasil'] = false;
            $_SESSION['pesan'] = "data pengguna gagal dihapus";
        }
    }
    echo "<meta http-equiv='refresh' content='0;url=?page=karyawanread'>";
?>