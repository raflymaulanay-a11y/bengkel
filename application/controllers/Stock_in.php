<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Stock_in extends CI_Controller
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
    | HALAMAN BARANG MASUK
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $push = [
            "pageTitle" => "Barang Masuk",
            "dataAdmin" => $this->dataAdmin
        ];


        $this->load->view("header", $push);
        $this->load->view("stock_in", $push);
        $this->load->view("footer", $push);
    }



    /*
    |--------------------------------------------------------------------------
    | DATA KATEGORI
    |--------------------------------------------------------------------------
    |
    | Mengambil semua kategori sparepart.
    |
    */

    public function categories()
    {
        $categories = $this->db
            ->order_by(
                "nama_kategori",
                "ASC"
            )
            ->get("categories")
            ->result();


        echo json_encode([
            "status" => TRUE,
            "data" => $categories
        ]);
    }



    /*
    |--------------------------------------------------------------------------
    | DATA PRODUK BERDASARKAN KATEGORI
    |--------------------------------------------------------------------------
    |
    | Contoh:
    |
    | /stock_in/products/1
    |
    | Maka hanya produk category_id = 1 yang ditampilkan.
    |
    */

    public function products($category_id = 0)
    {
        // Validasi kategori
        if (!$category_id) {

            echo json_encode([
                "status" => FALSE,
                "msg" => "Kategori belum dipilih",
                "data" => []
            ]);

            return;
        }


        // Cek kategori
        $category = $this->db
            ->where(
                "id",
                $category_id
            )
            ->get("categories")
            ->row();


        if (!$category) {

            echo json_encode([
                "status" => FALSE,
                "msg" => "Kategori tidak ditemukan",
                "data" => []
            ]);

            return;
        }


        // Ambil produk dari kategori tersebut
        $products = $this->db
            ->where(
                "category_id",
                $category_id
            )
            ->order_by(
                "nama_produk",
                "ASC"
            )
            ->get("category_products")
            ->result();


        echo json_encode([
            "status" => TRUE,
            "data" => $products
        ]);
    }



    /*
    |--------------------------------------------------------------------------
    | SIMPAN BARANG MASUK
    |--------------------------------------------------------------------------
    */

    public function insert()
    {
        $category_product_id = $this->input->post(
            "category_product_id"
        );


        $tanggal = $this->input->post(
            "tanggal"
        );


        $jumlah = $this->input->post(
            "jumlah"
        );


        $keterangan = trim(
            $this->input->post(
                "keterangan"
            )
        );



        /*
        |--------------------------------------------------------------------------
        | VALIDASI
        |--------------------------------------------------------------------------
        */

        if (!$category_product_id) {

            echo json_encode([
                "status" => FALSE,
                "msg" => "Produk wajib dipilih"
            ]);

            return;
        }



        if (!$tanggal) {

            echo json_encode([
                "status" => FALSE,
                "msg" => "Tanggal wajib diisi"
            ]);

            return;
        }



        if (!$jumlah || (int)$jumlah <= 0) {

            echo json_encode([
                "status" => FALSE,
                "msg" =>
                    "Jumlah barang masuk harus lebih dari 0"
            ]);

            return;
        }



        /*
        |--------------------------------------------------------------------------
        | AMBIL PRODUK
        |--------------------------------------------------------------------------
        */

        $product = $this->db
            ->where(
                "id",
                $category_product_id
            )
            ->get("category_products")
            ->row();


        if (!$product) {

            echo json_encode([
                "status" => FALSE,
                "msg" => "Produk tidak ditemukan"
            ]);

            return;
        }



        $jumlah = (int)$jumlah;

        $stok_lama = (int)$product->stok;



        /*
        |--------------------------------------------------------------------------
        | HITUNG STOK BARU
        |--------------------------------------------------------------------------
        */

        $stok_baru = $stok_lama + $jumlah;



        /*
        |--------------------------------------------------------------------------
        | TRANSAKSI DATABASE
        |--------------------------------------------------------------------------
        */

        $this->db->trans_start();



        /*
        | UPDATE STOK
        */

        $this->db
            ->where(
                "id",
                $category_product_id
            )
            ->update(
                "category_products",
                [
                    "stok" => $stok_baru
                ]
            );



        /*
        | SIMPAN RIWAYAT
        */

        $this->db->insert(
            "stock_transactions",
            [
                "category_product_id" =>
                    $category_product_id,

                "tanggal" =>
                    $tanggal,

                "jenis" =>
                    "masuk",

                "jumlah" =>
                    $jumlah,

                "keterangan" =>
                    $keterangan,

                "user_id" =>
                    $this->session->auth['id']
            ]
        );



        $this->db->trans_complete();



        /*
        |--------------------------------------------------------------------------
        | CEK TRANSAKSI
        |--------------------------------------------------------------------------
        */

        if ($this->db->trans_status() === FALSE) {

            echo json_encode([
                "status" => FALSE,
                "msg" =>
                    "Barang masuk gagal disimpan"
            ]);

            return;
        }



        /*
        |--------------------------------------------------------------------------
        | BERHASIL
        |--------------------------------------------------------------------------
        */

        echo json_encode([
            "status" => TRUE,
            "msg" =>
                "Barang masuk berhasil disimpan",

            "stok_lama" =>
                $stok_lama,

            "jumlah" =>
                $jumlah,

            "stok_baru" =>
                $stok_baru
        ]);
    }
}