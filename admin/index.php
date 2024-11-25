<?php
$title = "DashBoard | Elastomix";
require "../BackEnd/function.php";
require "../header.php";
require "../BackEnd/check_role.php"; // Pastikan file ini dipanggil
?>

<main>
    <div class="container-fluid">

        <h1 class="mt-4">List Data Item</h1>

        <!-- Chart Item  semua form-->
        <?php
        $total_barang_masuk = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah_masuk) AS total FROM barang_masuk"))['total'] ?? 0;
        $total_barang_keluar = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah_keluar) AS total FROM barang_keluar WHERE status_approve = 'approved'"))['total'] ?? 0;
        $total_stok = mysqli_fetch_assoc(mysqli_query($conn, "SELECT SUM(jumlah_masuk - jumlah_keluar) AS total FROM 
    (SELECT 
        COALESCE((SELECT SUM(jumlah_masuk) FROM barang_masuk WHERE barang_masuk.kode_barang = master_barang.kode_barang), 0) AS jumlah_masuk,
        COALESCE((SELECT SUM(jumlah_keluar) FROM barang_keluar WHERE barang_keluar.kode_barang = master_barang.kode_barang AND barang_keluar.status_approve = 'approved'), 0) AS jumlah_keluar
    FROM master_barang) AS subquery"))['total'] ?? 0;
        $total_jenis_barang = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM master_barang"))['total'] ?? 0;
        ?>
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6">
                <div class="card bg-primary text-white mb-4" style="border-radius: 20px;">
                    <div class="card-body">
                        <h5>Total Barang Masuk</h5>
                        <h2><?= $total_barang_masuk; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-warning text-white mb-4" style="border-radius: 20px;">
                    <div class="card-body">
                        <h5>Total Barang Keluar</h5>
                        <h2><?= $total_barang_keluar; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-success text-white mb-4" style="border-radius: 20px;">
                    <div class="card-body">
                        <h5>Total Stok</h5>
                        <h2><?= $total_stok; ?></h2>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6">
                <div class="card bg-info text-white mb-4" style="border-radius: 20px;">
                    <div class="card-body">
                        <h5>Jenis Barang</h5>
                        <h2><?= $total_jenis_barang; ?></h2>
                    </div>
                </div>
            </div>
        </div>
        <!--  Chart Item  semua form  END-->

        <!-- Tabel Dashboard Start -->
        <div class="card mb-4">
            <div class="card-header ">
                <i class="fas fa-table me-1"></i>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped table-bordered " id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>NO</th>
                                <th>KODE BARANG</th>
                                <th>JENIS BARANG</th>
                                <th>NAMA BARANG</th>
                                <th>IN ITEM</th>
                                <th>OUT ITEM</th>
                                <th>STOK</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $ambilsemuastock = mysqli_query($conn, "
                                SELECT 
                                    master_barang.kode_barang, 
                                    master_barang.nama_barang, 
                                    master_barang.jenis_barang, 
                                    COALESCE((SELECT SUM(jumlah_masuk) FROM barang_masuk WHERE barang_masuk.kode_barang = master_barang.kode_barang), 0) AS jumlah_masuk, 
                                    COALESCE((SELECT SUM(jumlah_keluar) FROM barang_keluar WHERE barang_keluar.kode_barang = master_barang.kode_barang AND barang_keluar.status_approve = 'approved'), 0) AS jumlah_keluar, 
                                    (COALESCE((SELECT SUM(jumlah_masuk) FROM barang_masuk WHERE barang_masuk.kode_barang = master_barang.kode_barang), 0) - 
                                    COALESCE((SELECT SUM(jumlah_keluar) FROM barang_keluar WHERE barang_keluar.kode_barang = master_barang.kode_barang AND barang_keluar.status_approve = 'approved'), 0)) AS stok 
                                FROM master_barang
                            ");
                            $i = 1;
                            while ($data = mysqli_fetch_array($ambilsemuastock)) {
                                $kbarang = $data['kode_barang'];
                                $namabarang = $data['nama_barang'];
                                $jenisBarang = $data['jenis_barang'];
                                $jumlah_masuk = $data['jumlah_masuk'];
                                $jumlah_keluar = $data['jumlah_keluar'];
                                $stok = $data['stok'];
                            ?>
                                <tr>
                                    <td><?= $i++; ?></td>
                                    <td><?= $kbarang; ?></td>
                                    <td><?= $jenisBarang; ?></td>
                                    <td><?= $namabarang; ?></td>
                                    <td><?= $jumlah_masuk; ?></td>
                                    <td><?= $jumlah_keluar; ?></td>
                                    <td><?= $stok; ?></td>
                                </tr>
                            <?php
                            };
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Tabel Dashboard END -->

    <br>
    <br>
    <br>

    <!-- VISUALISASI CHART  -->
    <?php
    // Ambil tahun yang dipilih dari URL (GET parameter), atau jika tidak ada pilih tahun saat ini
    $tahun_sekarang = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

    // Query untuk mengambil barang masuk per bulan berdasarkan tahun yang dipilih
    $barang_masuk_per_bulan = [];
    for ($bulan = 1; $bulan <= 12; $bulan++) {
        $query = mysqli_query($conn, "
        SELECT SUM(jumlah_masuk) AS total 
        FROM barang_masuk 
        WHERE MONTH(tanggal_masuk) = $bulan AND YEAR(tanggal_masuk) = '$tahun_sekarang'
    ");
        $result = mysqli_fetch_assoc($query);
        $barang_masuk_per_bulan[] = $result['total'] ?? 0;
    }

    // Menampilkan label bulan
    $labels_bulan = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
    ?>

    <!-- Dropdown untuk memilih tahun -->
    <div class="ms-5 me-5">
        <form method="GET" action="">
            <label for="tahun">Pilih Tahun:</label>
            <select name="tahun" id="tahun" class="form-select">
                <?php
                // Menampilkan pilihan tahun dari database
                $queryTahun = mysqli_query($conn, "SELECT DISTINCT YEAR(tanggal_masuk) AS tahun FROM barang_masuk ORDER BY tahun DESC");
                while ($tahun = mysqli_fetch_assoc($queryTahun)) {
                    // Jika tahun saat ini dipilih, beri atribut selected
                    echo "<option value='" . $tahun['tahun'] . "' " . ($tahun['tahun'] == $tahun_sekarang ? 'selected' : '') . ">" . $tahun['tahun'] . "</option>";
                }
                ?>
            </select>

            <button type="submit" class="btn btn-primary mt-2 ">Tampilkan</button>
        </form>

        <!-- Chart untuk barang masuk -->
        <canvas id="chartBarangMasuk" width="100%" height="40"></canvas>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            var ctx = document.getElementById('chartBarangMasuk').getContext('2d');
            var chartBarangMasuk = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($labels_bulan); ?>, // Label bulan
                    datasets: [{
                        label: 'Barang Masuk per Bulan Tahun <?= $tahun_sekarang; ?>',
                        data: <?= json_encode($barang_masuk_per_bulan); ?>, // Data barang masuk
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: true
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Barang Masuk per Bulan Tahun <?= $tahun_sekarang; ?>'
                        }
                    }
                }
            });
        </script>
    </div>
    <br>
    <br>
    <br>
    <br>
    <!-- VISUALISASI CHART  Selesai-->

    <!-- chart barang keluar -->
    <?php
    // Ambil tahun yang dipilih dari URL (GET parameter), atau jika tidak ada pilih tahun saat ini
    $tahun_sekarang = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

    // Query untuk mengambil barang keluar per bulan berdasarkan tahun yang dipilih
    $barang_keluar_per_bulan = [];
    for ($bulan = 1; $bulan <= 12; $bulan++) {
        $query = mysqli_query($conn, "
        SELECT SUM(jumlah_keluar) AS total 
        FROM barang_keluar 
        WHERE MONTH(tanggal_keluar) = $bulan AND YEAR(tanggal_keluar) = '$tahun_sekarang' AND status_approve = 'approved'
    ");
        $result = mysqli_fetch_assoc($query);
        $barang_keluar_per_bulan[] = $result['total'] ?? 0;
    }

    // Menampilkan label bulan
    $labels_bulan = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];
    ?>


    <!-- Chart untuk barang keluar -->
    <!-- Dropdown untuk memilih tahun -->
    <div class="ms-5 me-5">
        <form method="GET" action="">
            <label for="tahun">Pilih Tahun:</label>
            <select name="tahun" id="tahun" class="form-select">
                <?php
                // Menampilkan pilihan tahun dari database
                $queryTahun = mysqli_query($conn, "SELECT DISTINCT YEAR(tanggal_keluar) AS tahun FROM barang_keluar ORDER BY tahun DESC");
                while ($tahun = mysqli_fetch_assoc($queryTahun)) {
                    // Jika tahun saat ini dipilih, beri atribut selected
                    echo "<option value='" . $tahun['tahun'] . "' " . ($tahun['tahun'] == $tahun_sekarang ? 'selected' : '') . ">" . $tahun['tahun'] . "</option>";
                }
                ?>
            </select>
            <button type="submit" class="btn btn-primary">Tampilkan</button>
        </form>
        <canvas id="chartBarangKeluar" width="100%" height="40"></canvas>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            var ctx = document.getElementById('chartBarangKeluar').getContext('2d');
            var chartBarangKeluar = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: <?= json_encode($labels_bulan); ?>, // Label bulan
                    datasets: [{
                        label: 'Barang Keluar per Bulan Tahun <?= $tahun_sekarang; ?>',
                        data: <?= json_encode($barang_keluar_per_bulan); ?>, // Data barang keluar
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    },
                    plugins: {
                        legend: {
                            display: true
                        },
                        title: {
                            display: true,
                            text: 'Jumlah Barang Keluar per Bulan Tahun <?= $tahun_sekarang; ?>'
                        }
                    }
                }
            });
        </script>
    </div>
    <!-- chart barang keluar selesai -->

</main>
<?php
require "../footer.php"
?>