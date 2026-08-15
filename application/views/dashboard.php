<div class="row">

    <!-- ===================================================== -->
    <!-- TOTAL KATEGORI -->
    <!-- ===================================================== -->

    <div class="col-md-3">

        <div class="card">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <h6 class="text-muted">
                            Total Kategori
                        </h6>

                        <h2>
                            <?= $totalKategori; ?>
                        </h2>

                    </div>

                    <div>

                        <i
                            class="fa fa-tags"
                            style="
                                font-size:40px;
                                color:#3498db;
                            "
                        ></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- TOTAL PRODUK -->
    <!-- ===================================================== -->

    <div class="col-md-3">

        <div class="card">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <h6 class="text-muted">
                            Total Produk
                        </h6>

                        <h2>
                            <?= $totalProduk; ?>
                        </h2>

                    </div>

                    <div>

                        <i
                            class="fa fa-cubes"
                            style="
                                font-size:40px;
                                color:#27ae60;
                            "
                        ></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- BARANG MASUK -->
    <!-- ===================================================== -->

    <div class="col-md-3">

        <div class="card">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <h6 class="text-muted">
                            Barang Masuk Hari Ini
                        </h6>

                        <h2>
                            <?= $barangMasukHariIni; ?>
                        </h2>

                    </div>

                    <div>

                        <i
                            class="fa fa-sign-in"
                            style="
                                font-size:40px;
                                color:#2ecc71;
                            "
                        ></i>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- BARANG KELUAR -->
    <!-- ===================================================== -->

    <div class="col-md-3">

        <div class="card">

            <div class="card-body">

                <div class="d-flex justify-content-between">

                    <div>

                        <h6 class="text-muted">
                            Barang Keluar Hari Ini
                        </h6>

                        <h2>
                            <?= $barangKeluarHariIni; ?>
                        </h2>

                    </div>

                    <div>

                        <i
                            class="fa fa-sign-out"
                            style="
                                font-size:40px;
                                color:#e74c3c;
                            "
                        ></i>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- ========================================================= -->
<!-- TOTAL STOK + STOK MENIPIS -->
<!-- ========================================================= -->

<div class="row">


    <!-- ===================================================== -->
    <!-- TOTAL STOK -->
    <!-- ===================================================== -->

    <div class="col-md-5">

        <div class="card">

            <div class="card-header">

                <strong>
                    <i class="fa fa-archive"></i>
                    Total Stok Saat Ini
                </strong>

            </div>

            <div class="card-body text-center">

                <i
                    class="fa fa-archive"
                    style="
                        font-size:55px;
                        color:#3498db;
                    "
                ></i>

                <h1 style="margin-top:15px;">

                    <?= $totalStok; ?>

                </h1>

                <p class="text-muted">

                    Unit Sparepart

                </p>

            </div>

        </div>

    </div>


    <!-- ===================================================== -->
    <!-- STOK MENIPIS -->
    <!-- ===================================================== -->

    <div class="col-md-7">

        <div class="card">

            <div class="card-header">

                <strong>

                    <i class="fa fa-warning"></i>

                    Stok Menipis

                </strong>

                <span class="text-muted">
                    (≤ 5 unit)
                </span>

            </div>


            <div class="card-body p-0">

                <div class="table-responsive">

                    <table
                        class="table table-bordered table-striped mb-0"
                    >

                        <thead>

                            <tr>

                                <th>
                                    #
                                </th>

                                <th>
                                    Kategori
                                </th>

                                <th>
                                    Produk / Merek
                                </th>

                                <th>
                                    Stok
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            <?php if (empty($stokMenipis)): ?>

                                <tr>

                                    <td
                                        colspan="4"
                                        class="text-center"
                                    >

                                        <i class="fa fa-check-circle"></i>

                                        Semua stok masih aman.

                                    </td>

                                </tr>

                            <?php else: ?>


                                <?php foreach (
                                    $stokMenipis
                                    as $key => $row
                                ): ?>

                                    <tr>

                                        <td>
                                            <?= $key + 1; ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars(
                                                $row->nama_kategori
                                            ); ?>
                                        </td>

                                        <td>
                                            <?= htmlspecialchars(
                                                $row->nama_produk
                                            ); ?>
                                        </td>

                                        <td>

                                            <span class="badge badge-danger">

                                                <?= $row->stok; ?>

                                            </span>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>


                            <?php endif; ?>


                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- ========================================================= -->
<!-- GRAFIK -->
<!-- ========================================================= -->

<div class="row">

    <div class="col-md-12">

        <div class="card">

            <div class="card-header">

                <strong>

                    <i class="fa fa-bar-chart"></i>

                    Grafik Barang Masuk dan Keluar

                </strong>

            </div>


            <div class="card-body">

                <canvas
                    id="stockChart"
                    height="100"
                ></canvas>

            </div>

        </div>

    </div>

</div>


<!-- ========================================================= -->
<!-- TRANSAKSI TERBARU -->
<!-- ========================================================= -->

<div class="row">

    <div class="col-md-12">

        <div class="card">

            <div class="card-header">

                <strong>

                    <i class="fa fa-history"></i>

                    Transaksi Terbaru

                </strong>

                <a
                    href="<?= base_url('stock_history'); ?>"
                    class="float-right"
                >

                    Lihat Semua

                </a>

            </div>


            <div class="card-body">

                <div class="table-responsive">

                    <table
                        class="table table-bordered table-striped"
                    >

                        <thead>

                            <tr>

                                <th width="50">
                                    #
                                </th>

                                <th>
                                    Tanggal
                                </th>

                                <th>
                                    Kategori
                                </th>

                                <th>
                                    Produk / Merek
                                </th>

                                <th>
                                    Jenis
                                </th>

                                <th>
                                    Jumlah
                                </th>

                                <th>
                                    Keterangan
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            <?php if (
                                empty($transaksiTerbaru)
                            ): ?>

                                <tr>

                                    <td
                                        colspan="7"
                                        class="text-center"
                                    >

                                        Belum ada transaksi.

                                    </td>

                                </tr>

                            <?php else: ?>


                                <?php foreach (
                                    $transaksiTerbaru
                                    as $key => $row
                                ): ?>

                                    <tr>

                                        <td>

                                            <?= $key + 1; ?>

                                        </td>


                                        <td>

                                            <?= date(
                                                "d/m/Y",
                                                strtotime(
                                                    $row->tanggal
                                                )
                                            ); ?>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                $row->nama_kategori
                                            ); ?>

                                        </td>


                                        <td>

                                            <?= htmlspecialchars(
                                                $row->nama_produk
                                            ); ?>

                                        </td>


                                        <td>

                                            <?php if (
                                                $row->jenis
                                                ==
                                                "masuk"
                                            ): ?>

                                                <span
                                                    class="badge badge-success"
                                                >

                                                    <i class="fa fa-sign-in"></i>

                                                    Masuk

                                                </span>

                                            <?php else: ?>

                                                <span
                                                    class="badge badge-danger"
                                                >

                                                    <i class="fa fa-sign-out"></i>

                                                    Keluar

                                                </span>

                                            <?php endif; ?>

                                        </td>


                                        <td>

                                            <?= $row->jumlah; ?>

                                        </td>


                                        <td>

                                            <?= !empty(
                                                $row->keterangan
                                            )
                                                ? htmlspecialchars(
                                                    $row->keterangan
                                                )
                                                : "-";
                                            ?>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>


                            <?php endif; ?>


                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- ========================================================= -->
<!-- CHART.JS -->
<!-- ========================================================= -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


<script>

$(document).ready(function() {

    var ctx =
        document
            .getElementById(
                "stockChart"
            )
            .getContext("2d");


    new Chart(ctx, {

        type: "bar",

        data: {

            labels:
                <?= json_encode(
                    $chartLabels
                ); ?>,

            datasets: [

                {

                    label:
                        "Barang Masuk",

                    data:
                        <?= json_encode(
                            $chartMasuk
                        ); ?>,

                    borderWidth: 1

                },

                {

                    label:
                        "Barang Keluar",

                    data:
                        <?= json_encode(
                            $chartKeluar
                        ); ?>,

                    borderWidth: 1

                }

            ]

        },


        options: {

            responsive: true,

            maintainAspectRatio: false,

            scales: {

                y: {

                    beginAtZero: true,

                    ticks: {

                        precision: 0

                    }

                }

            }

        }

    });

});

</script>