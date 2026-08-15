<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Sparepart extends CI_Controller
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
    | HALAMAN UTAMA SPAREPART
    |--------------------------------------------------------------------------
    */

    public function index()
    {
        $push = [
            "pageTitle" => "Sparepart",
            "dataAdmin" => $this->dataAdmin
        ];


        $this->load->view("header", $push);
        $this->load->view("sparepart", $push);
        $this->load->view("footer", $push);
    }


    /*
    |--------------------------------------------------------------------------
    | KATEGORI
    |--------------------------------------------------------------------------
    |
    | URL:
    | /sparepart/kategori/1
    |
    | Akan diarahkan ke:
    | /kategori/index/1
    |
    */

    public function kategori($id = 0)
    {
        // ID kategori tidak ada
        if (!$id) {
            redirect(base_url("sparepart"));
            return;
        }


        // Cek kategori
        $kategori = $this->db
            ->where("id", $id)
            ->get("categories")
            ->row();


        if (!$kategori) {
            show_404();
            return;
        }


        // Arahkan ke controller Kategori
        redirect(base_url("kategori/index/" . $id));
    }


    /*
    |--------------------------------------------------------------------------
    | DATATABLE SPAREPART
    |--------------------------------------------------------------------------
    |
    | Data utama:
    | categories
    |
    | Jumlah jenis:
    | dihitung dari category_products
    |
    | Stok produk:
    | dihitung dari category_products
    |
    */

    public function json()
    {
        $this->load->model("datatables");


        /*
        |--------------------------------------------------------------------------
        | TABEL UTAMA
        |--------------------------------------------------------------------------
        */

        $this->datatables->setTable("categories");


        /*
        |--------------------------------------------------------------------------
        | SELECT
        |--------------------------------------------------------------------------
        |
        | Jumlah jenis produk dihitung dari category_products.
        |
        | Total stok juga dihitung dari category_products.
        |
        */

        $this->datatables->setSelect("
            categories.*,

            (
                SELECT COUNT(*)
                FROM category_products
                WHERE category_products.category_id = categories.id
            ) AS jumlah_produk,

            (
                SELECT COALESCE(SUM(category_products.stok), 0)
                FROM category_products
                WHERE category_products.category_id = categories.id
            ) AS total_stok
        ");


        /*
        |--------------------------------------------------------------------------
        | KOLOM DATATABLE
        |--------------------------------------------------------------------------
        */

        $this->datatables->setColumn([

            // Nomor
            '<index>',


            // Nama kategori
            '<get-nama_kategori>',


            // Jumlah produk / merek
            '<get-jumlah_produk>',


            // Aksi
            '
            <div class="text-center">

                <!-- EDIT -->
                <button
                    type="button"
                    class="btn btn-primary btn-sm btn-edit"
                    data-id="<get-id>"
                    data-name="<get-nama_kategori>"
                    title="Edit">

                    <i class="fa fa-edit"></i>

                </button>


                <!-- HAPUS -->
                <button
                    type="button"
                    class="btn btn-danger btn-sm btn-delete"
                    data-id="<get-id>"
                    data-name="<get-nama_kategori>"
                    title="Hapus">

                    <i class="fa fa-trash"></i>

                </button>


                <!-- KELOLA PRODUK -->
                <a
                    href="' . base_url("kategori/index/") . '<get-id>"
                    class="btn btn-info btn-sm"
                    title="Kelola Produk">

                    <i class="fa fa-folder"></i>

                </a>

            </div>
            '
        ]);


        /*
        |--------------------------------------------------------------------------
        | SORTING
        |--------------------------------------------------------------------------
        */

        $this->datatables->setOrdering([
            "id",
            "nama_kategori",
            "jumlah_produk",
            NULL
        ]);


        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */

        $this->datatables->setSearchField(
            "nama_kategori"
        );


        /*
        |--------------------------------------------------------------------------
        | GENERATE JSON
        |--------------------------------------------------------------------------
        */

        $this->datatables->generate();
    }


    /*
    |--------------------------------------------------------------------------
    | TAMBAH KATEGORI SPAREPART
    |--------------------------------------------------------------------------
    */

    public function insert()
    {
        $nama_kategori = trim(
            $this->input->post("name")
        );


        // Validasi nama
        if ($nama_kategori == "") {

            echo json_encode([
                "status" => FALSE,
                "msg" => "Nama sparepart wajib diisi"
            ]);

            return;
        }


        // Cek duplikat
        $cek = $this->db
            ->where(
                "nama_kategori",
                $nama_kategori
            )
            ->get("categories")
            ->row();


        if ($cek) {

            echo json_encode([
                "status" => FALSE,
                "msg" => "Sparepart tersebut sudah ada"
            ]);

            return;
        }


        // Insert kategori
        $insert = $this->db
            ->insert(
                "categories",
                [
                    "nama_kategori" => $nama_kategori
                ]
            );


        if ($insert) {

            echo json_encode([
                "status" => TRUE,
                "msg" => "Sparepart berhasil ditambahkan"
            ]);

        } else {

            $error = $this->db->error();

            echo json_encode([
                "status" => FALSE,
                "msg" =>
                    "Sparepart gagal ditambahkan: " .
                    $error["message"]
            ]);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT KATEGORI SPAREPART
    |--------------------------------------------------------------------------
    */

    public function update($id = 0)
    {
        // Validasi ID
        if (!$id) {

            echo json_encode([
                "status" => FALSE,
                "msg" => "ID sparepart tidak ditemukan"
            ]);

            return;
        }


        // Ambil nama
        $nama_kategori = trim(
            $this->input->post("name")
        );


        // Validasi nama
        if ($nama_kategori == "") {

            echo json_encode([
                "status" => FALSE,
                "msg" => "Nama sparepart wajib diisi"
            ]);

            return;
        }


        // Cek kategori
        $category = $this->db
            ->where("id", $id)
            ->get("categories")
            ->row();


        if (!$category) {

            echo json_encode([
                "status" => FALSE,
                "msg" => "Data sparepart tidak ditemukan"
            ]);

            return;
        }


        // Cek nama duplikat
        $cek = $this->db
            ->where(
                "nama_kategori",
                $nama_kategori
            )
            ->where(
                "id !=",
                $id
            )
            ->get("categories")
            ->row();


        if ($cek) {

            echo json_encode([
                "status" => FALSE,
                "msg" =>
                    "Nama sparepart tersebut sudah digunakan"
            ]);

            return;
        }


        // Update
        $this->db
            ->where("id", $id)
            ->update(
                "categories",
                [
                    "nama_kategori" => $nama_kategori
                ]
            );


        // Cek error database
        $error = $this->db->error();


        if ($error["code"] != 0) {

            echo json_encode([
                "status" => FALSE,
                "msg" =>
                    "Sparepart gagal diedit: " .
                    $error["message"]
            ]);

            return;
        }


        echo json_encode([
            "status" => TRUE,
            "msg" => "Sparepart berhasil diedit"
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | HAPUS KATEGORI SPAREPART
    |--------------------------------------------------------------------------
    */

    public function delete($id = 0)
    {
        // Validasi ID
        if (!$id) {

            echo json_encode([
                "status" => FALSE,
                "msg" => "ID sparepart tidak ditemukan"
            ]);

            return;
        }


        // Cek kategori
        $category = $this->db
            ->where("id", $id)
            ->get("categories")
            ->row();


        if (!$category) {

            echo json_encode([
                "status" => FALSE,
                "msg" => "Data sparepart tidak ditemukan"
            ]);

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | CEK APAKAH MASIH ADA PRODUK DI KATEGORI
        |--------------------------------------------------------------------------
        |
        | Sekarang pengecekan menggunakan category_products.
        |
        */

        $jumlah_produk = $this->db
            ->where("category_id", $id)
            ->count_all_results("category_products");


        if ($jumlah_produk > 0) {

            echo json_encode([
                "status" => FALSE,
                "msg" =>
                    "Kategori tidak dapat dihapus karena masih memiliki " .
                    $jumlah_produk .
                    " produk"
            ]);

            return;
        }


        /*
        |--------------------------------------------------------------------------
        | HAPUS KATEGORI
        |--------------------------------------------------------------------------
        */

        $this->db
            ->where("id", $id)
            ->delete("categories");


        // Cek error database
        $error = $this->db->error();


        if ($error["code"] != 0) {

            echo json_encode([
                "status" => FALSE,
                "msg" =>
                    "Sparepart gagal dihapus: " .
                    $error["message"]
            ]);

            return;
        }


        // Cek affected rows
        if ($this->db->affected_rows() > 0) {

            echo json_encode([
                "status" => TRUE,
                "msg" =>
                    "Sparepart berhasil dihapus"
            ]);

        } else {

            echo json_encode([
                "status" => FALSE,
                "msg" =>
                    "Sparepart gagal dihapus"
            ]);
        }
    }
}