<style>

/* =====================================================
   BACKGROUND LOGIN
   ===================================================== */

html,
body {
    margin: 0;
    padding: 0;
    min-height: 100%;
}

body.bg-dark {
    min-height: 100vh !important;

    background:
        linear-gradient(
            rgba(0, 0, 0, 0.45),
            rgba(0, 0, 0, 0.45)
        ),
        url("<?= base_url('img/backgroundlogin.png'); ?>") !important;

    background-size: cover !important;
    background-position: center center !important;
    background-repeat: no-repeat !important;
    background-attachment: fixed !important;
}

/* Pastikan area login tidak menimpa background */

body.bg-dark .sufee-login {
    min-height: 100vh !important;

    background: transparent !important;

    display: flex;
    align-items: center;

    padding: 30px 0;

    position: relative;
}


/* =====================================================
   CONTAINER
   ===================================================== */

.sufee-login .container {
    position: relative;
    z-index: 2;
}


/* =====================================================
   LOGIN CONTENT
   ===================================================== */

.login-content {
    width: 100%;
    max-width: 540px;

    margin: 0 auto;
}


/* =====================================================
   LOGO + NAMA BENGKEL
   ===================================================== */

.login-logo {
    text-align: center;

    margin-bottom: 25px;
}


/* =====================================================
   LOGO BENGKEL
   ===================================================== */

.login-logo img {
    width: 90px;
    height: 90px;

    object-fit: contain;

    display: block;

    margin: 0 auto 12px auto;

    filter:
        drop-shadow(
            0 4px 8px rgba(0, 0, 0, 0.35)
        );
}


/* =====================================================
   NAMA BENGKEL
   ===================================================== */

.login-logo a {
    color: #ffffff !important;

    font-size: 30px !important;

    font-weight: 600;

    text-decoration: none;

    text-shadow:
        0 2px 5px rgba(0, 0, 0, 0.5);
}


/* =====================================================
   LOGIN FORM
   ===================================================== */

.login-form {

    background: rgba(255, 255, 255, 0.97);

    padding: 35px 30px;

    border-radius: 8px;

    box-shadow:
        0 10px 35px
        rgba(0, 0, 0, 0.35);
}


/* =====================================================
   LABEL
   ===================================================== */

.login-form label {

    font-weight: 600;

    color: #444;

    margin-bottom: 7px;
}


/* =====================================================
   INPUT
   ===================================================== */

.login-form .form-control {

    height: 45px;

    border-radius: 5px;

    border: 1px solid #ced4da;

    font-size: 15px;
}


/* =====================================================
   INPUT FOCUS
   ===================================================== */

.login-form .form-control:focus {

    border-color: #28a745;

    box-shadow:
        0 0 0 0.15rem
        rgba(40, 167, 69, 0.15);
}


/* =====================================================
   BUTTON LOGIN
   ===================================================== */

.login-form .btn-success {

    width: 100%;

    height: 45px;

    font-weight: 600;

    border-radius: 5px;

    border: none;

    transition: 0.2s;
}


/* =====================================================
   BUTTON HOVER
   ===================================================== */

.login-form .btn-success:hover {

    transform: translateY(-1px);

    box-shadow:
        0 5px 12px
        rgba(40, 167, 69, 0.30);
}


/* =====================================================
   ALERT
   ===================================================== */

.login-form .alert {

    border-radius: 5px;

    margin-bottom: 20px;
}


/* =====================================================
   FOOTER LOGIN
   ===================================================== */

.login-form p {

    color: #777;

    margin-bottom: 0;

    text-align: center;
}

.login-form p a {

    color: #007bff;

    text-decoration: none;
}


/* =====================================================
   MOBILE
   ===================================================== */

@media (max-width: 576px) {

    .sufee-login {

        padding: 20px 0;
    }

    .login-content {

        padding: 0 15px;
    }

    .login-logo img {

        width: 70px;

        height: 70px;
    }

    .login-logo a {

        font-size: 24px !important;
    }

    .login-form {

        padding: 25px 20px;
    }

}

</style>


<!-- =====================================================
     LOGIN
     ===================================================== -->

<div class="sufee-login d-flex align-content-center flex-wrap">

    <div class="container">

        <div class="login-content">


            <!-- =================================================
                 LOGO + NAMA BENGKEL
                 ================================================= -->


            <!-- =================================================
                 FORM LOGIN
                 ================================================= -->

            <div class="login-form">


                <!-- =================================================
                     PESAN ERROR
                     ================================================= -->

                <?php if ($error) { ?>

                    <div class="alert alert-danger">

                        <?= $error; ?>

                    </div>

                <?php } ?>


                <!-- =================================================
                     FORM
                     ================================================= -->

                <form
                    action="<?= base_url("auth/login"); ?>"
                    method="post"
                >


                    <!-- USERNAME -->

                    <div class="form-group">

                        <label>
                            Username
                        </label>

                        <input
                            type="text"
                            name="username"
                            class="form-control"
                            placeholder="Masukkan username"
                            autocomplete="username"
                            required
                        >

                    </div>


                    <!-- PASSWORD -->

                    <div class="form-group">

                        <label>
                            Password
                        </label>

                        <input
                            type="password"
                            class="form-control"
                            name="password"
                            placeholder="Masukkan password"
                            autocomplete="current-password"
                            required
                        >

                    </div>


                    <!-- BUTTON -->

                    <button
                        type="submit"
                        class="btn btn-success btn-flat m-b-30 m-t-30"
                    >

                        <i class="fa fa-sign-in"></i>

                        Sign in

                    </button>


                </form>


                <br>


            </div>

        </div>

    </div>

</div>