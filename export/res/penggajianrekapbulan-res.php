<?php
if (isset($_GET['tahun']) && isset($_GET['bulan'])) {
    include "../database/database.php";
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
    <style type="text/css">
        table {
            font-family: Arial, Helvetica, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        th {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        td.angka {
            text-align: right;
        }
    </style>
    <span style="font-size: 20px; font-weight:bold">Detail Data Penggajian Tahun <?php echo $tahun ?> Bulan <?php echo $bulan ?><br></span>
    <br>
    <br>
    <table>
        <colgroup>
            <col style="width: 5%;" class="angka">
            <col style="width: 9%;" class="angka">
            <col style="width: 22%;" class="angka">
            <col style="width: 16%;" class="angka">
            <col style="width: 16%;" class="angka">
            <col style="width: 16%;" class="angka">
            <col style="width: 16%;" class="angka">
        </colgroup>
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
        $total_jumlah_gapok = 0;
        $total_jumlah_tunjangan = 0;
        $total_jumlah_uang_makan = 0;
        $total_total = 0;
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $total_jumlah_gapok += $row['gapok'];
            $total_jumlah_tunjangan += $row['tunjangan'];
            $total_jumlah_uang_makan += $row['uang_makan'];
            $total_total += $row['total'];
        ?>
            <tr>
                <td><?php echo $no++ ?></td>
                <td><?php echo $row['nik'] ?></td>
                <td><?php echo $row['nama_lengkap'] ?></td>
                <td><?php echo number_format($row['gapok']) ?></td>
                <td><?php echo number_format($row['tunjangan']) ?></td>
                <td><?php echo number_format($row['uang_makan']) ?></td>
                <td><?php echo number_format($row['total']) ?></td>
            </tr>
        <?php
        }
        ?>
        <tr>
            <td colspan="3">Grand Total</td>
            <td><?php echo number_format($total_jumlah_gapok) ?></td>
            <td><?php echo number_format($total_jumlah_tunjangan) ?></td>
            <td><?php echo number_format($total_jumlah_uang_makan) ?></td>
            <td><?php echo number_format($total_total) ?></td>
        </tr>
    </table>
<?php
}
?>