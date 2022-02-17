<?php
if (isset($_GET['id'])) {

    $database = new Database();
    $db = $database->getConnection();

    $id = $_GET['id'];
    $findSql = "SELECT * FROM karyawan WHERE id = ?";
    $stmt = $db->prepare($findSql);
    $stmt->bindParam(1, $_GET['id']);
    $stmt->execute();
    $row = $stmt->fetch();
    if (isset($row['id'])) {

        if (isset($_POST['button_update'])) {

            $updateSql = "INSERT INTO jabatan_karyawan SET karyawan_id = ?, jabatan_id = ?, tanggal_mulai = ?";
            $stmt = $db->prepare($updateSql);
            $stmt->bindParam(1, $_POST['karyawan_id']);
            $stmt->bindParam(2, $_POST['jabatan_id']);
            $stmt->bindParam(3, $_POST['tanggal_mulai']);
            if ($stmt->execute()) {
                $_SESSION['hasil'] = true;
            } else {
                $_SESSION['hasil'] = false;
            }
            echo "<meta http-equiv='refresh' content='1;url=?page=karyawanjabatan&id=" . $_POST['karyawan_id'] . "'>";
        }

        if (isset($_POST['button_delete'])) {

            $updateSql = "DELETE FROM jabatan_karyawan WHERE id = ?";
            $stmt = $db->prepare($updateSql);
            $stmt->bindParam(1, $_POST['jk_id']);
            if ($stmt->execute()) {
                $_SESSION['hasil'] = true;
            } else {
                $_SESSION['hasil'] = false;
            }
            // echo "<meta http-equiv='refresh' content='1;url=?page=karyawanjabatan&id=" . $_POST['karyawan_id'] . "'>";
        }



?>
        <section class="content-header">
            <div class="container-fluid">
                <?php
                if (isset($_SESSION["hasil"])) {
                    if ($_SESSION["hasil"]) {
                ?>
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <h5><i class="icon fas fa-check"></i> Berhasil</h5>
                        </div>
                    <?php
                    } else {
                    ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <h5><i class="icon fas fa-ban"></i> Gagal</h5>
                        </div>
                <?php
                    }
                    unset($_SESSION['hasil']);
                }
                ?>
                <div class="row mb2">
                    <div class="col-sm-6">
                        <h1>Karyawan</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
                            <li class="breadcrumb-item"><a href="?page=karyawanread">Karyawan</a></li>
                            <li class="breadcrumb-item active">Riwayat Jabatan</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Jabatan</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="nik">Nomor Induk Karyawan</label>
                                <input type="text" class="form-control" name="nik" value="<?php echo $row['nik'] ?>" disabled>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="handphone">Handphone</label>
                                <input type="text" class="form-control" name="handphone" value="<?php echo $row['handphone'] ?>" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="nama_lengkap">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="<?php echo $row['nama_lengkap'] ?>" disabled>
                    </div>
                    <form action="" method="POST">
                        <input type="hidden" name="karyawan_id" value="<?php echo $id ?>">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="jabatan_id">Jabatan</label>
                                    <select name="jabatan_id" class="form-control">
                                        <option value="">-- Pilih Jabatan --</option>
                                        <?php

                                        $selectSql = "SELECT * FROM jabatan";
                                        $stmt_jabatan = $db->prepare($selectSql);
                                        $stmt_jabatan->execute();

                                        while ($row_jabatan = $stmt_jabatan->fetch(PDO::FETCH_ASSOC)) {
                                            echo "<option value=\"" . $row_jabatan["id"] . "\">" . $row_jabatan["nama_jabatan"] . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="tanggal_mulai">Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" name="button_update" class="btn btn-success btn-block float-right mb-3">
                                <i class="fa fa-save"></i> Simpan</button>
                        </div>
                    </form>
                    <table id="mytable" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Jabatan</th>
                                <th>Tanggal Mulai</th>
                                <th>Opsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $database = new Database();
                            $db = $database->getConnection();

                            $selectSql = "SELECT JK.*, J.nama_jabatan FROM jabatan_karyawan JK
                                            LEFT JOIN jabatan J ON JK.jabatan_id = J.id
                                            WHERE JK.karyawan_id = ?
                                            ORDER BY tanggal_mulai DESC";
                            $stmt = $db->prepare($selectSql);
                            $stmt->bindParam(1, $id);
                            $stmt->execute();

                            $no = 1;
                            while ($rowjabatan = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            ?>
                                <tr>
                                    <td><?php echo $no++ ?></td>
                                    <td><?php echo $rowjabatan['nama_jabatan'] ?></td>
                                    <td><?php echo $rowjabatan['tanggal_mulai'] ?></td>
                                    <td>
                                        <form action="" method="POST">
                                            <input type="hidden" name="jk_id" value="<?php echo $rowjabatan['id'] ?>">
                                            <input type="hidden" name="karyawan_id" <?php echo $id ?>>
                                            <button type="submit" name="button_delete" class="btn btn-danger btn-sm mr-1" onclick="javascript:return confirm('Konfirmasi data akan dihapus?');">
                                                <i class="fa fa-trash"></i> Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
<?php
    } else {
        echo "<meta htttp-equiv='refresh' content='0;url=?page=karyawanread'>";
    }
} else {
    echo "<meta htttp-equiv='refresh' content='0;url=?page=karyawanread'>";
}
?>
<?php include_once "partials/scripts.php" ?>