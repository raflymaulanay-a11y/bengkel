<div class="row">

    <div class="col-md-12">

        <div class="card">

            <!-- ================================================= -->
            <!-- HEADER -->
            <!-- ================================================= -->

            <div class="card-header">

                <h4>

                    <i class="fa fa-sign-out"></i>

                    Barang Keluar

                </h4>

            </div>


            <!-- ================================================= -->
            <!-- BODY -->
            <!-- ================================================= -->

            <div class="card-body">

                <form id="formBarangKeluar">


                    <!-- ================================================= -->
                    <!-- TANGGAL -->
                    <!-- ================================================= -->

                    <div class="form-group">

                        <label>
                            Tanggal
                        </label>

                        <input
                            type="date"
                            name="tanggal"
                            class="form-control"
                            value="<?= date('Y-m-d'); ?>"
                            required
                        >

                    </div>


                    <!-- ================================================= -->
                    <!-- KATEGORI -->
                    <!-- ================================================= -->

                    <div class="form-group">

                        <label>
                            Kategori
                        </label>

                        <select
                            id="category_id"
                            class="form-control"
                            required
                        >

                            <option value="">
                                -- Pilih Kategori --
                            </option>

                        </select>

                    </div>


                    <!-- ================================================= -->
                    <!-- PRODUK -->
                    <!-- ================================================= -->

                    <div class="form-group">

                        <label>
                            Produk / Merek
                        </label>

                        <select
                            name="category_product_id"
                            id="category_product_id"
                            class="form-control"
                            required
                            disabled
                        >

                            <option value="">
                                -- Pilih Produk --
                            </option>

                        </select>

                    </div>


                    <!-- ================================================= -->
                    <!-- STOK SAAT INI -->
                    <!-- ================================================= -->

                    <div class="form-group">

                        <label>
                            Stok Saat Ini
                        </label>

                        <input
                            type="text"
                            id="stok_sekarang"
                            class="form-control"
                            value="0"
                            readonly
                        >

                    </div>


                    <!-- ================================================= -->
                    <!-- JUMLAH BARANG KELUAR -->
                    <!-- ================================================= -->

                    <div class="form-group">

                        <label>
                            Jumlah Barang Keluar
                        </label>

                        <input
                            type="number"
                            name="jumlah"
                            id="jumlah"
                            class="form-control"
                            min="1"
                            required
                            disabled
                        >

                        <small
                            id="infoStok"
                            class="text-muted"
                        >
                            Pilih produk terlebih dahulu.
                        </small>

                    </div>


                    <!-- ================================================= -->
                    <!-- KETERANGAN -->
                    <!-- ================================================= -->

                    <div class="form-group">

                        <label>
                            Keterangan
                        </label>

                        <textarea
                            name="keterangan"
                            class="form-control"
                            rows="3"
                            placeholder="Contoh: Penjualan atau pemakaian"
                        ></textarea>

                    </div>


                    <!-- ================================================= -->
                    <!-- TOMBOL -->
                    <!-- ================================================= -->

                    <button
                        type="submit"
                        id="btnSimpan"
                        class="btn btn-danger"
                        disabled
                    >

                        <i class="fa fa-save"></i>

                        Simpan Barang Keluar

                    </button>


                    <a
                        href="<?= base_url('sparepart'); ?>"
                        class="btn btn-secondary"
                    >

                        <i class="fa fa-arrow-left"></i>

                        Kembali

                    </a>

                </form>

            </div>

        </div>

    </div>

</div>



<script>

$(document).ready(function() {


    /*
    |--------------------------------------------------------------------------
    | LOAD KATEGORI
    |--------------------------------------------------------------------------
    */

    $.ajax({

        url:
            "<?= base_url('stock_out/categories'); ?>",

        type:
            "GET",

        dataType:
            "json",

        success:
            function(response)
        {

            if (
                response.status === true
            ) {

                var categorySelect =
                    $("#category_id");


                categorySelect
                    .empty();


                categorySelect.append(
                    '<option value="">-- Pilih Kategori --</option>'
                );


                $.each(
                    response.data,
                    function(index, category)
                {

                    categorySelect.append(

                        '<option value="' +
                        category.id +
                        '">' +
                        category.nama_kategori +
                        '</option>'

                    );

                });

            }

        },

        error:
            function(xhr)
        {

            console.log(
                "ERROR KATEGORI:",
                xhr.responseText
            );

        }

    });



    /*
    |--------------------------------------------------------------------------
    | KETIKA KATEGORI DIPILIH
    |--------------------------------------------------------------------------
    */

    $("#category_id").on(
        "change",
        function()
    {

        var category_id =
            $(this).val();


        var productSelect =
            $("#category_product_id");


        // =====================================================
        // RESET PRODUK
        // =====================================================

        productSelect
            .empty();


        productSelect.append(
            '<option value="">-- Pilih Produk --</option>'
        );


        // =====================================================
        // RESET STOK
        // =====================================================

        $("#stok_sekarang")
            .val("0");


        $("#jumlah")
            .val("")
            .prop(
                "disabled",
                true
            );


        $("#btnSimpan")
            .prop(
                "disabled",
                true
            );


        $("#infoStok")
            .text(
                "Pilih produk terlebih dahulu."
            );


        // =====================================================
        // JIKA KATEGORI KOSONG
        // =====================================================

        if (!category_id) {

            productSelect
                .prop(
                    "disabled",
                    true
                );

            return;
        }


        // =====================================================
        // LOAD PRODUK
        // =====================================================

        $.ajax({

            url:
                "<?= base_url('stock_out/products/'); ?>" +
                category_id,

            type:
                "GET",

            dataType:
                "json",


            success:
                function(response)
            {

                if (
                    response.status === true
                ) {

                    productSelect
                        .prop(
                            "disabled",
                            false
                        );


                    $.each(
                        response.data,
                        function(index, product)
                    {

                        productSelect.append(

                            '<option ' +
                            'value="' +
                            product.id +
                            '" ' +
                            'data-stock="' +
                            product.stok +
                            '">' +

                            product.nama_produk +

                            ' (Stok: ' +
                            product.stok +
                            ')' +

                            '</option>'

                        );

                    });

                } else {

                    alert(
                        response.msg
                    );

                }

            },


            error:
                function(xhr)
            {

                console.log(
                    "ERROR PRODUK:",
                    xhr.responseText
                );


                alert(
                    "Gagal mengambil data produk."
                );

            }

        });

    });



    /*
    |--------------------------------------------------------------------------
    | KETIKA PRODUK DIPILIH
    |--------------------------------------------------------------------------
    */

    $("#category_product_id").on(
        "change",
        function()
    {

        var selected =
            $(this)
                .find("option:selected");


        var product_id =
            $(this).val();


        var stok =
            selected.attr(
                "data-stock"
            );


        // =====================================================
        // PRODUK KOSONG
        // =====================================================

        if (!product_id) {

            $("#stok_sekarang")
                .val("0");


            $("#jumlah")
                .val("")
                .prop(
                    "disabled",
                    true
                );


            $("#btnSimpan")
                .prop(
                    "disabled",
                    true
                );


            $("#infoStok")
                .text(
                    "Pilih produk terlebih dahulu."
                );

            return;
        }


        // =====================================================
        // TAMPILKAN STOK
        // =====================================================

        stok =
            parseInt(stok) || 0;


        $("#stok_sekarang")
            .val(stok);


        // =====================================================
        // SET MAX JUMLAH
        // =====================================================

        $("#jumlah")
            .attr(
                "max",
                stok
            )
            .prop(
                "disabled",
                false
            );


        // =====================================================
        // INFO STOK
        // =====================================================

        if (stok <= 0) {

            $("#infoStok")
                .text(
                    "Stok produk kosong. Barang tidak dapat dikeluarkan."
                );


            $("#jumlah")
                .prop(
                    "disabled",
                    true
                );


            $("#btnSimpan")
                .prop(
                    "disabled",
                    true
                );

        } else {

            $("#infoStok")
                .text(
                    "Jumlah barang keluar maksimal " +
                    stok +
                    " unit."
                );


            $("#btnSimpan")
                .prop(
                    "disabled",
                    false
                );

        }

    });



    /*
    |--------------------------------------------------------------------------
    | VALIDASI JUMLAH
    |--------------------------------------------------------------------------
    */

    $("#jumlah").on(
        "input",
        function()
    {

        var stok =
            parseInt(
                $("#stok_sekarang").val()
            ) || 0;


        var jumlah =
            parseInt(
                $(this).val()
            ) || 0;


        // =====================================================
        // JUMLAH MELEBIHI STOK
        // =====================================================

        if (
            jumlah > stok
        ) {

            $("#infoStok")
                .text(
                    "Jumlah tidak boleh melebihi stok yang tersedia (" +
                    stok +
                    ")."
                );


            $("#btnSimpan")
                .prop(
                    "disabled",
                    true
                );

        }


        // =====================================================
        // JUMLAH TIDAK VALID
        // =====================================================

        else if (
            jumlah <= 0
        ) {

            $("#infoStok")
                .text(
                    "Jumlah barang keluar harus lebih dari 0."
                );


            $("#btnSimpan")
                .prop(
                    "disabled",
                    true
                );

        }


        // =====================================================
        // JUMLAH VALID
        // =====================================================

        else {

            $("#infoStok")
                .text(
                    "Stok setelah transaksi: " +
                    (stok - jumlah)
                );


            $("#btnSimpan")
                .prop(
                    "disabled",
                    false
                );

        }

    });



    /*
    |--------------------------------------------------------------------------
    | SUBMIT BARANG KELUAR
    |--------------------------------------------------------------------------
    */

    $("#formBarangKeluar").on(
        "submit",
        function(e)
    {

        e.preventDefault();


        var stok =
            parseInt(
                $("#stok_sekarang").val()
            ) || 0;


        var jumlah =
            parseInt(
                $("#jumlah").val()
            ) || 0;


        // =====================================================
        // VALIDASI PRODUK
        // =====================================================

        if (
            !$("#category_product_id").val()
        ) {

            alert(
                "Silakan pilih produk terlebih dahulu."
            );

            return;
        }


        // =====================================================
        // VALIDASI JUMLAH
        // =====================================================

        if (
            jumlah <= 0
        ) {

            alert(
                "Jumlah barang keluar harus lebih dari 0."
            );

            return;
        }


        // =====================================================
        // VALIDASI STOK
        // =====================================================

        if (
            jumlah > stok
        ) {

            alert(
                "Jumlah barang keluar tidak boleh melebihi stok yang tersedia."
            );

            return;
        }


        // =====================================================
        // KONFIRMASI
        // =====================================================

        if (
            !confirm(
                "Apakah data barang keluar sudah benar?"
            )
        ) {

            return;
        }


        // =====================================================
        // BUTTON
        // =====================================================

        var button =
            $("#btnSimpan");


        button
            .prop(
                "disabled",
                true
            );


        button.html(
            '<i class="fa fa-spinner fa-spin"></i> Menyimpan...'
        );


        // =====================================================
        // AJAX
        // =====================================================

        $.ajax({

            url:
                "<?= base_url('stock_out/insert'); ?>",

            type:
                "POST",

            data:
                $(this).serialize(),

            dataType:
                "json",


            success:
                function(response)
            {

                if (
                    response.status === true
                ) {

                    alert(
                        response.msg +
                        "\n\n" +
                        "Stok lama: " +
                        response.stok_lama +
                        "\n" +
                        "Jumlah keluar: " +
                        response.jumlah +
                        "\n" +
                        "Stok baru: " +
                        response.stok_baru
                    );


                    // =================================================
                    // RESET FORM
                    // =================================================

                    $("#formBarangKeluar")[0]
                        .reset();


                    $("#category_product_id")
                        .empty()
                        .append(
                            '<option value="">-- Pilih Produk --</option>'
                        )
                        .prop(
                            "disabled",
                            true
                        );


                    $("#stok_sekarang")
                        .val("0");


                    $("#jumlah")
                        .val("")
                        .prop(
                            "disabled",
                            true
                        );


                    $("#infoStok")
                        .text(
                            "Pilih produk terlebih dahulu."
                        );


                    // =================================================
                    // RELOAD
                    // =================================================

                    location.reload();

                } else {

                    alert(
                        response.msg
                    );


                    button
                        .prop(
                            "disabled",
                            false
                        );


                    button.html(
                        '<i class="fa fa-save"></i> Simpan Barang Keluar'
                    );

                }

            },


            error:
                function(xhr)
            {

                console.log(
                    "ERROR BARANG KELUAR:",
                    xhr.responseText
                );


                alert(
                    "Terjadi kesalahan saat menyimpan barang keluar."
                );


                button
                    .prop(
                        "disabled",
                        false
                    );


                button.html(
                    '<i class="fa fa-save"></i> Simpan Barang Keluar'
                );

            }

        });

    });

});

</script>