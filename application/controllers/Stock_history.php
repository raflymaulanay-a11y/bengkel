<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock_history extends CI_Controller
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
        // LOAD USER MODEL
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
    | HALAMAN RIWAYAT STOK
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $push = [
            "pageTitle" => "Riwayat Stok",
            "dataAdmin" => $this->dataAdmin
        ];

        $this->load->view("header", $push);
        $this->load->view("stock_history", $push);
        $this->load->view("footer", $push);
    }


    /*
    |--------------------------------------------------------------------------
    | DATA RIWAYAT STOK
    |--------------------------------------------------------------------------
    |
    | Bisa menggunakan filter tanggal.
    |
    | Contoh:
    |
    | /stock_history/data
    |
    | = semua data
    |
    | /stock_history/data?tanggal=2026-08-11
    |
    | = hanya transaksi tanggal 11 Agustus 2026
    |
    |--------------------------------------------------------------------------
    */

    public function data()
    {
        // =========================================================
        // AMBIL FILTER TANGGAL
        // =========================================================

        $tanggal = $this->input->get("tanggal");


        // =========================================================
        // QUERY DATA
        // =========================================================

        $this->db
            ->select("
                stock_transactions.id,
                stock_transactions.tanggal,
                stock_transactions.jenis,
                stock_transactions.jumlah,
                stock_transactions.keterangan,
                stock_transactions.created_at,

                category_products.nama_produk,
                category_products.harga,

                categories.nama_kategori,

                users.username
            ")

            ->from("stock_transactions")


            // =====================================================
            // JOIN PRODUK
            // =====================================================

            ->join(
                "category_products",
                "category_products.id = stock_transactions.category_product_id",
                "left"
            )


            // =====================================================
            // JOIN KATEGORI
            // =====================================================

            ->join(
                "categories",
                "categories.id = category_products.category_id",
                "left"
            )


            // =====================================================
            // JOIN USER
            // =====================================================

            ->join(
                "users",
                "users.id = stock_transactions.user_id",
                "left"
            );


        // =========================================================
        // FILTER TANGGAL
        // =========================================================

        if (!empty($tanggal)) {

            $this->db->where(
                "stock_transactions.tanggal",
                $tanggal
            );
        }


        // =========================================================
        // URUTKAN DATA
        // =========================================================

        $this->db
            ->order_by(
                "stock_transactions.tanggal",
                "DESC"
            )

            ->order_by(
                "stock_transactions.id",
                "DESC"
            );


        // =========================================================
        // AMBIL DATA
        // =========================================================

        $data = $this->db
            ->get()
            ->result();


        // =========================================================
        // RESPONSE JSON
        // =========================================================

        echo json_encode([
            "status" => TRUE,
            "tanggal" => $tanggal,
            "jumlah_data" => count($data),
            "data" => $data
        ]);
    }

    /*
|--------------------------------------------------------------------------
| CETAK RIWAYAT STOK
|--------------------------------------------------------------------------
*/

    public function print()
    {
        $tanggal = $this->input->get("tanggal");


        // =====================================================
        // AMBIL DATA
        // =====================================================

        $this->db
            ->select("
            stock_transactions.id,
            stock_transactions.tanggal,
            stock_transactions.jenis,
            stock_transactions.jumlah,
            stock_transactions.keterangan,

            category_products.nama_produk,

            categories.nama_kategori
        ")

            ->from("stock_transactions")

            ->join(
                "category_products",
                "category_products.id = stock_transactions.category_product_id",
                "left"
            )

            ->join(
                "categories",
                "categories.id = category_products.category_id",
                "left"
            );


        // =====================================================
        // FILTER TANGGAL
        // =====================================================

        if ($tanggal) {

            $this->db->where(
                "stock_transactions.tanggal",
                $tanggal
            );
        }


        // =====================================================
        // URUTKAN
        // =====================================================

        $this->db
            ->order_by(
                "stock_transactions.tanggal",
                "DESC"
            )

            ->order_by(
                "stock_transactions.id",
                "DESC"
            );


        $data = $this->db
            ->get()
            ->result();


        // =====================================================
        // DATA HALAMAN
        // =====================================================

        $push = [

            "data" =>
            $data,

            "tanggal" =>
            $tanggal

        ];


        // =====================================================
        // LOAD VIEW CETAK
        // =====================================================

        $this->load->view(
            "stock_history_print",
            $push
        );
    }
}
