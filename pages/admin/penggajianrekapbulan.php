<?php include_once "partials/cssdatatables.php" ?>

<?php
if (isset($_GET['tahun']) && isset($_GET['bulan'])) {

    $database = new Database();
    $db = $database->getConnection();

    $tahun = $_GET['tahun'];
    $bulan = $_GET['bulan'];
    $findSql = "SELECT * FROM penggajian WHERE tahun = ? and bulan = ?";
    $stmt = $db->prepare($findSql);
    $stmt->bindParam(1, $_GET['tahun']);
    $stmt->bindParam(2, $_GET['bulan']);
    $stmt->execute();
    $row = $stmt->fetch();
?>
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <?php
            if (isset($_SESSION["hasil"])) {
                if ($_SESSION["hasil"]) {
            ?>
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                        <h5><i class="icon fas fa-check"></i> Berhasil</h5>
                        <?php echo $_SESSION["pesan"] ?>
                    </div>
                <?php
                } else {
                ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                        <h5><i class="icon fas fa-ban"></i> Gagal</h5>
                        <?php echo $_SESSION["pesan"] ?>
                    </div>
            <?php
                }
                unset($_SESSION['hasil']);
                unset($_SESSION['pesan']);
            }
            ?>
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Rekapitulasi Penggajian Sebulan</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">
                            <a href="?page=home">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="?page=penggajianrekap">Rekap Gaji</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="?page=penggajianrekaptahun&tahun=<?php echo $tahun ?>"><?php echo $tahun ?></a>
                        </li>
                        <li class="breadcrumb-item"><?php echo $bulan ?></li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <div class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Data Rekap Gaji Bulan <?php echo $tahun ?>/<?php echo $bulan ?></h3>
                <a href="export/penggajianrekapbulan-pdf.php?tahun=<?php echo $tahun ?>&bulan=<?php echo $bulan ?>" target="blank" class="btn btn-success btn-sm float-right">
                    <i class="fa fa-file-pdf"></i> Report PDF</a>
            </div>
            <div class="card-body">


                <table id="mytable" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama Lengkap</th>
                            <th>Gaji Pokok</th>
                            <th>Tunjangan</th>
                            <th>Uang Makan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>No</th>
                            <th>NIK</th>
                            <th>Nama Lengkap</th>
                            <th>Gaji Pokok</th>
                            <th>Tunjangan</th>
                            <th>Uang Makan</th>
                            <th>Total</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php
                        $database = new Database();
                        $db = $database->getConnection();
                        $selectSql = "SELECT nik,nama_lengkap,gapok,tunjangan,uang_makan,tahun,bulan,gapok+tunjangan+uang_makan total
                                            FROM penggajian
                                            INNER JOIN karyawan ON karyawan_id = karyawan.id 
                                            WHERE tahun = $tahun AND bulan = $bulan";
                        $stmt = $db->prepare($selectSql);
                        $stmt->execute();
                        $no = 1;
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                        ?>
                            <tr>
                                <td><?php echo $no++ ?></td>
                                <td><?php echo $row['nik'] ?></td>
                                <td><?php echo $row['nama_lengkap'] ?></td>
                                <td style="text-align:right"><?php echo number_format($row['gapok']) ?></td>
                                <td style="text-align:right"><?php echo number_format($row['tunjangan']) ?></td>
                                <td style="text-align:right"><?php echo number_format($row['uang_makan']) ?></td>
                                <td style="text-align:right"><?php echo number_format($row['total']) ?></td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php
} else {
    echo "<meta htttp-equiv='refresh' content='0;url=?page=penggajianrekap'>";
}
?>
<?php include_once "partials/scripts.php" ?>
<?php include_once "partials/scriptsdatatables.php" ?>
<script>
    $(function() {
        $('#mytable').DataTable()
    });
</script>