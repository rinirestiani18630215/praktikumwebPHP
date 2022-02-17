<?php
if (isset($_GET['id'])) {

    $database = new Database();
    $db = $database->getConnection();

    $id = $_GET['id'];
    $findSql = "SELECT * FROM karyawan join pengguna on pengguna_id = pengguna.id WHERE karyawan.id = ?";
    $stmt = $db->prepare($findSql);
    $stmt->bindParam(1, $_GET['id']);
    $stmt->execute();
    $row = $stmt->fetch();
    if (isset($row['id'])) {
        if (isset($_POST['button_update'])) {

            $database = new Database();
            $db = $database->getConnection();

            $valdateSql = "SELECT * FROM karyawan WHERE nik = ? AND id != ?";
            $stmt = $db->prepare($valdateSql);
            $stmt->bindParam(1, $_POST['nik']);
            $stmt->bindParam(2, $_POST['id']);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
?>
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                    <h5><i class="icon fas fa-ban"></i> Gagal</h5>
                    NIK sama sudah ada
                </div>
                <?php
            } else {
                $valdateSql = "SELECT * FROM pengguna WHERE username = ? AND id != ?";
                $stmt = $db->prepare($valdateSql);
                $stmt->bindParam(1, $_POST['username']);
                $stmt->bindParam(2, $_POST['id']);
                $stmt->execute();
                if ($stmt->rowCount() > 0) {
                ?>
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                        <h5><i class="icon fas fa-ban"></i> Gagal</h5>
                        Username sama sudah ada
                    </div>
                    <?php
                } else {
                    if ($_POST['password'] != $_POST['password_ulangi']) {
                    ?>
                        <div class="alert alert-danger alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                            <h5><i class="icon fas fa-ban"></i> Gagal</h5>
                            Password tidak sama
                        </div>
        <?php
                    } else {
                        $md5Password = md5($_POST['password']);
                        // $id_pengguna = $_POST['pengguna_id'];

                        $updatePengguna = " UPDATE pengguna SET username = :username,
                                            password = :password,
                                            peran = :peran
                                            WHERE id = :id_pengguna";
                        $stmt = $db->prepare($updatePengguna);
                        $stmt->bindParam(':username', $_POST['username']);
                        $stmt->bindParam(':password', $md5Password);
                        $stmt->bindParam(':peran', $_POST['peran']);
                        $stmt->bindParam(':id_pengguna', $_POST['pengguna_id']);
                        if ($stmt->execute()) {
                            $updateKaryawan = " UPDATE karyawan SET nik = :nik,
                                                nama_lengkap = :nama,
                                                handphone = :hp,
                                                email = :email,
                                                tanggal_masuk = :tgl
                                                WHERE id = :id";
                            $stmtKaryawan = $db->prepare($updateKaryawan);
                            $stmtKaryawan->bindParam(':nik', $_POST['nik']);
                            $stmtKaryawan->bindParam(':nama', $_POST['nama_lengkap']);
                            $stmtKaryawan->bindParam(':hp', $_POST['handphone']);
                            $stmtKaryawan->bindParam(':email', $_POST['email']);
                            $stmtKaryawan->bindParam(':tgl', $_POST['tanggal_masuk']);
                            $stmtKaryawan->bindParam(':id', $_POST['id']);

                            if ($stmtKaryawan->execute()) {
                                $_SESSION['hasil'] = true;
                                $_SESSION['pesan'] = "Berhasil ubah data";
                            } else {
                                $_SESSION['hasil'] = false;
                                $_SESSION['pesan'] = "Gagal ubah data";
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
        ?>
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb2">
                    <div class="col-sm-6">
                        <h1>Ubah Data Karyawan</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="?page=home">Home</a></li>
                            <li class="breadcrumb-item"><a href="?page=karyawanread">Karyawan</a></li>
                            <li class="breadcrumb-item">Ubah Data</li>
                        </ol>
                    </div>
                </div>
            </div>
        </section>
        <section class="content">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Ubah Karyawan</h3>
                </div>
                <div class="card-body">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="nik">Nomor Induk Karyawan</label>
                            <input type="hidden" name="id" class="form-control" value="<?php echo $row['id'] ?>">
                            <input type="hidden" name="pengguna_id" class="form-control" value="<?php echo $row['pengguna_id'] ?>">
                            <input type="text" class="form-control" name="nik" value="<?php echo $row['nik'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap</label>
                            <input type="text" class="form-control" name="nama_lengkap" value="<?php echo $row['nama_lengkap'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="handphone">Handphone</label>
                            <input type="text" class="form-control" name="handphone" value="<?php echo $row['handphone'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" name="email" value="<?php echo $row['email'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="tanggal_masuk">Tanggal Masuk</label>
                            <input type="date" class="form-control" name="tanggal_masuk" value="<?php echo $row['tanggal_masuk'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="username">Username</label>
                            <input type="text" class="form-control" name="username" value="<?php echo $row['username'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" name="password" value="<?php echo $row['password'] ?>">
                            <!-- <input type="password" class="form-control" name="password"> -->
                        </div>
                        <div class="form-group">
                            <label for="password_ulangi">Password (Ulangi)</label>
                            <input type="password" class="form-control" name="password_ulangi" value="<?php echo $row['password'] ?>">
                        </div>
                        <div class="form-group">
                            <label for="peran">Peran</label>
                            <select name="peran" class="form-control">
                                <option value="" disabled>-- Pilih Peran --</option>
                                <option value="ADMIN" <?= $row['peran'] == "ADMIN" ? "selected" : null ?>>ADMIN</option>
                                <option value="USER" <?= $row['peran'] == "USER" ? "selected" : null ?>>USER</option>
                            </select>
                        </div>
                        <a href="?page=karyawanread" class="btn btn-danger btn-sm float-right">
                            <i class="fa fa-times"></i> Batal
                        </a>
                        <button type="submit" name="button_update" class="btn btn-success btn-sm float-right">
                            <i class="fa fa-save"></i> Simpan
                        </button>
                    </form>
                </div>
            </div>
        </section>
<?php
    } else {
        echo "<meta http-equiv='refresh' content='0;url=?page=karyawanread'>";
    }
} else {
    echo "<meta http-equiv='refresh' content='0;url=?page=karyawanread'>";
}
?>
<?php include_once "partials/scripts.php" ?>