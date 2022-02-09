<?php include_once "partials/cssdatatables.php" ?>
<div class="content-header">
    <div class="container-fluid">
    <?php 
    if (isset($_SESSION["hasil"])){
        if($_SESSION["hasil"]){
            $alert = "alert-success";
            $icon = "fa-check";
            $keterangan = "Berhasil";
        }else{
            $alert = "alert-danger";
            $icon = "fa-check";
            $keterangan = "Gagal";
        }
    ?>
        <div class="alert <?= $alert ?> alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true"></button>
            <h5><i class="icon fas <?= $icon ?>"></i><?= $keterangan ?></h5>
            <?= $_SESSION['pesan'] ?> 
        </div>
    
    <?php
    unset($_SESSION['hasil']);
    unset($_SESSION['pesan']);
    }
    ?>

        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Karyawan</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="?page=home">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Karyawan</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<div class="content">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Data Karyawan</h3>
            <a href="?page=karyawancreate" class="btn btn-success btn-sm float-right">
                <i class="fa fa-plus-circle"></i> Tambah Data</a>
        </div>
        <div class="card-body">
            <table id="mytable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Karyawan</th>
                        <th>Bagian</th>
                        <th>Jabatan</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>NIK</th>
                        <th>Nama Karyawan</th>
                        <th>Bagian</th>
                        <th>Jabatan</th>
                        <th>Opsi</th>
                    </tr>
                </tfoot>
                <tbody> 
                    <?php
                    $database = new Database();
                    $db = $database->getConnection();

                    // $selectSql = "SELECT * FROM lokasi";

                    $selectSql = "SELECT K.*,
                    (
                        SELECT J.nama_jabatan FROM jabatan_karyawan JK 
                        INNER JOIN jabatan J ON JK.jabatan_id = J.id
                        WHERE JK.karyawan_id = K.id ORDER BY JK.tanggal_mulai DESC
                        LIMIT 1
                        ) jabatan_terkini,
                        (
                        SELECT B.nama_bagian FROM bagian_karyawan BK
                        INNER JOIN bagian B ON BK.bagian_id = B.id
                        WHERE BK.karyawan_id = K.id ORDER BY BK.tanggal_mulai DESC 
                        LIMIT 1 
                        ) bagian_terkini 
                        FROM karyawan K";
                    $stmt = $db->prepare($selectSql);
                    $stmt->execute();
                    $no = 1;
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <!-- <td><?php echo $no++ ?></td> -->
                        <td><?php echo $row['nik'] ?></td>
                        <td><?php echo $row['nama_lengkap'] ?></td>
                        <td><?php echo $row['bagian_terkini'] ?></td>
                        <td><?php echo $row['jabatan_terkini'] ?></td>

                        <td>
                            <a href="?page=lokasiupdate&id=<?php echo $row['id'] ?>" class="btn btn-primary btn-sm mr-1"><i class="fa fa-edit"></i> Ubah
                            </a>
                            <a href="?page=lokasidelete&id=<?php echo $row['id'] ?>" class="btn btn-danger btn-sm" onClick="javascript: return confirm('Konfirmasi data akan dihapus?');"><i class="fa fa-trash"></i> Hapus
                            </a>
                        </td>
                    </tr>
                <?php
                    }
                ?>
                    
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- /.content -->

<?php include "partials/scripts.php" ?>
<?php include "partials/scriptsdatatables.php" ?>
<script>
    $(function() {
        $('#mytable').DataTable()
    });
</script>