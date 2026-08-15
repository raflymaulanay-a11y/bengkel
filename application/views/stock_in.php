<div class="row">

    <div class="col-md-12">

        <div class="card">

            <div class="card-header">
                <h4>
                    <i class="fa fa-sign-in"></i>
                    Barang Masuk
                </h4>
            </div>

            <div class="card-body">

                <form id="formBarangMasuk">

                    <!-- TANGGAL -->
                    <div class="form-group">

                        <label>Tanggal</label>

                        <input
                            type="date"
                            name="tanggal"
                            class="form-control"
                            value="<?= date('Y-m-d'); ?>"
                            required
                        >

                    </div>


                    <!-- KATEGORI -->
                    <div class="form-group">

                        <label>Kategori</label>

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


                    <!-- PRODUK -->
                    <div class="form-group">

                        <label>Produk / Merek</label>

                        <select
                            name="category_product_id"
                            id="category_product_id"
                            class="form-control"
                            required
                            disabled
                        >

                            <option value="">
                                -- Pilih Kategori Terlebih Dahulu --
                            </option>

                        </select>

                    </div>


                    <!-- STOK SAAT INI -->
                    <div class="form-group">

                        <label>Stok Saat Ini</label>

                        <input
                            type="text"
                            id="stok_sekarang"
                            class="form-control"
                            value="0"
                            readonly
                        >

                    </div>


                    <!-- JUMLAH -->
                    <div class="form-group">

                        <label>
                            Jumlah Barang Masuk
                        </label>

                        <input
                            type="number"
                            name="jumlah"
                            id="jumlah"
                            class="form-control"
                            min="1"
                            required
                        >

                    </div>


                    <!-- KETERANGAN -->
                    <div class="form-group">

                        <label>Keterangan</label>

                        <textarea
                            name="keterangan"
                            class="form-control"
                            rows="3"
                            placeholder="Contoh: Pembelian dari supplier"
                        ></textarea>

                    </div>


                    <!-- TOMBOL -->
                    <button
                        type="submit"
                        class="btn btn-success"
                    >

                        <i class="fa fa-save"></i>
                        Simpan Barang Masuk

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

$(document).ready(function () {


    /*
    |--------------------------------------------------------------------------
    | LOAD KATEGORI
    |--------------------------------------------------------------------------
    */

    $.ajax({

        url: "<?= base_url('stock_in/categories'); ?>",

        type: "GET",

        dataType: "json",

        success: function (response) {

            if (response.status === true) {

                $.each(response.data, function (index, category) {

                    $("#category_id").append(
                        $("<option>", {
                            value: category.id,
                            text: category.nama_kategori
                        })
                    );

                });

            }

        },

        error: function () {

            Swal.fire(
                "Error",
                "Gagal mengambil data kategori",
                "error"
            );

        }

    });



    /*
    |--------------------------------------------------------------------------
    | KETIKA KATEGORI DIPILIH
    |--------------------------------------------------------------------------
    */

    $("#category_id").on("change", function () {

        var categoryId = $(this).val();


        /*
        | Reset produk
        */

        $("#category_product_id")
            .html(
                '<option value="">-- Pilih Produk --</option>'
            )
            .prop("disabled", true);


        /*
        | Reset stok
        */

        $("#stok_sekarang").val("0");


        /*
        | Jika kategori belum dipilih
        */

        if (!categoryId) {

            $("#category_product_id")
                .html(
                    '<option value="">-- Pilih Kategori Terlebih Dahulu --</option>'
                );

            return;

        }


        /*
        | Ambil produk berdasarkan kategori
        */

        $.ajax({

            url:
                "<?= base_url('stock_in/products/'); ?>" +
                categoryId,

            type: "GET",

            dataType: "json",

            success: function (response) {

                if (
                    response.status === true &&
                    response.data.length > 0
                ) {

                    $("#category_product_id")
                        .prop("disabled", false);


                    $.each(
                        response.data,
                        function (index, product) {

                            $("#category_product_id").append(

                                $("<option>", {

                                    value: product.id,

                                    text:
                                        product.nama_produk +
                                        " (Stok: " +
                                        product.stok +
                                        ")"

                                })

                            );

                        }
                    );


                } else {

                    $("#category_product_id")
                        .html(
                            '<option value="">-- Belum Ada Produk --</option>'
                        );

                }

            },

            error: function () {

                Swal.fire(
                    "Error",
                    "Gagal mengambil data produk",
                    "error"
                );

            }

        });

    });



    /*
    |--------------------------------------------------------------------------
    | KETIKA PRODUK DIPILIH
    |--------------------------------------------------------------------------
    */

    $("#category_product_id").on("change", function () {

        var productId = $(this).val();


        /*
        | Reset stok
        */

        $("#stok_sekarang").val("0");


        if (!productId) {

            return;

        }


        /*
        | Ambil stok dari option yang dipilih
        */

        var selectedText = $(this)
            .find("option:selected")
            .text();


        /*
        | Contoh:
        |
        | YAMALUB 800L (Stok: 20)
        |
        | Ambil angka stok
        */

        var match = selectedText.match(
            /\(Stok:\s*(\d+)\)/
        );


        if (match) {

            $("#stok_sekarang").val(
                match[1]
            );

        }

    });



    /*
    |--------------------------------------------------------------------------
    | SIMPAN BARANG MASUK
    |--------------------------------------------------------------------------
    */

    $("#formBarangMasuk").on("submit", function (e) {

        e.preventDefault();


        var form = $(this);

        var button = form.find(
            'button[type="submit"]'
        );


        /*
        | Nonaktifkan tombol
        */

        button.prop(
            "disabled",
            true
        );


        $.ajax({

            url:
                "<?= base_url('stock_in/insert'); ?>",

            type: "POST",

            data:
                form.serialize(),

            dataType: "json",

            success: function (response) {

                if (response.status === true) {

                    Swal.fire({

                        icon: "success",

                        title: "Berhasil",

                        text:
                            response.msg +
                            ". Stok sekarang: " +
                            response.stok_baru,

                        confirmButtonText: "OK"

                    }).then(function () {

                        /*
                        | Kembali ke Data Sparepart
                        */

                        window.location.href =
                            "<?= base_url('sparepart'); ?>";

                    });


                } else {

                    Swal.fire(
                        "Gagal",
                        response.msg,
                        "error"
                    );

                }

            },

            error: function (xhr) {

                Swal.fire(
                    "Error",
                    "Terjadi kesalahan saat menyimpan barang masuk.",
                    "error"
                );

            },

            complete: function () {

                button.prop(
                    "disabled",
                    false
                );

            }

        });

    });


});

</script>