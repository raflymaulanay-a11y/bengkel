<!DOCTYPE html>

<html>

<head>

    <meta charset="UTF-8">

    <title>
        Laporan Riwayat Stok
    </title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {

            font-family: Arial, Helvetica, sans-serif;

            margin: 30px;

            color: #000;

            font-size: 13px;

        }


        .header {

            text-align: center;

            margin-bottom: 20px;

        }


        .header h2 {

            margin: 0;

            font-size: 22px;

        }


        .header h3 {

            margin: 5px 0;

            font-size: 18px;

        }


        .periode {

            margin-top: 10px;

            font-size: 13px;

        }


        table {

            width: 100%;

            border-collapse: collapse;

            margin-top: 20px;

        }


        table th {

            background-color: #eeeeee;

            text-align: center;

            font-weight: bold;

        }


        table th,
        table td {

            border: 1px solid #000;

            padding: 8px;

        }


        .center {

            text-align: center;

        }


        .masuk {

            font-weight: bold;

        }


        .keluar {

            font-weight: bold;

        }


        .footer {

            margin-top: 20px;

            text-align: right;

            font-weight: bold;

        }


        @media print {

            body {

                margin: 15mm;

            }


            .no-print {

                display: none !important;

            }

        }


    </style>

</head>


<body>


    <!-- ================================================= -->
    <!-- HEADER -->
    <!-- ================================================= -->

    <div class="header">

        <h2>
            AVS PERKASA
        </h2>

        <h3>
            LAPORAN RIWAYAT STOK
        </h3>


        <?php if ($tanggal): ?>

            <div class="periode">

                Tanggal:
                <strong>
                    <?= date(
                        'd/m/Y',
                        strtotime($tanggal)
                    ); ?>
                </strong>

            </div>

        <?php else: ?>

            <div class="periode">

                Semua Riwayat Stok

            </div>

        <?php endif; ?>

    </div>



    <!-- ================================================= -->
    <!-- TOMBOL -->
    <!-- ================================================= -->

    <div
        class="no-print"
        style="margin-bottom:20px;"
    >

        <button
            onclick="window.print();"
        >

            🖨 Cetak

        </button>


        <button
            onclick="window.close();"
        >

            Tutup

        </button>

    </div>



    <!-- ================================================= -->
    <!-- TABEL -->
    <!-- ================================================= -->

    <table>

        <thead>

            <tr>

                <th width="50">
                    No
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

            <?php if (empty($data)): ?>

                <tr>

                    <td
                        colspan="7"
                        class="center"
                    >

                        Tidak ada data riwayat stok.

                    </td>

                </tr>

            <?php else: ?>


                <?php foreach ($data as $key => $row): ?>

                    <tr>

                        <td class="center">

                            <?= $key + 1; ?>

                        </td>


                        <td>

                            <?= date(
                                'd/m/Y',
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


                        <td class="center">

                            <?php if (
                                $row->jenis == "masuk"
                            ): ?>

                                <span class="masuk">

                                    MASUK

                                </span>

                            <?php else: ?>

                                <span class="keluar">

                                    KELUAR

                                </span>

                            <?php endif; ?>

                        </td>


                        <td class="center">

                            <?= $row->jumlah; ?>

                        </td>


                        <td>

                            <?= $row->keterangan
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



    <!-- ================================================= -->
    <!-- JUMLAH DATA -->
    <!-- ================================================= -->

    <div class="footer">

        Jumlah Transaksi:
        <?= count($data); ?>

    </div>



    <!-- ================================================= -->
    <!-- AUTO PRINT -->
    <!-- ================================================= -->

    <script>

        window.onload = function () {

            setTimeout(
                function () {

                    window.print();

                },
                500
            );

        };

    </script>


</body>

</html>