<?php
if (!isset($authPage)) {
    $authPage = FALSE;
}
?>

<!doctype html>

<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="">
<![endif]-->

<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8" lang="">
<![endif]-->

<!--[if IE 8]>
<html class="no-js lt-ie9" lang="">
<![endif]-->

<!--[if gt IE 8]>
<!-->
<html class="no-js" lang="en">
<!--
<![endif]-->

<head>

    <meta charset="utf-8">

    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?= $pageTitle; ?></title>

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >


    <!-- ===================================================== -->
    <!-- BOOTSTRAP -->
    <!-- ===================================================== -->

    <link
        rel="stylesheet"
        href="<?= base_url(); ?>assets/sufee/vendors/bootstrap/dist/css/bootstrap.min.css"
    >


    <!-- ===================================================== -->
    <!-- FONT AWESOME -->
    <!-- ===================================================== -->

    <link
        rel="stylesheet"
        href="<?= base_url(); ?>assets/sufee/vendors/font-awesome/css/font-awesome.min.css"
    >


    <!-- ===================================================== -->
    <!-- DATATABLE -->
    <!-- ===================================================== -->

    <link
        rel="stylesheet"
        href="<?= base_url(); ?>assets/sufee/vendors/datatables.net-bs4/css/dataTables.bootstrap4.min.css"
    >


    <!-- ===================================================== -->
    <!-- SWEET ALERT -->
    <!-- ===================================================== -->

    <link
        rel="stylesheet"
        href="<?= base_url(); ?>assets/sweetalert2/sweetalert2.min.css"
    >


    <!-- ===================================================== -->
    <!-- DATEPICKER -->
    <!-- ===================================================== -->

    <link
        rel="stylesheet"
        href="<?= base_url(); ?>assets/daterangepicker/css/datepicker.min.css"
    >

    <link
        rel="stylesheet"
        href="<?= base_url(); ?>assets/daterangepicker/css/datepicker-bs4.min.css"
    >


    <!-- ===================================================== -->
    <!-- PACE -->
    <!-- ===================================================== -->

    <link
        rel="stylesheet"
        href="<?= base_url(); ?>assets/pace-style.css"
    >


    <!-- ===================================================== -->
    <!-- DROPIFY -->
    <!-- ===================================================== -->

    <link
        rel="stylesheet"
        href="<?= base_url(); ?>assets/dropify/css/dropify.min.css"
    >


    <!-- ===================================================== -->
    <!-- SUFEE STYLE -->
    <!-- ===================================================== -->

    <link
        rel="stylesheet"
        href="<?= base_url(); ?>assets/sufee/assets/css/style.css"
    >


    <!-- ===================================================== -->
    <!-- GOOGLE FONT -->
    <!-- ===================================================== -->

    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700,800"
        rel="stylesheet"
        type="text/css"
    >


    <!-- ===================================================== -->
    <!-- CUSTOM CSS -->
    <!-- ===================================================== -->

    <style>

        /*
        |--------------------------------------------------------------------------
        | DATATABLE SEARCH
        |--------------------------------------------------------------------------
        */

        #dataTable_filter input {
            margin-left: -17px;
        }


        /*
        |--------------------------------------------------------------------------
        | LOGO SIDEBAR
        |--------------------------------------------------------------------------
        */

        .sidebar-brand-logo {
            height: 35px;
            width: 35px;
            object-fit: contain;
            margin-right: 8px;
            vertical-align: middle;
        }


        /*
        |--------------------------------------------------------------------------
        | HEADER LOGO
        |--------------------------------------------------------------------------
        */

        .header-shop-logo {
            height: 40px;
            width: 40px;
            object-fit: contain;
            margin-right: 10px;
        }


        /*
        |--------------------------------------------------------------------------
        | NAMA BENGKEL HEADER
        |--------------------------------------------------------------------------
        */

        .header-shop-name {
            font-size: 18px;
            font-weight: 600;
            vertical-align: middle;
        }

    </style>

</head>


<body
    <?php
    if ($authPage) {
        echo " class='bg-dark'";
    }
    ?>
>


<!-- ========================================================= -->
<!-- JAVASCRIPT -->
<!-- ========================================================= -->

<script
    src="<?= base_url(); ?>assets/jquery.js"
></script>


<script
    src="<?= base_url(); ?>assets/sufee/vendors/popper.js/dist/umd/popper.min.js"
></script>


<script
    src="<?= base_url(); ?>assets/sufee/vendors/bootstrap/dist/js/bootstrap.min.js"
></script>


<script
    src="<?= base_url(); ?>assets/sufee/vendors/datatables.net/js/jquery.dataTables.min.js"
></script>


<script
    src="<?= base_url(); ?>assets/sufee/vendors/datatables.net-bs4/js/dataTables.bootstrap4.min.js"
></script>


<script
    src="<?= base_url(); ?>assets/sweetalert2/sweetalert2.all.min.js"
></script>


<script
    src="<?= base_url(); ?>assets/daterangepicker/js/datepicker-full.min.js"
></script>


<script
    src="<?= base_url(); ?>assets/sufee/vendors/chart.js/dist/Chart.min.js"
></script>


<script
    src="<?= base_url(); ?>assets/dropify/js/dropify.min.js"
></script>


<script>

    paceOptions = {

        restartOnRequestAfter: 5,

        ajax: {

            trackMethods: [
                'GET',
                'POST',
                'PUT',
                'DELETE',
                'REMOVE'
            ]

        }

    };

</script>


<script
    src="<?= base_url(); ?>assets/pace.min.js"
></script>


<?php

if (!$authPage) {

?>


<!-- ========================================================= -->
<!-- LEFT PANEL / SIDEBAR -->
<!-- ========================================================= -->

<aside
    id="left-panel"
    class="left-panel"
>

    <nav
        class="navbar navbar-expand-sm navbar-default"
    >


        <!-- ================================================= -->
        <!-- SIDEBAR HEADER -->
        <!-- ================================================= -->

        <div class="navbar-header">


            <button
                class="navbar-toggler"
                type="button"
                data-toggle="collapse"
                data-target="#main-menu"
                aria-controls="main-menu"
                aria-expanded="false"
                aria-label="Toggle navigation"
            >

                <i class="fa fa-bars"></i>

            </button>


            <!-- LOGO + NAMA BENGKEL -->

            <a
                class="navbar-brand"
                href="<?= base_url(); ?>"
                style="
                    display:flex;
                    align-items:center;
                "
            >

                <img
                    class="sidebar-brand-logo"
                    src="<?= base_url('img/logo.png'); ?>?v=<?= time(); ?>"
                    alt="Logo Bengkel"
                >


                <span>
                    <?= $this->shop_info->get_shop_name(); ?>
                </span>

            </a>


            <!-- LOGO SAAT SIDEBAR DIKECILKAN -->

            <a
                class="navbar-brand hidden"
                href="<?= base_url(); ?>"
            >

                <img
                    class="sidebar-brand-logo"
                    src="<?= base_url('img/logo.png'); ?>?v=<?= time(); ?>"
                    alt="Logo"
                >

            </a>


        </div>


        <!-- ================================================= -->
        <!-- MAIN MENU -->
        <!-- ================================================= -->

        <div
            id="main-menu"
            class="main-menu collapse navbar-collapse"
        >

            <ul class="nav navbar-nav">


                <!-- ================================================= -->
                <!-- DASHBOARD -->
                <!-- ================================================= -->

                <li>

                    <a
                        href="<?= base_url("dashboard"); ?>"
                    >

                        <i
                            class="menu-icon fa fa-dashboard"
                        ></i>

                        Dashboard

                    </a>

                </li>


                <!-- ================================================= -->
                <!-- MANAGEMENT -->
                <!-- ================================================= -->

                <h3 class="menu-title">
                    MANAGEMENT
                </h3>


                <!-- DATA SPAREPART -->

                <li>

                    <a
                        href="<?= base_url("sparepart"); ?>"
                    >

                        <i
                            class="menu-icon fa fa-archive"
                        ></i>

                        Data Sparepart

                    </a>

                </li>


                <!-- BARANG MASUK -->

                <li>

                    <a
                        href="<?= base_url("stock_in"); ?>"
                    >

                        <i
                            class="menu-icon fa fa-sign-in"
                        ></i>

                        Barang Masuk

                    </a>

                </li>


                <!-- BARANG KELUAR -->

                <li>

                    <a
                        href="<?= base_url("stock_out"); ?>"
                    >

                        <i
                            class="menu-icon fa fa-sign-out"
                        ></i>

                        Barang Keluar

                    </a>

                </li>


                <!-- ================================================= -->
                <!-- LAPORAN -->
                <!-- ================================================= -->

                <h3 class="menu-title">
                    LAPORAN
                </h3>


                <!-- RIWAYAT STOK -->

                <li>

                    <a
                        href="<?= base_url('stock_history'); ?>"
                    >

                        <i
                            class="menu-icon fa fa-history"
                        ></i>

                        Riwayat Stok

                    </a>

                </li>


            </ul>

        </div>


    </nav>

</aside>


<!-- ========================================================= -->
<!-- RIGHT PANEL -->
<!-- ========================================================= -->

<div
    id="right-panel"
    class="right-panel"
>


    <!-- ===================================================== -->
    <!-- HEADER -->
    <!-- ===================================================== -->

    <header
        id="header"
        class="header"
    >

        <div class="header-menu">


            <!-- ================================================= -->
            <!-- HEADER KIRI -->
            <!-- ================================================= -->

            <div class="col-sm-7">

                <div
                    class="header-left"
                    style="
                        display:flex;
                        align-items:center;
                        height:50px;
                    "
                >

                    <!-- LOGO BENGKEL -->

                    <img
                        class="header-shop-logo"
                        src="<?= base_url('img/logo.png'); ?>?v=<?= time(); ?>"
                        alt="Logo Bengkel"
                    >


                    <!-- NAMA BENGKEL -->

                    <span
                        class="header-shop-name"
                    >

                        <?= $this->shop_info->get_shop_name(); ?>

                    </span>


                </div>

            </div>


            <!-- ================================================= -->
            <!-- HEADER KANAN -->
            <!-- ================================================= -->

            <div class="col-sm-5">

                <div
                    class="user-area dropdown float-right"
                >


                    <!-- ================================================= -->
                    <!-- FOTO PROFIL ADMIN -->
                    <!-- ================================================= -->

                    <a
                        href="#"
                        class="dropdown-toggle"
                        data-toggle="dropdown"
                        aria-haspopup="true"
                        aria-expanded="false"
                    >

                        <img
                            class="user-avatar rounded-circle"
                            src="<?= base_url('img/admin.png'); ?>?v=<?= time(); ?>"
                            alt="Foto Admin"
                        >

                    </a>


                    <!-- ================================================= -->
                    <!-- USER MENU -->
                    <!-- ================================================= -->

                    <div
                        class="user-menu dropdown-menu"
                    >


                        <!-- GANTI PASSWORD -->

                        <a
                            class="nav-link"
                            href="<?= base_url("setting/change_password"); ?>"
                        >

                            <i
                                class="fa fa-key"
                            ></i>

                            Ganti Password

                        </a>


                        <!-- PENGATURAN -->

                        <a
                            class="nav-link"
                            href="<?= base_url("setting/shop_info"); ?>"
                        >

                            <i
                                class="fa fa-cog"
                            ></i>

                            Pengaturan

                        </a>


                        <!-- LOGOUT -->

                        <a
                            class="nav-link"
                            href="<?= base_url("auth/logout"); ?>"
                        >

                            <i
                                class="fa fa-power-off"
                            ></i>

                            Logout

                        </a>


                    </div>


                </div>

            </div>


        </div>

    </header>


    <!-- ===================================================== -->
    <!-- /HEADER -->
    <!-- ===================================================== -->


<?php

}

?>