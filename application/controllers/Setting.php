<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Setting extends CI_Controller
{
    private $dataAdmin;


    public function __construct()
    {
        parent::__construct();


        // =====================================================
        // CEK LOGIN
        // =====================================================

        if (!$this->session->auth) {
            redirect(base_url("auth/login"));
        }


        // =====================================================
        // LOAD MODEL USER
        // =====================================================

        $this->load->model("user_model");


        // =====================================================
        // AMBIL DATA ADMIN
        // =====================================================

        $this->dataAdmin = $this->user_model
            ->get([
                "id" => $this->session->auth['id']
            ])
            ->row();
    }



    // =========================================================
    // HALAMAN PENGATURAN
    // =========================================================

    public function shop_info()
    {
        $push = [

            "pageTitle" => "Pengaturan",

            "dataAdmin" => $this->dataAdmin

        ];


        $this->load->view(
            "header",
            $push
        );


        $this->load->view(
            "setting",
            $push
        );


        $this->load->view(
            "footer",
            $push
        );
    }



    // =========================================================
    // HALAMAN GANTI PASSWORD
    // =========================================================

    public function change_password()
    {
        $push = [

            "pageTitle" => "Ganti Password",

            "dataAdmin" => $this->dataAdmin

        ];


        $this->load->view(
            "header",
            $push
        );


        $this->load->view(
            "change_password",
            $push
        );


        $this->load->view(
            "footer",
            $push
        );
    }



    // =========================================================
    // SIMPAN PENGATURAN
    // =========================================================

    public function save_info()
    {

        // =====================================================
        // AMBIL NAMA BENGKEL
        // =====================================================

        $name = trim(
            $this->input->post("name")
        );


        // =====================================================
        // AMBIL ALAMAT
        // =====================================================

        $address = trim(
            $this->input->post("address")
        );



        // =====================================================
        // VALIDASI NAMA
        // =====================================================

        if (!$name) {

            echo json_encode([

                "status" => FALSE,

                "msg" =>
                    "Nama bengkel wajib diisi."

            ]);

            return;
        }



        // =====================================================
        // VALIDASI ALAMAT
        // =====================================================

        if (!$address) {

            echo json_encode([

                "status" => FALSE,

                "msg" =>
                    "Alamat bengkel wajib diisi."

            ]);

            return;
        }



        // =====================================================
        // LOKASI FOLDER IMG
        // =====================================================

        $upload_path =
            FCPATH . "img/";



        // =====================================================
        // CEK FOLDER IMG
        // =====================================================

        if (!is_dir($upload_path)) {

            echo json_encode([

                "status" => FALSE,

                "msg" =>
                    "Folder img tidak ditemukan."

            ]);

            return;
        }



        // =====================================================
        // SIMPAN NAMA DAN ALAMAT
        // =====================================================

        $this->user_model->set_shop([

            "name" =>
                $name,

            "address" =>
                $address

        ]);



        // =====================================================
        // LOAD LIBRARY UPLOAD
        // =====================================================

        $this->load->library("upload");



        // =====================================================
        // UPLOAD LOGO BENGKEL
        // =====================================================

        if (
            isset($_FILES["userfile"]) &&
            !empty($_FILES["userfile"]["name"])
        ) {


            $config = [];


            $config["upload_path"] =
                $upload_path;


            $config["allowed_types"] =
                "png|jpg|jpeg";


            $config["max_size"] =
                2048;


            $config["file_name"] =
                "logo.png";


            $config["overwrite"] =
                TRUE;


            $config["remove_spaces"] =
                TRUE;



            // -------------------------------------------------
            // INITIALIZE UPLOAD
            // -------------------------------------------------

            $this->upload->initialize(
                $config
            );



            // -------------------------------------------------
            // UPLOAD LOGO
            // -------------------------------------------------

            if (
                !$this->upload->do_upload(
                    "userfile"
                )
            ) {

                echo json_encode([

                    "status" => FALSE,

                    "msg" =>
                        "Logo gagal diupload: " .

                        strip_tags(
                            $this->upload->display_errors(
                                "",
                                ""
                            )
                        )

                ]);

                return;
            }
        }



        // =====================================================
        // UPLOAD FOTO ADMIN
        // =====================================================

        if (
            isset($_FILES["adminfile"]) &&
            !empty($_FILES["adminfile"]["name"])
        ) {


            $config = [];


            $config["upload_path"] =
                $upload_path;


            $config["allowed_types"] =
                "png|jpg|jpeg";


            $config["max_size"] =
                2048;


            $config["file_name"] =
                "admin.png";


            $config["overwrite"] =
                TRUE;


            $config["remove_spaces"] =
                TRUE;



            // -------------------------------------------------
            // INITIALIZE ULANG UPLOAD
            // -------------------------------------------------

            $this->upload->initialize(
                $config
            );



            // -------------------------------------------------
            // UPLOAD FOTO ADMIN
            // -------------------------------------------------

            if (
                !$this->upload->do_upload(
                    "adminfile"
                )
            ) {

                echo json_encode([

                    "status" => FALSE,

                    "msg" =>
                        "Foto admin gagal diupload: " .

                        strip_tags(
                            $this->upload->display_errors(
                                "",
                                ""
                            )
                        )

                ]);

                return;
            }
        }



        // =====================================================
        // BERHASIL
        // =====================================================

        echo json_encode([

            "status" => TRUE,

            "msg" =>
                "Pengaturan bengkel berhasil disimpan."

        ]);
    }



    // =========================================================
    // SIMPAN PASSWORD
    // =========================================================

    public function save_password()
    {

        $id =
            $this->input->post("id");


        $oldpw =
            $this->input->post("oldpw");


        $newpw1 =
            $this->input->post("newpw1");


        $newpw2 =
            $this->input->post("newpw2");



        // =====================================================
        // CEK PASSWORD LAMA
        // =====================================================

        if (
            !password_verify(
                $oldpw,
                $this->dataAdmin->password
            )
        ) {

            $response = [

                "status" => FALSE,

                "msg" =>
                    "Password lama yang anda masukkan salah"

            ];

        } else {


            // =================================================
            // CEK PASSWORD BARU
            // =================================================

            if (
                !$newpw1 ||
                !$newpw2
            ) {

                $response = [

                    "status" => FALSE,

                    "msg" =>
                        "Masukkan password baru"

                ];

            } else {


                // =============================================
                // CEK KONFIRMASI PASSWORD
                // =============================================

                if (
                    $newpw1 != $newpw2
                ) {

                    $response = [

                        "status" => FALSE,

                        "msg" =>
                            "Ulangi password baru dengan benar"

                    ];

                } else {


                    // =========================================
                    // UPDATE PASSWORD
                    // =========================================

                    $this->user_model->set_user(

                        $this->dataAdmin->id,

                        [

                            "password" =>

                                password_hash(

                                    $newpw1,

                                    PASSWORD_BCRYPT

                                )

                        ]

                    );


                    $response = [

                        "status" => TRUE,

                        "msg" =>
                            "Password telah diganti"

                    ];
                }
            }
        }



        // =====================================================
        // RESPONSE
        // =====================================================

        echo json_encode(
            $response
        );
    }
}