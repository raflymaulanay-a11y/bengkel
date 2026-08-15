<div class="breadcrumbs">

    <div class="col-sm-4">

        <div class="page-header float-left">

            <div class="page-title">

                <h1>Pengaturan</h1>

            </div>

        </div>

    </div>


    <div class="col-sm-8">

        <div class="page-header float-right">

            <div class="page-title">

                <ol class="breadcrumb text-right">

                    <li>
                        <a href="<?= base_url("dashboard"); ?>">
                            Dashboard
                        </a>
                    </li>

                    <li class="active">
                        Pengaturan
                    </li>

                </ol>

            </div>

        </div>

    </div>

</div>


<div class="content mt-3">

    <div class="card">


        <!-- =====================================================
             HEADER CARD
             ===================================================== -->

        <div class="card-header">

            <h4>

                <i class="fa fa-cog"></i>

                Pengaturan Bengkel

            </h4>

        </div>


        <div class="card-body">


            <!-- =================================================
                 LOGO + FOTO ADMIN
                 ================================================= -->

            <div class="row">


                <!-- =================================================
                     LOGO BENGKEL
                     ================================================= -->

                <div class="col-md-6 col-sm-12">

                    <div class="form-group">

                        <label>
                            <strong>Logo Bengkel</strong>
                        </label>


                        <input
                            type="file"
                            name="userfile"
                            class="dropify logo-upload"
                            data-height="200"
                            data-allowed-file-extensions="png jpg jpeg"
                            data-max-file-size="2M"
                            data-default-file="<?= base_url('img/logo.png') . '?v=' . time(); ?>"
                        >


                        <small class="form-text text-muted">

                            Format: PNG, JPG, JPEG

                            <br>

                            Maksimal: 2 MB

                        </small>

                    </div>

                </div>


                <!-- =================================================
                     FOTO ADMIN
                     ================================================= -->

                <div class="col-md-6 col-sm-12">

                    <div class="form-group">

                        <label>
                            <strong>Foto Admin</strong>
                        </label>


                        <input
                            type="file"
                            name="adminfile"
                            class="dropify admin-upload"
                            data-height="200"
                            data-allowed-file-extensions="png jpg jpeg"
                            data-max-file-size="2M"
                            data-default-file="<?= base_url('img/admin.png') . '?v=' . time(); ?>"
                        >


                        <small class="form-text text-muted">

                            Foto profil admin

                            <br>

                            Format: PNG, JPG, JPEG

                            <br>

                            Maksimal: 2 MB

                        </small>

                    </div>

                </div>


            </div>


            <!-- =================================================
                 NAMA BENGKEL
                 ================================================= -->

            <div class="row">

                <div class="col-md-6 col-sm-12">

                    <div class="form-group">

                        <label>
                            Nama Bengkel
                        </label>


                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            value="<?= htmlspecialchars(
                                $this->shop_info->get_shop_name()
                            ); ?>"
                            placeholder="Masukkan nama bengkel"
                        >

                    </div>

                </div>

            </div>


            <!-- =================================================
                 ALAMAT
                 ================================================= -->

            <div class="row">

                <div class="col-md-8 col-sm-12">

                    <div class="form-group">

                        <label>
                            Alamat
                        </label>


                        <textarea
                            name="address"
                            class="form-control"
                            rows="4"
                            placeholder="Masukkan alamat bengkel"
                        ><?= htmlspecialchars(
                            $this->shop_info->get_shop_address()
                        ); ?></textarea>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 BUTTON SIMPAN
                 ================================================= -->

            <button
                type="button"
                class="btn btn-primary btn-save"
            >

                <i class="fa fa-save"></i>

                Simpan

            </button>


        </div>

    </div>

</div>



<script>

$(document).ready(function() {


    // =========================================================
    // DROPIFY
    // =========================================================

    $('.dropify').dropify();



    // =========================================================
    // SIMPAN
    // =========================================================

    $(".btn-save").on(
        "click",
        function()
        {


            // =================================================
            // AMBIL DATA
            // =================================================

            var name =
                $('input[name="name"]')
                    .val()
                    .trim();


            var address =
                $('textarea[name="address"]')
                    .val()
                    .trim();


            // FILE LOGO

            var logoFile =
                $('input[name="userfile"]')[0]
                    .files[0];


            // FILE ADMIN

            var adminFile =
                $('input[name="adminfile"]')[0]
                    .files[0];



            // =================================================
            // VALIDASI NAMA
            // =================================================

            if (!name) {

                Swal.fire(
                    "Peringatan",
                    "Nama bengkel wajib diisi.",
                    "warning"
                );

                return;

            }



            // =================================================
            // VALIDASI ALAMAT
            // =================================================

            if (!address) {

                Swal.fire(
                    "Peringatan",
                    "Alamat bengkel wajib diisi.",
                    "warning"
                );

                return;

            }



            // =================================================
            // FUNGSI VALIDASI FILE
            // =================================================

            function validateFile(file, namaFile) {


                if (!file) {

                    return true;

                }


                // Ambil extension

                var extension =
                    file.name
                        .split(".")
                        .pop()
                        .toLowerCase();



                // Validasi format

                if (
                    extension !== "png" &&
                    extension !== "jpg" &&
                    extension !== "jpeg"
                ) {

                    Swal.fire(
                        "Format Tidak Sesuai",
                        namaFile +
                        " harus berformat PNG, JPG, atau JPEG.",
                        "warning"
                    );

                    return false;

                }



                // Validasi ukuran

                if (file.size > 2097152) {

                    Swal.fire(
                        "Ukuran Terlalu Besar",
                        namaFile +
                        " maksimal 2 MB.",
                        "warning"
                    );

                    return false;

                }


                return true;

            }



            // =================================================
            // VALIDASI LOGO
            // =================================================

            if (
                !validateFile(
                    logoFile,
                    "Logo bengkel"
                )
            ) {

                return;

            }



            // =================================================
            // VALIDASI FOTO ADMIN
            // =================================================

            if (
                !validateFile(
                    adminFile,
                    "Foto admin"
                )
            ) {

                return;

            }



            // =================================================
            // KONFIRMASI
            // =================================================

            Swal.fire({

                title:
                    "Simpan Pengaturan?",

                text:
                    "Apakah data pengaturan bengkel sudah benar?",

                type:
                    "question",

                showCancelButton:
                    true,

                confirmButtonText:
                    "Ya, Simpan",

                cancelButtonText:
                    "Batal",

                confirmButtonColor:
                    "#3085d6",

                cancelButtonColor:
                    "#6c757d"

            }).then(
                function(result)
                {


                    // =========================================
                    // BATAL
                    // =========================================

                    if (!result.value) {

                        return;

                    }



                    // =========================================
                    // FORM DATA
                    // =========================================

                    var form =
                        new FormData();



                    // Nama

                    form.append(
                        "name",
                        name
                    );



                    // Alamat

                    form.append(
                        "address",
                        address
                    );



                    // Logo

                    if (logoFile) {

                        form.append(
                            "userfile",
                            logoFile
                        );

                    }



                    // Foto Admin

                    if (adminFile) {

                        form.append(
                            "adminfile",
                            adminFile
                        );

                    }



                    // =========================================
                    // BUTTON
                    // =========================================

                    var button =
                        $(".btn-save");


                    button.prop(
                        "disabled",
                        true
                    );


                    button.html(

                        '<i class="fa fa-spinner fa-spin"></i> ' +
                        'Menyimpan...'

                    );



                    // =========================================
                    // AJAX
                    // =========================================

                    $.ajax({

                        url:
                            "<?= base_url('setting/save_info'); ?>",

                        type:
                            "POST",

                        data:
                            form,

                        dataType:
                            "json",

                        processData:
                            false,

                        contentType:
                            false,



                        // =====================================
                        // SUCCESS
                        // =====================================

                        success:
                            function(response)
                            {


                                console.log(
                                    "RESPONSE SETTING:",
                                    response
                                );



                                if (
                                    response.status === true
                                ) {


                                    Swal.fire(

                                        "Berhasil",

                                        response.msg,

                                        "success"

                                    ).then(
                                        function()
                                        {

                                            location.reload();

                                        }
                                    );


                                } else {


                                    Swal.fire(

                                        "Gagal",

                                        response.msg,

                                        "error"

                                    );


                                    button.prop(
                                        "disabled",
                                        false
                                    );


                                    button.html(

                                        '<i class="fa fa-save"></i> ' +
                                        'Simpan'

                                    );

                                }

                            },



                        // =====================================
                        // ERROR
                        // =====================================

                        error:
                            function(xhr)
                            {


                                console.log(
                                    "ERROR SETTING:",
                                    xhr.responseText
                                );



                                Swal.fire(

                                    "Gagal",

                                    "Terjadi kesalahan saat menyimpan pengaturan.",

                                    "error"

                                );


                                button.prop(
                                    "disabled",
                                    false
                                );


                                button.html(

                                    '<i class="fa fa-save"></i> ' +
                                    'Simpan'

                                );

                            }

                    });

                }
            );

        }

    );

});

</script>