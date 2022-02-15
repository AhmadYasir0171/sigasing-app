<?php
if (isset($_GET['id_karyawan'])) {

    $database = new Database();
    $db = $database->getConnection();

    $id = $_GET['id_karyawan'];
    $findSql = "SELECT karyawan.id, karyawan.nik, karyawan.nama_lengkap, karyawan.handphone, karyawan.email, karyawan.tanggal_masuk, karyawan.pengguna_id, pengguna.id, pengguna.username,pengguna.password, pengguna.peran 
    FROM karyawan 
    JOIN pengguna ON karyawan.pengguna_id = pengguna.id
    WHERE karyawan.id = ?";
    $stmt = $db->prepare($findSql);
    $stmt->bindParam(1, $_GET['id_karyawan']);
    $stmt->execute();
    $row = $stmt->fetch();
    if (isset($row['id'])) {
        if (isset($_POST['button_update'])) {

            $database = new Database();
            $db = $database->getConnection();

            $validateSql = "SELECT * FROM karyawan WHERE nama_lengkap = ? AND id = ?";
            $stmt = $db->prepare($validateSql);
            $stmt->bindParam(1, $_POST['nama_lengkap']);
            $stmt->bindParam(2, $_POST['id']);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                    <h5><i class="icon fas fa-ban"></i> Gagal</h5>
                    Nama Karyawan Sama Sudah Ada
                </div>
                <?php
            } else {
                $validateSql = "SELECT * FROM pengguna WHERE username = ?";
                $stmt = $db->prepare($validateSql);
                $stmt->bindParam(1, $_POST['username']);
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                        <h5><i class="icon fas fa-ban"></i> Gagal</h5>
                    </div>
                    <?php
                } else {
                    if ($_POST['password'] != $_POST['passsword_ulangi']) {
                    ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">X</button>
                            <h5><i class="icon fas fa-ban"></i> Gagal</h5>
                            Password tidak sama
                        </div>
<?php
                    } else {
                        $md5Password = md5($_POST['password']);
                        $updateSql = "UPDATE pengguna SET username = ?, password = ?, peran = ? WHERE id = ?";
                        $stmt = $db->prepare($updateSql);
                        $stmt->bindParam(1, $_POST['username']);
                        $stmt->bindParam(2, $md5Password);
                        $stmt->bindParam(3, $_POST['peran']);
                        $stmt->bindParam(4, $_POST['pengguna_id']);

                        if ($stmt->execute()) {

                            $pengguna_id = $db->lastInsertId();

                            $update_karyawan = "UPDATE karyawan SET nik = ?, nama_lengkap = ?, handphone = ?, email = ?, tanggal_masuk = ? WHERE id = ? ";
                            $stmt = $db->prepare($update_karyawan);
                            $stmt->bindParam(1, $_POST['nik']);
                            $stmt->bindParam(2, $_POST['nama_lengkap']);
                            $stmt->bindParam(3, $_POST['handphone']);
                            $stmt->bindParam(4, $_POST['email']);
                            $stmt->bindParam(5, $_POST['tanggal_masuk']);
                            $stmt->bindParam(6, $_POST['id']);

                            if ($stmt->execute()) {
                                $_SESSION['hasil'] = true;
                                $_SESSION['pesan'] = "Berhasil simpan data";
                            } else {
                                $_SESSION['hasil'] = false;
                                $_SESSION['pesan'] = "Gagal simpan data";
                            }
                        } else {
                            $_SESSION['hasil'] = false;
                            $_SESSION['pesan'] = "Gagal simpan data";
                        }
                        echo "<meta http-equiv='refresh' content='0;url=?page=karyawanread'>";
                    }
                }
            }
        }
    }
}
?>
<?php include_once "partials/cssdatatables.php" ?>
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Ubah Data Karyawan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
                    <li class="breadcrumb-item"><a href="?page=bagianread">Karyawan</a></li>
                    <li class="breadcrumb-item active">Ubah Data</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</section>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Ubah Karyawan</h3>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nik">Nomor Induk Karyawan</label>
                            <input type="hidden" class="form-control" name="id" value="<?php echo $row['id'] ?>">
                            <input type="hidden" class="form-control" name="pengguna_id" value="<?php echo $row['pengguna_id'] ?>">
                            <input type="text" class="form-control" id="nik" name="nik" value="<?php echo $row['nik'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" value="<?php echo $row['nama_lengkap'] ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="handphone">Handphone</label>
                            <input type="text" class="form-control" name="handphone" value="<?php echo $row['handphone'] ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control" name="email" value="<?php echo $row['email'] ?>">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="email">Username</label>
                            <input type="text" class="form-control" name="username" value="<?php echo $row['username'] ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tanggal_masuk">Tanggal Masuk</label>
                            <input type="date" class="form-control" name="tanggal_masuk" value="<?php echo $row['tanggal_masuk'] ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" value="<?php echo $row['password'] ?>">
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label for="passsword_ulangi">Password (Ulangi)</label>
                            <input type="text" class="form-control" name="passsword_ulangi"  value="<?php echo $row['password'] ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="peran">Peran</label>
                            <select class="form-control" name="peran">
                                <option value="">-- Pilih Peran --</option>
                                <option <?php if ($row['peran'] == "ADMIN") {
                                            echo 'selected';
                                        } ?> value="ADMIN">ADMIN</option>
                                <option <?php if ($row['peran'] == "USER") {
                                            echo 'selected';
                                        } ?> value="USER">USER</option>
                            </select>
                        </div>
                    </div>
                </div>
                <a href="?page=bagianread" class="btn btn-danger btn-sm float-right"><i class="fa fa-times"></i> Batal</a>
                <button type="submit" name="button_update" class="btn btn-success btn-sm float-right mr-1"><i class="fa fa-save"></i> Simpan</button>
            </form>
        </div>
    </div>
</section>

<?php include "partials/scripts.php" ?>
<?php include "partials/scriptsdatatables.php" ?>
<script>
    $(function() {
        $('#mytable').DataTable()
    });
</script>