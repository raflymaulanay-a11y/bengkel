<div class="breadcrumbs">

    <div class="col-sm-4">
        <div class="page-header float-left">
            <div class="page-title">

                <h1>Sparepart</h1>

            </div>
        </div>
    </div>


    <div class="col-sm-8">
        <div class="page-header float-right">
            <div class="page-title">

                <ol class="breadcrumb text-right">

                    <li>
                        <a href="<?=base_url("dashboard");?>">
                            Dashboard
                        </a>
                    </li>

                    <li class="active">
                        Sparepart
                    </li>

                </ol>

            </div>
        </div>
    </div>

</div>


<div class="content mt-3">

    <div class="card">


        <!-- =====================================================
             HEADER
        ====================================================== -->

        <div class="card-header">

            <button
                class="btn btn-success btn-sm btn-show-add"
                data-toggle="modal"
                data-target="#compose">

                <i class="fa fa-plus"></i>
                Tambah Sparepart

            </button>

        </div>


        <!-- =====================================================
             TABLE
        ====================================================== -->

        <div class="card-body">

            <div class="table-responsive">

                <table
                    class="table table-bordered"
                    id="data"
                    style="width:100%;">

                    <thead>

                        <tr>

                            <th style="width:50px;">
                                #
                            </th>

                            <th>
                                Nama
                            </th>

                            <th>
                                Jumlah Jenis
                            </th>

                            <th style="width:220px;">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                </table>

            </div>

        </div>

    </div>

</div>



<!-- =========================================================
     MODAL TAMBAH / EDIT SPAREPART
========================================================= -->

<div
    class="modal fade"
    id="compose"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">

    <div
        class="modal-dialog modal-md"
        role="document">

        <div class="modal-content">


            <!-- HEADER -->

            <div class="modal-header">

                <h5
                    class="modal-title"
                    id="largeModalLabel">

                    Tambah Sparepart

                </h5>


                <button
                    type="button"
                    class="close"
                    data-dismiss="modal">

                    <span>
                        &times;
                    </span>

                </button>

            </div>


            <!-- BODY -->

            <div class="modal-body">

                <form
                    action=""
                    id="compose-form">


                    <!-- =================================================
                         NAMA SPAREPART
                    ================================================== -->

                    <div class="form-group">

                        <label>
                            Nama Sparepart
                        </label>

                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            placeholder="Masukkan nama sparepart"
                            autocomplete="off">

                    </div>


                    <!-- =================================================
                         INFORMASI JUMLAH JENIS
                    ================================================== -->

                    <div
                        class="form-group"
                        id="jumlahJenisInfo"
                        style="display:none;">

                        <label>
                            Jumlah Jenis
                        </label>

                        <div
                            class="form-control"
                            style="
                                background:#f5f5f5;
                                cursor:not-allowed;
                            ">

                            <span id="jumlahJenisText">
                                0 jenis produk
                            </span>

                        </div>

                        <small class="text-muted">

                            Jumlah jenis dihitung otomatis dari
                            produk yang ada di dalam sparepart.

                        </small>

                    </div>


                </form>

            </div>


            <!-- FOOTER -->

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal">

                    Batal

                </button>


                <button
                    type="button"
                    class="btn btn-primary btn-submit">

                    <i class="fa fa-save"></i>
                    Simpan

                </button>

            </div>


        </div>

    </div>

</div>



<!-- =========================================================
     MODAL DELETE
========================================================= -->

<div
    class="modal fade"
    id="delete"
    tabindex="-1"
    role="dialog"
    aria-hidden="true">

    <div
        class="modal-dialog modal-md"
        role="document">

        <div class="modal-content">


            <div class="modal-header">

                <h5 class="modal-title">
                    Konfirmasi?
                </h5>


                <button
                    type="button"
                    class="close"
                    data-dismiss="modal">

                    <span>
                        &times;
                    </span>

                </button>

            </div>


            <div class="modal-body">

            </div>


            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-dismiss="modal">

                    Batal

                </button>


                <button
                    type="button"
                    class="btn btn-danger btn-del-confirm">

                    <i class="fa fa-trash"></i>
                    Hapus

                </button>

            </div>


        </div>

    </div>

</div>



<!-- =========================================================
     JAVASCRIPT
========================================================= -->

<script>

$(document).ready(function(){


    /*
    ============================================================
       TAMBAH SPAREPART
    ============================================================
    */

    $(".btn-show-add").on("click", function(){

        /*
        | Kosongkan nama
        */

        $("input[name=name]").val("");


        /*
        | Sembunyikan jumlah jenis
        |
        | Karena saat tambah kategori baru,
        | jumlah produknya masih 0.
        */

        $("#jumlahJenisInfo").hide();

        $("#jumlahJenisText")
            .text("0 jenis produk");


        /*
        | Judul modal
        */

        $("#compose .modal-title")
            .html("Tambah Sparepart");


        /*
        | Action tambah
        */

        $("#compose-form").attr(
            "action",
            "<?=base_url("sparepart/insert");?>"
        );

    });



    /*
    ============================================================
       DATATABLE
    ============================================================
    */

    $("#data").DataTable({

        "processing": true,

        "serverSide": true,

        "autoWidth": false,

        "order": [],

        "ajax": {

            "url":
                "<?=base_url("sparepart/json");?>"

        }

    });



    /*
    ============================================================
       SIMPAN
    ============================================================
    */

    $(".btn-submit").on("click", function(){


        /*
        | Ambil nama
        */

        var name =
            $("input[name=name]")
                .val()
                .trim();


        /*
        | Validasi
        */

        if(name === ""){

            Swal.fire(
                "Gagal",
                "Nama sparepart wajib diisi",
                "error"
            );

            return;

        }


        /*
        | Data yang dikirim
        |
        | HANYA name.
        */

        var form = {

            "name": name

        };


        /*
        | Ambil action
        */

        var action =
            $("#compose-form")
                .attr("action");


        /*
        ========================================================
           AJAX
        ========================================================
        */

        $.ajax({

            url: action,

            method: "POST",

            data: form,

            dataType: "json",


            success: function(data){


                if(data.status){


                    /*
                    | Tutup modal
                    */

                    $("#compose")
                        .modal("hide");


                    /*
                    | Reload DataTable
                    */

                    $("#data")
                        .DataTable()
                        .ajax
                        .reload(null, false);


                    /*
                    | Pesan berhasil
                    */

                    Swal.fire(
                        "Berhasil",
                        data.msg,
                        "success"
                    );


                }else{


                    Swal.fire(
                        "Gagal",
                        data.msg,
                        "error"
                    );

                }

            },


            error: function(xhr){

                console.log(
                    xhr.responseText
                );


                Swal.fire(
                    "Gagal",
                    "Terjadi kesalahan pada server",
                    "error"
                );

            }

        });

    });



    /*
    ============================================================
       EDIT SPAREPART
    ============================================================
    */

    $("body").on(
        "click",
        ".btn-edit",
        function(){


            /*
            | Ambil ID
            */

            var id =
                $(this)
                    .attr("data-id");


            /*
            | Ambil nama
            */

            var name =
                $(this)
                    .attr("data-name");


            /*
            | Ambil jumlah produk
            |
            | Dari Datatable:
            | data-jumlah_produk
            */

            var jumlah =
                $(this)
                    .attr("data-jumlah");


            /*
            | Jika kosong
            */

            if(
                jumlah === undefined ||
                jumlah === null ||
                jumlah === ""
            ){

                jumlah = 0;

            }


            /*
            | Judul
            */

            $("#compose .modal-title")
                .html("Edit Sparepart");


            /*
            | Action update
            */

            $("#compose-form").attr(
                "action",
                "<?=base_url("sparepart/update");?>/" + id
            );


            /*
            | Isi nama
            */

            $("input[name=name]")
                .val(name);


            /*
            | Tampilkan jumlah
            */

            $("#jumlahJenisText")
                .text(
                    jumlah + " jenis produk"
                );


            $("#jumlahJenisInfo")
                .show();


            /*
            | Tampilkan modal
            */

            $("#compose")
                .modal("show");

        }

    );



    /*
    ============================================================
       DELETE
    ============================================================
    */

    $("body").on(
        "click",
        ".btn-delete",
        function(){


            /*
            | Ambil ID
            */

            var id =
                $(this)
                    .attr("data-id");


            /*
            | Ambil nama
            */

            var name =
                $(this)
                    .attr("data-name");


            /*
            | Isi konfirmasi
            */

            $("#delete .modal-body")
                .html(

                    "Anda yakin ingin menghapus <b>" +
                    name +
                    "</b>?"

                );


            /*
            | Tampilkan modal
            */

            $("#delete")
                .modal("show");


            /*
            | Tombol hapus
            */

            $("#delete .btn-del-confirm")
                .attr(
                    "onclick",
                    "deleteData(" + id + ")"
                );

        }

    );


});



/* =========================================================
   FUNCTION DELETE
========================================================= */

function deleteData(id){


    $.getJSON(

        "<?=base_url("sparepart/delete");?>/" + id,

        function(data){


            if(data.status){


                /*
                | Tutup modal
                */

                $("#delete")
                    .modal("hide");


                /*
                | Reload
                */

                $("#data")
                    .DataTable()
                    .ajax
                    .reload(null, false);


                /*
                | Pesan
                */

                Swal.fire(
                    "Berhasil",
                    data.msg,
                    "success"
                );


            }else{


                Swal.fire(
                    "Gagal",
                    data.msg,
                    "error"
                );

            }

        }

    );

}

</script>