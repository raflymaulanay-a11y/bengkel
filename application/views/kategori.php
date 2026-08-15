<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<div class="content-wrapper">

    <!-- ========================================================= -->
    <!-- HEADER -->
    <!-- ========================================================= -->

    <section class="content-header">

        <h1>
            Produk -
            <?= html_escape($category->nama_kategori); ?>
        </h1>

        <ol class="breadcrumb">

            <li>
                <a href="<?= base_url(); ?>">
                    <i class="fa fa-dashboard"></i>
                    Dashboard
                </a>
            </li>

            <li>
                <a href="<?= base_url('sparepart'); ?>">
                    Sparepart
                </a>
            </li>

            <li class="active">
                <?= html_escape($category->nama_kategori); ?>
            </li>

        </ol>

    </section>


    <!-- ========================================================= -->
    <!-- CONTENT -->
    <!-- ========================================================= -->

    <section class="content">

        <div class="box box-primary">

            <!-- ================================================= -->
            <!-- BOX HEADER -->
            <!-- ================================================= -->

            <div class="box-header with-border">

                <!-- KEMBALI -->

                <a
                    href="<?= base_url('sparepart'); ?>"
                    class="btn btn-default btn-sm"
                >
                    <i class="fa fa-arrow-left"></i>
                    Kembali
                </a>


                <!-- TAMBAH PRODUK -->

                <button
                    type="button"
                    class="btn btn-success btn-sm pull-right"
                    data-toggle="modal"
                    data-target="#modalTambah"
                >

                    <i class="fa fa-plus"></i>

                    Tambahkan Produk

                </button>

            </div>


            <!-- ================================================= -->
            <!-- BOX BODY -->
            <!-- ================================================= -->

            <div class="box-body">

                <div class="table-responsive">

                    <table
                        class="table table-bordered table-striped"
                        style="width:100%;"
                    >

                        <thead>

                            <tr>

                                <th width="5%">
                                    #
                                </th>

                                <th>
                                    Nama Produk / Merek
                                </th>

                                <th width="20%">
                                    Harga
                                </th>

                                <th width="15%">
                                    Stok
                                </th>

                                <th width="15%">
                                    Aksi
                                </th>

                            </tr>

                        </thead>


                        <tbody>

                            <?php if (!empty($products)): ?>

                                <?php $no = 1; ?>


                                <?php foreach ($products as $product): ?>

                                    <tr>

                                        <!-- NOMOR -->

                                        <td>
                                            <?= $no++; ?>
                                        </td>


                                        <!-- NAMA PRODUK -->

                                        <td>
                                            <?= html_escape(
                                                $product->nama_produk
                                            ); ?>
                                        </td>


                                        <!-- HARGA -->

                                        <td>

                                            Rp
                                            <?= number_format(
                                                (int) $product->harga,
                                                0,
                                                ',',
                                                '.'
                                            ); ?>

                                        </td>


                                        <!-- STOK -->

                                        <td>

                                            <strong>
                                                <?= (int) $product->stok; ?>
                                            </strong>

                                        </td>


                                        <!-- AKSI -->

                                        <td>

                                            <!-- EDIT -->

                                            <button
                                                type="button"
                                                class="btn btn-primary btn-sm btn-edit"
                                                data-id="<?= $product->id; ?>"
                                                data-name="<?= html_escape($product->nama_produk); ?>"
                                                data-price="<?= (int) $product->harga; ?>"
                                                data-stock="<?= (int) $product->stok; ?>"
                                                title="Edit"
                                            >

                                                <i class="fa fa-edit"></i>

                                            </button>


                                            <!-- HAPUS -->

                                            <button
                                                type="button"
                                                class="btn btn-danger btn-sm btn-delete"
                                                data-id="<?= $product->id; ?>"
                                                data-name="<?= html_escape($product->nama_produk); ?>"
                                                title="Hapus"
                                            >

                                                <i class="fa fa-trash"></i>

                                            </button>

                                        </td>

                                    </tr>

                                <?php endforeach; ?>


                            <?php else: ?>

                                <tr>

                                    <td
                                        colspan="5"
                                        class="text-center"
                                    >

                                        Belum ada produk dalam kategori

                                        <strong>
                                            <?= html_escape(
                                                $category->nama_kategori
                                            ); ?>
                                        </strong>.

                                    </td>

                                </tr>

                            <?php endif; ?>

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </section>

</div>



<!-- ============================================================= -->
<!-- MODAL TAMBAH PRODUK -->
<!-- ============================================================= -->

<div
    class="modal fade"
    id="modalTambah"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">


            <!-- HEADER -->

            <div class="modal-header">

                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                >

                    <span aria-hidden="true">
                        &times;
                    </span>

                </button>


                <h4 class="modal-title">

                    <i class="fa fa-plus-circle"></i>

                    Tambah Produk -

                    <?= html_escape(
                        $category->nama_kategori
                    ); ?>

                </h4>

            </div>


            <!-- FORM -->

            <form id="formTambah">

                <div class="modal-body">


                    <!-- NAMA PRODUK -->

                    <div class="form-group">

                        <label>
                            Nama Produk / Merek
                        </label>

                        <input
                            type="text"
                            name="nama_produk"
                            id="tambah_nama_produk"
                            class="form-control"
                            placeholder="Contoh: Federal 1L"
                            autocomplete="off"
                            required
                        >

                    </div>


                    <!-- HARGA -->

                    <div class="form-group">

                        <label>
                            Harga
                        </label>

                        <input
                            type="number"
                            name="harga"
                            id="tambah_harga"
                            class="form-control"
                            placeholder="Contoh: 50000"
                            min="0"
                            required
                        >

                    </div>


                    <!-- STOK AWAL -->

                    <div class="form-group">

                        <label>
                            Stok Awal
                        </label>

                        <input
                            type="number"
                            name="stok"
                            id="tambah_stok"
                            class="form-control"
                            placeholder="Contoh: 20"
                            min="0"
                            required
                        >

                        <small class="text-muted">

                            Masukkan stok awal produk.
                            Setelah produk dibuat,
                            stok akan dikelola melalui
                            Barang Masuk dan Barang Keluar.

                        </small>

                    </div>

                </div>


                <!-- FOOTER -->

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-default"
                        data-dismiss="modal"
                    >

                        <i class="fa fa-times"></i>

                        Batal

                    </button>


                    <button
                        type="submit"
                        class="btn btn-success"
                        id="btnSimpan"
                    >

                        <i class="fa fa-save"></i>

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



<!-- ============================================================= -->
<!-- MODAL EDIT PRODUK -->
<!-- ============================================================= -->

<div
    class="modal fade"
    id="modalEdit"
    tabindex="-1"
    role="dialog"
    aria-hidden="true"
>

    <div class="modal-dialog">

        <div class="modal-content">


            <!-- HEADER -->

            <div class="modal-header">

                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                >

                    <span aria-hidden="true">
                        &times;
                    </span>

                </button>


                <h4 class="modal-title">

                    <i class="fa fa-edit"></i>

                    Edit Produk

                </h4>

            </div>


            <!-- FORM -->

            <form id="formEdit">

                <input
                    type="hidden"
                    name="id"
                    id="edit_id"
                >


                <div class="modal-body">


                    <!-- NAMA -->

                    <div class="form-group">

                        <label>
                            Nama Produk / Merek
                        </label>

                        <input
                            type="text"
                            name="nama_produk"
                            id="edit_nama_produk"
                            class="form-control"
                            autocomplete="off"
                            required
                        >

                    </div>


                    <!-- HARGA -->

                    <div class="form-group">

                        <label>
                            Harga
                        </label>

                        <input
                            type="number"
                            name="harga"
                            id="edit_harga"
                            class="form-control"
                            min="0"
                            required
                        >

                    </div>


                    <!-- STOK -->

                    <div class="form-group">

                        <label>
                            Stok Saat Ini
                        </label>

                        <input
                            type="text"
                            id="edit_stok"
                            class="form-control"
                            readonly
                        >

                        <small class="text-muted">

                            Stok tidak dapat diedit di sini.

                            Gunakan menu
                            <strong>Barang Masuk</strong>
                            untuk menambah stok dan
                            <strong>Barang Keluar</strong>
                            untuk mengurangi stok.

                        </small>

                    </div>

                </div>


                <!-- FOOTER -->

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-default"
                        data-dismiss="modal"
                    >

                        <i class="fa fa-times"></i>

                        Batal

                    </button>


                    <button
                        type="submit"
                        class="btn btn-primary"
                        id="btnUpdate"
                    >

                        <i class="fa fa-save"></i>

                        Simpan Perubahan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>



<!-- ============================================================= -->
<!-- JAVASCRIPT -->
<!-- ============================================================= -->

<script>

$(document).ready(function() {


    /*
    |--------------------------------------------------------------------------
    | TAMBAH PRODUK
    |--------------------------------------------------------------------------
    */

    $("#formTambah").on(
        "submit",
        function(e)
    {

        e.preventDefault();


        var form =
            $(this);

        var button =
            $("#btnSimpan");


        var nama_produk =
            $("#tambah_nama_produk")
                .val()
                .trim();


        var harga =
            $("#tambah_harga")
                .val();


        var stok =
            $("#tambah_stok")
                .val();


        // =====================================================
        // VALIDASI NAMA
        // =====================================================

        if (nama_produk === "") {

            alert(
                "Nama produk wajib diisi"
            );

            $("#tambah_nama_produk")
                .focus();

            return;
        }


        // =====================================================
        // VALIDASI HARGA
        // =====================================================

        if (harga === "") {

            alert(
                "Harga wajib diisi"
            );

            $("#tambah_harga")
                .focus();

            return;
        }


        // =====================================================
        // VALIDASI STOK
        // =====================================================

        if (stok === "") {

            alert(
                "Stok awal wajib diisi"
            );

            $("#tambah_stok")
                .focus();

            return;
        }


        // =====================================================
        // DISABLE BUTTON
        // =====================================================

        button.prop(
            "disabled",
            true
        );


        button.html(
            '<i class="fa fa-spinner fa-spin"></i> Menyimpan...'
        );


        // =====================================================
        // AJAX TAMBAH
        // =====================================================

        $.ajax({

            url:
                "<?= base_url(
                    'kategori/kategori_insert/' .
                    $category->id
                ); ?>",

            type:
                "POST",

            data:
                form.serialize(),

            dataType:
                "json",


            success:
                function(response)
            {

                if (
                    response.status === true
                ) {

                    alert(
                        response.msg
                    );


                    $("#modalTambah")
                        .modal("hide");


                    form[0]
                        .reset();


                    location.reload();

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
                    "ERROR TAMBAH:",
                    xhr.responseText
                );


                alert(
                    "Terjadi kesalahan saat menyimpan produk."
                );
            },


            complete:
                function()
            {

                button.prop(
                    "disabled",
                    false
                );


                button.html(
                    '<i class="fa fa-save"></i> Simpan'
                );
            }

        });

    });



    /*
    |--------------------------------------------------------------------------
    | BUKA MODAL EDIT
    |--------------------------------------------------------------------------
    */

    $(document).on(
        "click",
        ".btn-edit",
        function()
    {

        var id =
            $(this)
                .attr("data-id");


        var name =
            $(this)
                .attr("data-name");


        var price =
            $(this)
                .attr("data-price");


        var stock =
            $(this)
                .attr("data-stock");


        // =====================================================
        // ISI FORM
        // =====================================================

        $("#edit_id")
            .val(id);


        $("#edit_nama_produk")
            .val(name);


        $("#edit_harga")
            .val(price);


        $("#edit_stok")
            .val(stock);


        // =====================================================
        // TAMPILKAN MODAL
        // =====================================================

        $("#modalEdit")
            .modal("show");

    });



    /*
    |--------------------------------------------------------------------------
    | UPDATE PRODUK
    |--------------------------------------------------------------------------
    |
    | HANYA NAMA DAN HARGA.
    |
    | STOK TIDAK DIUBAH.
    |
    */

    $("#formEdit").on(
        "submit",
        function(e)
    {

        e.preventDefault();


        var id =
            $("#edit_id")
                .val();


        var button =
            $("#btnUpdate");


        var nama_produk =
            $("#edit_nama_produk")
                .val()
                .trim();


        var harga =
            $("#edit_harga")
                .val();


        // =====================================================
        // VALIDASI NAMA
        // =====================================================

        if (nama_produk === "") {

            alert(
                "Nama produk wajib diisi"
            );

            $("#edit_nama_produk")
                .focus();

            return;
        }


        // =====================================================
        // VALIDASI HARGA
        // =====================================================

        if (harga === "") {

            alert(
                "Harga wajib diisi"
            );

            $("#edit_harga")
                .focus();

            return;
        }


        // =====================================================
        // DISABLE BUTTON
        // =====================================================

        button.prop(
            "disabled",
            true
        );


        button.html(
            '<i class="fa fa-spinner fa-spin"></i> Menyimpan...'
        );


        // =====================================================
        // AJAX UPDATE
        // =====================================================

        $.ajax({

            url:
                "<?= base_url(
                    'kategori/kategori_update/'
                ); ?>" +
                id,

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
                        response.msg
                    );


                    $("#modalEdit")
                        .modal("hide");


                    location.reload();

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
                    "ERROR EDIT:",
                    xhr.responseText
                );


                alert(
                    "Terjadi kesalahan saat mengedit produk."
                );
            },


            complete:
                function()
            {

                button.prop(
                    "disabled",
                    false
                );


                button.html(
                    '<i class="fa fa-save"></i> Simpan Perubahan'
                );
            }

        });

    });



    /*
    |--------------------------------------------------------------------------
    | HAPUS PRODUK
    |--------------------------------------------------------------------------
    */

    $(document).on(
        "click",
        ".btn-delete",
        function()
    {

        var id =
            $(this)
                .attr("data-id");


        var name =
            $(this)
                .attr("data-name");


        // =====================================================
        // KONFIRMASI
        // =====================================================

        if (
            !confirm(
                'Apakah Anda yakin ingin menghapus produk "' +
                name +
                '"?'
            )
        ) {

            return;
        }


        // =====================================================
        // AJAX DELETE
        // =====================================================

        $.ajax({

            url:
                "<?= base_url(
                    'kategori/kategori_delete/'
                ); ?>" +
                id,

            type:
                "POST",

            dataType:
                "json",


            success:
                function(response)
            {

                if (
                    response.status === true
                ) {

                    alert(
                        response.msg
                    );


                    location.reload();

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
                    "ERROR DELETE:",
                    xhr.responseText
                );


                alert(
                    "Terjadi kesalahan saat menghapus produk."
                );
            }

        });

    });



    /*
    |--------------------------------------------------------------------------
    | RESET FORM TAMBAH
    |--------------------------------------------------------------------------
    */

    $("#modalTambah").on(
        "hidden.bs.modal",
        function()
    {

        $("#formTambah")[0]
            .reset();

    });

});

</script>