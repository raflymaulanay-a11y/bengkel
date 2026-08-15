<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends CI_Controller
{
    private $dataAdmin;

    public function __construct()
    {
        parent::__construct();

        // Cek login
        if (!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        // Load model user
        $this->load->model("user_model");

        // Data admin
        $this->dataAdmin = $this->user_model
            ->get([
                "id" => $this->session->auth['id']
            ])
            ->row();
    }


    /*
    |--------------------------------------------------------------------------
    | HALAMAN PRODUK DALAM KATEGORI
    |--------------------------------------------------------------------------
    */

    public function index($id = 0)
    {
        // Jika ID kategori tidak ada
        if (!$id) {
            redirect(base_url("sparepart"));
            return;
        }

        // Ambil kategori
        $category = $this->db
            ->where("id", $id)
            ->get("categories")
            ->row();

        // Kategori tidak ditemukan
        if (!$category) {
            show_404();
            return;
        }

        /*
        |--------------------------------------------------------------------------
        | AMBIL PRODUK DARI CATEGORY_PRODUCTS
        |--------------------------------------------------------------------------
        |
        | Semua produk sparepart berada di sini.
        | Stok juga berasal dari tabel ini.
        |
        */

        $products = $this->db
            ->where("category_id", $id)
            ->order_by("id", "DESC")
            ->get("category_products")
            ->result();


        // Data yang dikirim ke view
        $push = [
            "pageTitle" => "Produk - " . $category->nama_kategori,
            "dataAdmin" => $this->dataAdmin,
            "category" => $category,
            "products" => $products
        ];


        // Header
        $this->load->view("header", $push);

        // View kategori
        $this->load->view("kategori", $push);

        // Footer
        $this->load->view("footer", $push);
    }


    /*
    |--------------------------------------------------------------------------
    | TAMBAH PRODUK
    |--------------------------------------------------------------------------
    */

    public function kategori_insert($kategori_id = 0)
    {
        // Validasi kategori
        if (!$kategori_id) {
            echo json_encode([
                "status" => FALSE,
                "msg" => "Kategori tidak ditemukan"
            ]);

            return;
        }


        // Cek kategori
        $category = $this->db
            ->where("id", $kategori_id)
            ->get("categories")
            ->row();


        if (!$category) {
            echo json_encode([
                "status" => FALSE,
                "msg" => "Kategori tidak ditemukan"
            ]);

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | INPUT
        |--------------------------------------------------------------------------
        */

        $nama_produk = trim(
            $this->input->post("nama_produk")
        );

        $harga = $this->input->post("harga");

        $stok = $this->input->post("stok");


        /*
        |--------------------------------------------------------------------------
        | VALIDASI NAMA
        |--------------------------------------------------------------------------
        */

        if ($nama_produk == "") {
            echo json_encode([
                "status" => FALSE,
                "msg" => "Nama produk wajib diisi"
            ]);

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | HARGA
        |--------------------------------------------------------------------------
        */

        if ($harga === "" || $harga === NULL) {
            $harga = 0;
        }


        /*
        |--------------------------------------------------------------------------
        | STOK AWAL
        |--------------------------------------------------------------------------
        */

        if ($stok === "" || $stok === NULL) {
            $stok = 0;
        }


        /*
        |--------------------------------------------------------------------------
        | CEK PRODUK DUPLIKAT DALAM KATEGORI
        |--------------------------------------------------------------------------
        */

        $cek = $this->db
            ->where("category_id", $kategori_id)
            ->where("nama_produk", $nama_produk)
            ->get("category_products")
            ->row();


        if ($cek) {
            echo json_encode([
                "status" => FALSE,
                "msg" => "Produk dengan nama tersebut sudah ada dalam kategori"
            ]);

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | DATA CATEGORY_PRODUCTS
        |--------------------------------------------------------------------------
        */

        $data = [
            "category_id" => (int) $kategori_id,
            "nama_produk" => $nama_produk,
            "harga" => (int) $harga,
            "stok" => (int) $stok
        ];


        /*
        |--------------------------------------------------------------------------
        | INSERT
        |--------------------------------------------------------------------------
        */

        $insert = $this->db
            ->insert(
                "category_products",
                $data
            );


        if ($insert) {

            echo json_encode([
                "status" => TRUE,
                "msg" => "Produk berhasil ditambahkan"
            ]);

        } else {

            $error = $this->db->error();

            echo json_encode([
                "status" => FALSE,
                "msg" =>
                    "Produk gagal ditambahkan: " .
                    $error["message"]
            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT PRODUK
    |--------------------------------------------------------------------------
    */

    public function kategori_update($id = 0)
    {
        // Validasi ID
        if (!$id) {
            echo json_encode([
                "status" => FALSE,
                "msg" => "ID produk tidak ditemukan"
            ]);

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | INPUT
        |--------------------------------------------------------------------------
        */

        $nama_produk = trim(
            $this->input->post("nama_produk")
        );

        $harga = $this->input->post("harga");


        /*
        |--------------------------------------------------------------------------
        | VALIDASI NAMA
        |--------------------------------------------------------------------------
        */

        if ($nama_produk == "") {
            echo json_encode([
                "status" => FALSE,
                "msg" => "Nama produk wajib diisi"
            ]);

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | HARGA
        |--------------------------------------------------------------------------
        */

        if ($harga === "" || $harga === NULL) {
            $harga = 0;
        }


        /*
        |--------------------------------------------------------------------------
        | CEK PRODUK
        |--------------------------------------------------------------------------
        */

        $product = $this->db
            ->where("id", $id)
            ->get("category_products")
            ->row();


        if (!$product) {
            echo json_encode([
                "status" => FALSE,
                "msg" => "Produk tidak ditemukan"
            ]);

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        |
        | STOK TIDAK DIUBAH DI SINI.
        |
        | Stok hanya berubah melalui:
        | 1. Barang Masuk
        | 2. Barang Keluar
        |
        */

        $data = [
            "nama_produk" => $nama_produk,
            "harga" => (int) $harga
        ];


        $this->db
            ->where("id", $id)
            ->update(
                "category_products",
                $data
            );


        /*
        |--------------------------------------------------------------------------
        | CEK ERROR DATABASE
        |--------------------------------------------------------------------------
        */

        $error = $this->db->error();


        if ($error["code"] != 0) {

            echo json_encode([
                "status" => FALSE,
                "msg" =>
                    "Produk gagal diedit: " .
                    $error["message"]
            ]);

            return;
        }


        echo json_encode([
            "status" => TRUE,
            "msg" => "Produk berhasil diedit"
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS PRODUK
    |--------------------------------------------------------------------------
    */

    public function kategori_delete($id = 0)
    {
        // Validasi ID
        if (!$id) {
            echo json_encode([
                "status" => FALSE,
                "msg" => "ID produk tidak ditemukan"
            ]);

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | CEK PRODUK
        |--------------------------------------------------------------------------
        */

        $product = $this->db
            ->where("id", $id)
            ->get("category_products")
            ->row();


        if (!$product) {
            echo json_encode([
                "status" => FALSE,
                "msg" => "Produk tidak ditemukan"
            ]);

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | HAPUS PRODUK
        |--------------------------------------------------------------------------
        */

        $this->db
            ->where("id", $id)
            ->delete("category_products");


        /*
        |--------------------------------------------------------------------------
        | HASIL
        |--------------------------------------------------------------------------
        */

        if ($this->db->affected_rows() > 0) {

            echo json_encode([
                "status" => TRUE,
                "msg" => "Produk berhasil dihapus"
            ]);

        } else {

            $error = $this->db->error();

            echo json_encode([
                "status" => FALSE,
                "msg" =>
                    "Produk gagal dihapus: " .
                    $error["message"]
            ]);
        }
    }
}