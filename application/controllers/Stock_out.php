<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_out extends CI_Controller
{
    private $dataAdmin;

    public function __construct()
    {
        parent::__construct();

        // =========================================================
        // CEK LOGIN
        // =========================================================
        if (!$this->session->auth) {
            redirect(base_url("auth/login"));
        }

        // =========================================================
        // LOAD MODEL USER
        // =========================================================
        $this->load->model("user_model");

        // =========================================================
        // DATA ADMIN
        // =========================================================
        $this->dataAdmin = $this->user_model
            ->get([
                "id" => $this->session->auth['id']
            ])
            ->row();
    }


    /*
    |--------------------------------------------------------------------------
    | HALAMAN BARANG KELUAR
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $push = [
            "pageTitle" => "Barang Keluar",
            "dataAdmin" => $this->dataAdmin
        ];

        $this->load->view("header", $push);
        $this->load->view("stock_out", $push);
        $this->load->view("footer", $push);
    }


    /*
    |--------------------------------------------------------------------------
    | DATA KATEGORI
    |--------------------------------------------------------------------------
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
    | /stock_out/products/1
    |
    | Maka hanya produk category_id = 1
    | yang akan ditampilkan.
    |
    */

    public function products($category_id = 0)
    {
        // =========================================================
        // VALIDASI KATEGORI
        // =========================================================

        if (!$category_id) {

            echo json_encode([
                "status" => FALSE,
                "msg" => "Kategori belum dipilih",
                "data" => []
            ]);

            return;
        }


        // =========================================================
        // CEK KATEGORI
        // =========================================================

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


        // =========================================================
        // AMBIL PRODUK DALAM KATEGORI
        // =========================================================

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
    | SIMPAN BARANG KELUAR
    |--------------------------------------------------------------------------
    */

    public function insert()
    {
        // =========================================================
        // INPUT
        // =========================================================

        $category_product_id =
            $this->input->post(
                "category_product_id"
            );

        $tanggal =
            $this->input->post(
                "tanggal"
            );

        $jumlah =
            $this->input->post(
                "jumlah"
            );

        $keterangan =
            trim(
                $this->input->post(
                    "keterangan"
                )
            );


        // =========================================================
        // VALIDASI PRODUK
        // =========================================================

        if (!$category_product_id) {

            echo json_encode([
                "status" => FALSE,
                "msg" => "Produk wajib dipilih"
            ]);

            return;
        }


        // =========================================================
        // VALIDASI TANGGAL
        // =========================================================

        if (!$tanggal) {

            echo json_encode([
                "status" => FALSE,
                "msg" => "Tanggal wajib diisi"
            ]);

            return;
        }


        // =========================================================
        // VALIDASI JUMLAH
        // =========================================================

        if (
            !$jumlah ||
            (int) $jumlah <= 0
        ) {

            echo json_encode([
                "status" => FALSE,
                "msg" =>
                    "Jumlah barang keluar harus lebih dari 0"
            ]);

            return;
        }


        // =========================================================
        // AMBIL PRODUK
        // =========================================================

        $product = $this->db
            ->where(
                "id",
                $category_product_id
            )
            ->get("category_products")
            ->row();


        // =========================================================
        // PRODUK TIDAK DITEMUKAN
        // =========================================================

        if (!$product) {

            echo json_encode([
                "status" => FALSE,
                "msg" => "Produk tidak ditemukan"
            ]);

            return;
        }


        // =========================================================
        // KONVERSI JUMLAH
        // =========================================================

        $jumlah =
            (int) $jumlah;


        $stok_lama =
            (int) $product->stok;


        // =========================================================
        // VALIDASI STOK
        // =========================================================

        if ($jumlah > $stok_lama) {

            echo json_encode([
                "status" => FALSE,
                "msg" =>
                    "Jumlah barang keluar tidak boleh melebihi stok yang tersedia",
                "stok" =>
                    $stok_lama
            ]);

            return;
        }


        // =========================================================
        // HITUNG STOK BARU
        // =========================================================

        $stok_baru =
            $stok_lama - $jumlah;


        // =========================================================
        // TRANSAKSI DATABASE
        // =========================================================

        $this->db->trans_start();


        // =========================================================
        // UPDATE STOK
        // =========================================================

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


        // =========================================================
        // SIMPAN RIWAYAT TRANSAKSI
        // =========================================================

        $this->db->insert(
            "stock_transactions",
            [

                "category_product_id" =>
                    $category_product_id,

                "tanggal" =>
                    $tanggal,

                "jenis" =>
                    "keluar",

                "jumlah" =>
                    $jumlah,

                "keterangan" =>
                    $keterangan,

                "user_id" =>
                    $this->session->auth['id']
            ]
        );


        // =========================================================
        // SELESAIKAN TRANSAKSI
        // =========================================================

        $this->db->trans_complete();


        // =========================================================
        // CEK TRANSAKSI
        // =========================================================

        if (
            $this->db->trans_status() === FALSE
        ) {

            echo json_encode([
                "status" => FALSE,
                "msg" =>
                    "Barang keluar gagal disimpan"
            ]);

            return;
        }


        // =========================================================
        // BERHASIL
        // =========================================================

        echo json_encode([
            "status" => TRUE,

            "msg" =>
                "Barang keluar berhasil disimpan",

            "stok_lama" =>
                $stok_lama,

            "jumlah" =>
                $jumlah,

            "stok_baru" =>
                $stok_baru
        ]);
    }
}