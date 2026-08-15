<style>
    /* =========================================
       FILTER TANGGAL
       ========================================= */

    #filterTanggal {
        height: 40px;
    }

    #btnFilter,
    #btnReset {
        height: 40px;
        margin-top: 0;
    }


    /* =========================================
       DATATABLE SEARCH
       ========================================= */

    #dataTable_wrapper .dataTables_filter {
        margin-bottom: 15px;
        text-align: right;
    }

    #dataTable_wrapper .dataTables_filter label {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 8px;
        margin-bottom: 0;
    }

    #dataTable_wrapper .dataTables_filter input {
        margin-left: 0 !important;
        width: 180px;
        height: 35px;
        padding: 6px 10px;
        border: 1px solid #ced4da;
        border-radius: 4px;
        outline: none;
    }

    #dataTable_wrapper .dataTables_filter input:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.15rem rgba(0, 123, 255, .15);
    }


    /* =========================================
       SHOW ENTRIES
       ========================================= */

    #dataTable_wrapper .dataTables_length {
        margin-bottom: 15px;
    }

    #dataTable_wrapper .dataTables_length label {
        margin-bottom: 0;
    }


    /* =========================================
       JARAK FILTER KE DATATABLE
       ========================================= */

    #dataTable_wrapper {
        width: 100%;
    }


    /* =========================================
       TABLE
       ========================================= */

    #dataTable {
        width: 100% !important;
    }


    /* =========================================
       RESPONSIVE
       ========================================= */

    @media (max-width: 768px) {

        #dataTable_wrapper .dataTables_filter {
            text-align: left;
            margin-top: 10px;
        }

        #dataTable_wrapper .dataTables_filter label {
            justify-content: flex-start;
        }

        #dataTable_wrapper .dataTables_filter input {
            width: 160px;
        }

    }
</style>
<div class="row">

    <div class="col-md-12">

        <div class="card">

            <!-- ================================================= -->
            <!-- HEADER -->
            <!-- ================================================= -->

            <div class="card-header">

                <h4>
                    <i class="fa fa-history"></i>
                    Riwayat Stok
                </h4>

            </div>


            <!-- ================================================= -->
            <!-- BODY -->
            <!-- ================================================= -->

            <div class="card-body">


                <!-- ================================================= -->
                <!-- FILTER TANGGAL -->
                <!-- ================================================= -->

                <div class="row mb-3">

                    <div class="col-md-4">

                        <label>
                            <strong>Filter Tanggal</strong>
                        </label>

                        <input
                            type="date"
                            id="filterTanggal"
                            class="form-control">

                    </div>


                    <div class="col-md-4">

                        <label>
                            &nbsp;
                        </label>

                        <div>

                            <button
                                type="button"
                                id="btnFilter"
                                class="btn btn-primary">

                                <i class="fa fa-search"></i>
                                Tampilkan

                            </button>


                            <button
                                type="button"
                                id="btnReset"
                                class="btn btn-secondary">

                                <i class="fa fa-refresh"></i>
                                Reset

                            </button>

                            <button
                                type="button"
                                id="btnCetak"
                                class="btn btn-success"
                                style="margin-left: 5px;">
                                <i class="fa fa-print"></i>
                                Cetak
                            </button>

                        </div>

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- INFORMASI FILTER -->
                <!-- ================================================= -->

                <div
                    id="infoFilter"
                    class="alert alert-info"
                    style="display:none;">

                    <i class="fa fa-info-circle"></i>

                    Menampilkan riwayat stok tanggal
                    <strong id="tanggalFilterText"></strong>

                </div>


                <!-- ================================================= -->
                <!-- TABLE -->
                <!-- ================================================= -->

                <div class="table-responsive">

                    <table
                        id="dataTable"
                        class="table table-bordered table-striped"
                        width="100%">

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
                                <!--
                                <th>
                                    User
                                </th>
-->

                            </tr>

                        </thead>


                        <tbody>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>



<script>
    $(document).ready(function() {


        /*
        |--------------------------------------------------------------------------
        | VARIABEL DATATABLE
        |--------------------------------------------------------------------------
        */

        var table = null;



        /*
        |--------------------------------------------------------------------------
        | LOAD DATA RIWAYAT
        |--------------------------------------------------------------------------
        */

        function loadData(tanggal = "") {


            /*
            |--------------------------------------------------------------------------
            | HAPUS DATATABLE LAMA
            |--------------------------------------------------------------------------
            */

            if ($.fn.DataTable.isDataTable("#dataTable")) {

                $("#dataTable")
                    .DataTable()
                    .destroy();

            }


            /*
            |--------------------------------------------------------------------------
            | KOSONGKAN TABLE
            |--------------------------------------------------------------------------
            */

            var tbody = $("#dataTable tbody");

            tbody.empty();


            /*
            |--------------------------------------------------------------------------
            | TAMPILKAN LOADING
            |--------------------------------------------------------------------------
            */

            tbody.append(

                '<tr>' +

                '<td colspan="8" class="text-center">' +

                '<i class="fa fa-spinner fa-spin"></i> ' +

                'Memuat data...' +

                '</td>' +

                '</tr>'

            );


            /*
            |--------------------------------------------------------------------------
            | REQUEST AJAX
            |--------------------------------------------------------------------------
            */

            $.ajax({

                url: "<?= base_url('stock_history/data'); ?>",

                type: "GET",

                dataType: "json",

                data: {

                    tanggal: tanggal

                },


                success: function(response) {


                    /*
                    |--------------------------------------------------------------------------
                    | KOSONGKAN TABLE
                    |--------------------------------------------------------------------------
                    */

                    tbody.empty();


                    /*
                    |--------------------------------------------------------------------------
                    | CEK STATUS
                    |--------------------------------------------------------------------------
                    */

                    if (response.status !== true) {

                        tbody.append(

                            '<tr>' +

                            '<td colspan="8" class="text-center text-danger">' +

                            'Gagal mengambil data.' +

                            '</td>' +

                            '</tr>'

                        );

                        return;

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | JIKA TIDAK ADA DATA
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !response.data ||
                        response.data.length === 0
                    ) {


                        var pesan =
                            tanggal !== "" ?
                            "Tidak ada riwayat stok pada tanggal tersebut." :
                            "Belum ada riwayat stok.";


                        tbody.append(

                            '<tr>' +

                            '<td colspan="8" class="text-center">' +

                            pesan +

                            '</td>' +

                            '</tr>'

                        );


                        /*
                        |--------------------------------------------------------------------------
                        | TETAP AKTIFKAN DATATABLE
                        |--------------------------------------------------------------------------
                        */

                        table = $("#dataTable").DataTable({

                            responsive: true,

                            ordering: true,

                            pageLength: 10,

                            language: {

                                search: "Cari:",

                                lengthMenu: "Tampilkan _MENU_ data",

                                info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",

                                infoEmpty: "Tidak ada data",

                                zeroRecords: "Data tidak ditemukan",

                                paginate: {

                                    first: "Pertama",

                                    last: "Terakhir",

                                    next: "›",

                                    previous: "‹"

                                }

                            }

                        });


                        return;

                    }



                    /*
                    |--------------------------------------------------------------------------
                    | TAMPILKAN DATA
                    |--------------------------------------------------------------------------
                    */

                    $.each(

                        response.data,

                        function(index, row) {


                            /*
                            |--------------------------------------------------------------------------
                            | JENIS TRANSAKSI
                            |--------------------------------------------------------------------------
                            */

                            var jenis =
                                row.jenis;


                            var badge;


                            if (
                                jenis === "masuk"
                            ) {


                                badge =

                                    '<span class="badge badge-success">' +

                                    '<i class="fa fa-sign-in"></i> ' +

                                    'Masuk' +

                                    '</span>';


                            } else {


                                badge =

                                    '<span class="badge badge-danger">' +

                                    '<i class="fa fa-sign-out"></i> ' +

                                    'Keluar' +

                                    '</span>';

                            }



                            /*
                            |--------------------------------------------------------------------------
                            | KETERANGAN
                            |--------------------------------------------------------------------------
                            */

                            var keterangan =

                                row.keterangan ?
                                row.keterangan :
                                "-";



                            /*
                            |--------------------------------------------------------------------------
                            | USER
                            |--------------------------------------------------------------------------
                            */

                            var username =

                                row.username ?
                                row.username :
                                "-";



                            /*
                            |--------------------------------------------------------------------------
                            | KATEGORI
                            |--------------------------------------------------------------------------
                            */

                            var kategori =

                                row.nama_kategori ?
                                row.nama_kategori :
                                "-";



                            /*
                            |--------------------------------------------------------------------------
                            | PRODUK
                            |--------------------------------------------------------------------------
                            */

                            var produk =

                                row.nama_produk ?
                                row.nama_produk :
                                "-";



                            /*
                            |--------------------------------------------------------------------------
                            | TAMBAHKAN KE TABLE
                            |--------------------------------------------------------------------------
                            */

                            tbody.append(

                                '<tr>' +

                                '<td>' +

                                (index + 1) +

                                '</td>' +


                                '<td>' +

                                formatTanggal(
                                    row.tanggal
                                ) +

                                '</td>' +


                                '<td>' +

                                kategori +

                                '</td>' +


                                '<td>' +

                                produk +

                                '</td>' +


                                '<td>' +

                                badge +

                                '</td>' +


                                '<td>' +

                                row.jumlah +

                                '</td>' +


                                '<td>' +

                                keterangan +

                                '</td>' +




                                '</tr>'

                            );

                        }

                    );



                    /*
                    |--------------------------------------------------------------------------
                    | DATATABLE
                    |--------------------------------------------------------------------------
                    */

                    table = $("#dataTable").DataTable({

                        responsive: true,

                        ordering: true,

                        pageLength: 10,

                        language: {

                            search: "Cari:",

                            lengthMenu: "Tampilkan _MENU_ data",

                            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",

                            infoEmpty: "Tidak ada data",

                            zeroRecords: "Data tidak ditemukan",

                            paginate: {

                                first: "Pertama",

                                last: "Terakhir",

                                next: "›",

                                previous: "‹"

                            }

                        }

                    });

                },


                /*
                |--------------------------------------------------------------------------
                | ERROR AJAX
                |--------------------------------------------------------------------------
                */

                error: function(xhr) {

                    console.log(
                        "ERROR RIWAYAT:",
                        xhr.responseText
                    );


                    tbody.empty();


                    tbody.append(

                        '<tr>' +

                        '<td colspan="8" class="text-center text-danger">' +

                        '<i class="fa fa-warning"></i> ' +

                        'Gagal mengambil data riwayat stok.' +

                        '</td>' +

                        '</tr>'

                    );

                }

            });

        }



        /*
        |--------------------------------------------------------------------------
        | TOMBOL FILTER
        |--------------------------------------------------------------------------
        */

        $("#btnFilter").on(
            "click",
            function() {


                var tanggal =
                    $("#filterTanggal").val();


                /*
                |--------------------------------------------------------------------------
                | VALIDASI
                |--------------------------------------------------------------------------
                */

                if (!tanggal) {


                    Swal.fire({

                        icon: "warning",

                        title: "Tanggal belum dipilih",

                        text: "Silakan pilih tanggal terlebih dahulu."

                    });


                    return;

                }


                /*
                |--------------------------------------------------------------------------
                | TAMPILKAN INFO FILTER
                |--------------------------------------------------------------------------
                */

                $("#infoFilter")
                    .show();


                $("#tanggalFilterText")
                    .text(
                        formatTanggal(tanggal)
                    );


                /*
                |--------------------------------------------------------------------------
                | LOAD DATA SESUAI TANGGAL
                |--------------------------------------------------------------------------
                */

                loadData(tanggal);

            }
        );



        /*
        |--------------------------------------------------------------------------
        | TOMBOL RESET
        |--------------------------------------------------------------------------
        */

        $("#btnReset").on(

            "click",

            function() {


                /*
                |--------------------------------------------------------------------------
                | KOSONGKAN INPUT
                |--------------------------------------------------------------------------
                */

                $("#filterTanggal")
                    .val("");


                /*
                |--------------------------------------------------------------------------
                | SEMBUNYIKAN INFO
                |--------------------------------------------------------------------------
                */

                $("#infoFilter")
                    .hide();


                $("#tanggalFilterText")
                    .text("");


                /*
                |--------------------------------------------------------------------------
                | LOAD SEMUA DATA
                |--------------------------------------------------------------------------
                */

                loadData("");

            }

        );



        /*
        |--------------------------------------------------------------------------
        | FORMAT TANGGAL
        |--------------------------------------------------------------------------
        */

        function formatTanggal(tanggal) {


            if (!tanggal) {

                return "-";

            }


            var parts =
                tanggal.split("-");


            if (
                parts.length !== 3
            ) {

                return tanggal;

            }


            return (

                parts[2] +

                "/" +

                parts[1] +

                "/" +

                parts[0]

            );

        }



        /*
        |--------------------------------------------------------------------------
        | LOAD DATA PERTAMA KALI
        |--------------------------------------------------------------------------
        */

        loadData("");

    });

    // =====================================================
// CETAK RIWAYAT STOK
// =====================================================

  $("#btnCetak").on("click", function () {

    var tanggal = $("#filterTanggal").val();

    // =====================================================
    // URL CETAK
    // =====================================================

    var url =
        "<?= base_url('stock_history/print'); ?>";

    // =====================================================
    // JIKA ADA FILTER TANGGAL
    // =====================================================

    if (tanggal) {

        url += "?tanggal=" + encodeURIComponent(tanggal);

    }

    // =====================================================
    // BUKA HALAMAN CETAK
    // =====================================================

    var printWindow = window.open(
        url,
        "_blank",
        "width=1200,height=800"
    );


    // =====================================================
    // CEK POPUP
    // =====================================================

    if (!printWindow) {

        Swal.fire({

            icon: "warning",

            title: "Popup Diblokir",

            text:
                "Browser memblokir halaman cetak. Silakan izinkan popup untuk website ini.",

            confirmButtonText: "OK"

        });

    }

});
</script>