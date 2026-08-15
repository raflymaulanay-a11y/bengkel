<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller
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
        // LOAD USER MODEL
        // =====================================================

        $this->load->model("user_model");

        // =====================================================
        // DATA ADMIN
        // =====================================================

        $this->dataAdmin = $this->user_model
            ->get([
                "id" => $this->session->auth['id']
            ])
            ->row();
    }


    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        // =====================================================
        // TOTAL KATEGORI
        // =====================================================

        $totalKategori = $this->db
            ->count_all("categories");


        // =====================================================
        // TOTAL PRODUK
        // =====================================================

        $totalProduk = $this->db
            ->count_all("category_products");


        // =====================================================
        // TOTAL STOK
        // =====================================================

        $queryTotalStok = $this->db
            ->select("COALESCE(SUM(stok), 0) AS total_stok")
            ->get("category_products")
            ->row();

        $totalStok = (int) $queryTotalStok->total_stok;


        // =====================================================
        // BARANG MASUK HARI INI
        // =====================================================

        $queryMasukHariIni = $this->db
            ->select("COALESCE(SUM(jumlah), 0) AS total_masuk")
            ->where(
                "tanggal",
                date("Y-m-d")
            )
            ->where(
                "jenis",
                "masuk"
            )
            ->get("stock_transactions")
            ->row();

        $barangMasukHariIni =
            (int) $queryMasukHariIni->total_masuk;


        // =====================================================
        // BARANG KELUAR HARI INI
        // =====================================================

        $queryKeluarHariIni = $this->db
            ->select("COALESCE(SUM(jumlah), 0) AS total_keluar")
            ->where(
                "tanggal",
                date("Y-m-d")
            )
            ->where(
                "jenis",
                "keluar"
            )
            ->get("stock_transactions")
            ->row();

        $barangKeluarHariIni =
            (int) $queryKeluarHariIni->total_keluar;


        // =====================================================
        // STOK MENIPIS
        // =====================================================
        // Batas stok menipis = 5 atau kurang
        // =====================================================

        $stokMenipis = $this->db
            ->select("
                category_products.id,
                category_products.nama_produk,
                category_products.stok,
                categories.nama_kategori
            ")
            ->from("category_products")
            ->join(
                "categories",
                "categories.id = category_products.category_id",
                "left"
            )
            ->where(
                "category_products.stok <=",
                5
            )
            ->order_by(
                "category_products.stok",
                "ASC"
            )
            ->order_by(
                "category_products.nama_produk",
                "ASC"
            )
            ->get()
            ->result();


        // =====================================================
        // TRANSAKSI TERBARU
        // =====================================================

        $transaksiTerbaru = $this->db
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
            )
            ->order_by(
                "stock_transactions.tanggal",
                "DESC"
            )
            ->order_by(
                "stock_transactions.id",
                "DESC"
            )
            ->limit(5)
            ->get()
            ->result();


        // =====================================================
        // GRAFIK 6 BULAN TERAKHIR
        // =====================================================

        $chartLabels = [];
        $chartMasuk = [];
        $chartKeluar = [];


        for ($i = 5; $i >= 0; $i--) {

            $bulan = date(
                "Y-m",
                strtotime("-" . $i . " months")
            );

            $label = date(
                "M Y",
                strtotime("-" . $i . " months")
            );

            $chartLabels[] = $label;


            // -------------------------------------------------
            // BARANG MASUK
            // -------------------------------------------------

            $masuk = $this->db
                ->select(
                    "COALESCE(SUM(jumlah), 0) AS total"
                )
                ->where(
                    "jenis",
                    "masuk"
                )
                ->where(
                    "DATE_FORMAT(tanggal, '%Y-%m') =",
                    $bulan
                )
                ->get("stock_transactions")
                ->row();

            $chartMasuk[] =
                (int) $masuk->total;


            // -------------------------------------------------
            // BARANG KELUAR
            // -------------------------------------------------

            $keluar = $this->db
                ->select(
                    "COALESCE(SUM(jumlah), 0) AS total"
                )
                ->where(
                    "jenis",
                    "keluar"
                )
                ->where(
                    "DATE_FORMAT(tanggal, '%Y-%m') =",
                    $bulan
                )
                ->get("stock_transactions")
                ->row();

            $chartKeluar[] =
                (int) $keluar->total;
        }


        // =====================================================
        // KIRIM DATA KE VIEW
        // =====================================================

        $push = [

            "pageTitle" =>
                "Dashboard",

            "dataAdmin" =>
                $this->dataAdmin,

            "totalKategori" =>
                $totalKategori,

            "totalProduk" =>
                $totalProduk,

            "totalStok" =>
                $totalStok,

            "barangMasukHariIni" =>
                $barangMasukHariIni,

            "barangKeluarHariIni" =>
                $barangKeluarHariIni,

            "stokMenipis" =>
                $stokMenipis,

            "transaksiTerbaru" =>
                $transaksiTerbaru,

            "chartLabels" =>
                $chartLabels,

            "chartMasuk" =>
                $chartMasuk,

            "chartKeluar" =>
                $chartKeluar
        ];


        // =====================================================
        // LOAD VIEW
        // =====================================================

        $this->load->view(
            "header",
            $push
        );

        $this->load->view(
            "dashboard",
            $push
        );

        $this->load->view(
            "footer",
            $push
        );
    }
}