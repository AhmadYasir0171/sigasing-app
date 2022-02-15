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
                <h1 class="m-0">Penggajian</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <a href="?page=home">Home</a>
                    </li>
                    <li class="breadcrumb-item active">Penggajian</li>
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
            <h3 class="card-title">Data Penggajian</h3>
            <a href="export/penggajianrekap-pdf.php" class="btn btn-success btn-sm float-right">
            <i class="fa fa-file"></i> Export PDF</a>
        </div>
        <div class="card-body">
            <table id="mytable" class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tahun</th>
                        <th>Gaji Pokok</th>
                        <th>Tunjangan</th>
                        <th>Uang Makan</th>
                        <th>Total</th>
                        <th>Opsi</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                    <th>No</th>
                        <th>Tahun</th>
                        <th>Gaji Pokok</th>
                        <th>Tunjangan</th>
                        <th>Uang Makan</th>
                        <th>Total</th>
                        <th>Opsi</th>
                    </tr>
                </tfoot>
                <tbody> 
                    <?php
                    $database = new Database();
                    $db = $database->getConnection();

                    $selectSql = "SELECT tahun, 
                            SUM(P.gapok) jumlah_gapok, 
                            SUM(P.tunjangan) jumlah_tunjangan, 
                            SUM(P.uang_makan) jumlah_uang_makan, SUM(P.gapok) + SUM(P.tunjangan) + SUM(P.uang_makan) total 
                            FROM penggajian P 
                            GROUP BY tahun;";
                    $stmt = $db->prepare($selectSql);
                    $stmt->execute();
                    $no = 1;
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    ?>
                    <tr>
                        <td><?php echo $no++ ?></td>
                        <td><?php echo $row['tahun'] ?></td>
                        <td style="text-align:right"><?php echo number_format($row['jumlah_gapok']) ?></td>
                        <td style="text-align:right"><?php echo number_format($row['jumlah_tunjangan']) ?></td>
                        <td style="text-align:right"><?php echo number_format($row['jumlah_uang_makan']) ?></td>
                        <td style="text-align:right"><?php echo number_format($row['total']) ?></td>
                        <td>
                            <a href="#" class="btn btn-primary btn-sm mr-1"><i class="fa fa-info"></i> Rincian
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
        $('#mytable').DataTable({
            "responsive": true,
            "lengthChange": false,
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#mytable_wrapper .col-md-6:eq(0)');
    });
</script>