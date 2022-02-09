<?php include_once "partials/cssdatatables.php" ?>

<?php 
if (isset($_POST['button_create'])){
    $database = new Database();
    $db = $database->getConnection();

    $validateSql = "SELECT * FROM lokasi WHERE nama_lokasi = ?";
    $stmt = $db->prepare($validateSql);
    $stmt ->bindParam(1, $_POST['nama_lokasi']);
    $stmt ->execute();
    if ($stmt->rowCount()>0){
?>
    <div class="alert alert-danger alert-dismissible m-2" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h5><i class="icon fas fa-ban"></i>Gagal</h5>
        Nama Lokasi Sudah ada
    </div>
    <?php 
    } else {

    $insertSql = "INSERT INTO lokasi (nama_lokasi) VALUES (?)";
    $stmt = $db->prepare($insertSql);
    $stmt ->bindParam(1, $_POST['nama_lokasi']);
    if ($stmt->execute()){
        $_SESSION['hasil'] = true;
        $_SESSION['pesan'] = "Tambah data lokasi berhasil";
    } else {
        $_SESSION['hasil'] = false;
        $_SESSION['pesan'] = "Tambah data lokasi gagal";
    }
    echo "<meta http-equiv='refresh' content='0;url=?page=lokasiread'>";
}
}
?>

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Lokasi</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="?page=home">Home</a>
                    </li>
                    <li class="breadcrumb-item">Lokasi</li>
                    <li class="breadcrumb-item active">Tambah Data</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Tambah Lokasi</h3>
            <!-- <a href="?page=lokasicreate" class="btn btn-success btn-sm float-right">
                <i class="fa fa-plus-circle"></i> Tambah Data</a> -->
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label for="nama_lokasi">Nama Lokasi</label>
                    <input type="text" class="form-control" name="nama_lokasi">
                </div>
               <a href="?page=lokasiread" class="btn btn-danger btn-sm float-right"><i class="fa fa-times"></i> Batal</a>
               <button type="submit" name="button_create" class="btn btn-success btn-sm float-right mr-1"><i class="fa fa-save"></i> Simpan</button>
        </div>
    </div>
</section>
<!-- /.content -->

<?php include "partials/scripts.php" ?>
<?php include "partials/scriptsdatatables.php" ?>
<script>
    $(function() {
        $('#mytable').DataTable()
    });
</script>