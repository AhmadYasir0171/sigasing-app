<?php include_once "partials/cssdatatables.php" ?>

<?php 
if (isset($_GET['id'])){
    $database = new Database();
    $db = $database->getConnection();

    if(isset($_POST['button_update'])){
        $stmt = $db->prepare("UPDATE lokasi SET nama_lokasi = ? WHERE id = ?");
        $stmt ->bindParam(1, $_POST['nama_lokasi']);
        $stmt ->bindParam(2, $_POST['id']);
        if ($stmt->execute()){
            $_SESSION['hasil'] = true;
            $_SESSION['pesan'] = "Tambah data lokasi berhasil";
        } else {
            $_SESSION['hasil'] = false;
            $_SESSION['pesan'] = "Tambah data lokasi gagal";
        }
        echo "<meta http-equiv='refresh' content='0;url=?page=lokasiread'>";
    }
    $stmt = $db->prepare("SELECT * FROM lokasi WHERE id = ?");
    $stmt ->bindParam(1, $_GET['id']);
    $stmt ->execute();
    $row = $stmt->fetch();
    if(isset($row['id'])){
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
                    <li class="breadcrumb-item active">Ubah Data</li>
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
            <h3 class="card-title">Ubah Lokasi</h3>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="form-group">
                    <label for="nama_lokasi">Nama Lokasi</label>
                    <input type="hidden" class="form-control" name="id" value="<?php echo $row['id'] ?>">
                    <input type="text" class="form-control" name="nama_lokasi" value="<?php echo $row['nama_lokasi'] ?>">
                </div>
               <a href="?page=lokasiread" class="btn btn-danger btn-sm float-right"><i class="fa fa-times"></i> Batal</a>
               <button type="submit" name="button_update" class="btn btn-success btn-sm float-right mr-1"><i class="fa fa-save"></i> Simpan</button>
        </div>
    </div>
</section>
<!-- /.content -->
<?php
    }else{
        $_SESSION['hasil'] = false;
        echo "<meta http-equiv='refresh' content='0;url=?page=lokasiread'>";
    }
} else{
    $_SESSION['hasil'] = false;
    echo "<meta http-equiv='refresh' content='0;url=?page=lokasiread'>";  
}
?>


<?php include "partials/scripts.php" ?>
<?php include "partials/scriptsdatatables.php" ?>
<script>
    $(function() {
        $('#mytable').DataTable()
    });
</script>