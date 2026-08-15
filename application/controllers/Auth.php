<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // =====================================================
        // LOAD MODEL
        // =====================================================

        $this->load->model("user_model");
    }


    // =========================================================
    // INDEX
    // =========================================================

    public function index()
    {
        $this->login();
    }


    // =========================================================
    // LOGIN
    // =========================================================

    public function login()
    {
        // =====================================================
        // JIKA SUDAH LOGIN
        // =====================================================

        if (
            isset($this->session->auth) &&
            isset($this->session->auth['logged_in']) &&
            $this->session->auth['logged_in'] === TRUE
        ) {

            redirect(base_url("dashboard"));
            return;
        }


        // =====================================================
        // DATA VIEW
        // =====================================================

        $push = [
            "error"     => FALSE,
            "username"  => "",
            "pageTitle" => "Login",
            "authPage"  => TRUE
        ];


        // =====================================================
        // JIKA FORM LOGIN DIKIRIM
        // =====================================================

        if ($this->input->method() == "post") {

            // =================================================
            // AMBIL INPUT
            // =================================================

            $username = trim(
                $this->input->post("username")
            );

            $password = $this->input->post("password");


            $push["username"] = $username;


            // =================================================
            // VALIDASI INPUT
            // =================================================

            if ($username == "" || $password == "") {

                $push["error"] =
                    "Username dan password wajib diisi.";

            } else {

                // =============================================
                // CARI USER BERDASARKAN USERNAME
                // =============================================

                $query = $this->user_model->get([
                    "username" => $username
                ]);


                // =============================================
                // USERNAME TIDAK DITEMUKAN
                // =============================================

                if ($query->num_rows() == 0) {

                    $push["error"] =
                        "Username atau password yang anda masukkan salah.";

                } else {

                    // =========================================
                    // AMBIL DATA USER
                    // =========================================

                    $user = $query->row();


                    // =========================================
                    // CEK PASSWORD
                    // =========================================

                    $passwordBenar = FALSE;


                    // =========================================
                    // PASSWORD DARI DATABASE
                    // =========================================

                    $passwordDatabase = $user->password;


                    // =========================================
                    // CARA 1
                    // PASSWORD SUDAH HASH
                    // =========================================

                    if (
                        !empty($passwordDatabase) &&
                        password_verify(
                            $password,
                            $passwordDatabase
                        )
                    ) {

                        $passwordBenar = TRUE;
                    }


                    // =========================================
                    // CARA 2
                    // PASSWORD LAMA MASIH PLAINTEXT
                    // =========================================
                    //
                    // Ini hanya untuk migrasi akun lama.
                    // Jika cocok, langsung diubah menjadi hash.
                    // =========================================

                    elseif (
                        !empty($passwordDatabase) &&
                        $password === $passwordDatabase
                    ) {

                        $passwordBenar = TRUE;


                        // =====================================
                        // UBAH PASSWORD LAMA MENJADI HASH
                        // =====================================

                        $passwordHash = password_hash(
                            $password,
                            PASSWORD_BCRYPT
                        );


                        $this->user_model->set_user(
                            $user->id,
                            [
                                "password" => $passwordHash
                            ]
                        );
                    }


                    // =========================================
                    // PASSWORD SALAH
                    // =========================================

                    if (!$passwordBenar) {

                        $push["error"] =
                            "Username atau password yang anda masukkan salah.";

                    } else {

                        // =====================================
                        // PASSWORD BENAR
                        // =====================================

                        $sessionData = [
                            "logged_in" => TRUE,
                            "id"        => $user->id
                        ];


                        // =====================================
                        // SIMPAN SESSION
                        // =====================================

                        $this->session->set_userdata(
                            "auth",
                            $sessionData
                        );


                        // =====================================
                        // MASUK DASHBOARD
                        // =====================================

                        redirect(
                            base_url("dashboard")
                        );

                        return;
                    }
                }
            }
        }


        // =====================================================
        // TAMPILKAN HALAMAN LOGIN
        // =====================================================

        $this->load->view(
            "header",
            $push
        );

        $this->load->view(
            "login",
            $push
        );

        $this->load->view(
            "footer",
            $push
        );
    }


    // =========================================================
    // LOGOUT
    // =========================================================

    public function logout()
    {
        // =====================================================
        // HAPUS SESSION
        // =====================================================

        $this->session->unset_userdata("auth");


        // =====================================================
        // KEMBALI KE LOGIN
        // =====================================================

        redirect(
            base_url("auth/login")
        );
    }
}