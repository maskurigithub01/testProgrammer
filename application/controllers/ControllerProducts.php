<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ControllerProducts extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('ModelProducts');
    }

    public function fetchData() {
        // cek apakah sudah ada data di database jika ada maka hapus semua data
        $this->ModelProducts->deleteAllProducts();

        date_default_timezone_set('Asia/Jakarta');

        // Ambil tanggal dan waktu sekarang
        $currentDateTime = new DateTime();

        // Menambahkan 1 jam ke waktu sekarang
        $currentDateTime->modify('+1 hour');

        // Format username
        $day = $currentDateTime->format('d'); // Tanggal 2 digit
        $month = $currentDateTime->format('m'); // Bulan 2 digit
        $hour = $currentDateTime->format('H'); // Jam 24 jam format

        // Format password (md5)
        $year = $currentDateTime->format('y'); // 2 digit tahun
        $passwordString = 'bisacoding-' . $day . '-' . $month . '-' . $year;

        $username = 'tesprogrammer' . $day . $month . $year . 'C' . $hour;
        $password = md5($passwordString); // MD5 password

        echo "Username: $username\n";
        echo "Password: $passwordString\n";

        // URL endpoint API
        $url = "https://recruitment.fastprint.co.id/tes/api_tes_programmer";

        // Data yang akan dikirim
        $data = [
            'username' => $username,
            'password' => $password,
        ];

        // Inisialisasi cURL
        $ch = curl_init();

        // Set opsi cURL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/x-www-form-urlencoded',
        ]);

        // Eksekusi cURL
        $response = curl_exec($ch);

        // Periksa jika ada error
        if (curl_errno($ch)) {
            echo "cURL Error: " . curl_error($ch);
        } else {
            // Decode respons API
            $result = json_decode($response, true);

            if (isset($result['error']) && $result['error'] == 0) {
                // Jika sukses, simpan data ke database
                $this->ModelProducts->saveData($result['data']);
            } else {
                // Jika gagal, tampilkan pesan error
                echo "Gagal mengambil data: " . ($result['ket'] ?? 'Unknown error');
            }
        }

        // Tutup cURL
        curl_close($ch);
    }

    public function viewAllProducts() {
        $status = $this->input->post('status') ? $this->input->post('status') : 'semua';

        $title = 'Daftar Produk';

        // Ambil produk berdasarkan status
        if ($status == 'bisa dijual') {
            $data = [
                'title' => $title,
                'products' => $this->ModelProducts->getProductsByStatus('bisa dijual'),
            ];
        } else {
            $data = [
                'title' => $title,
                'products' => $this->ModelProducts->getAllProducts(),
            ];
        }

        // Kirim data ke view
        $data['selected_status'] = $status;
        $this->load->view('products/viewProducts', $data);
    }

    public function addProduct() {
        $title = 'Tambah Produk';
        $data = [
            'title' => $title,
            'categories' => $this->ModelProducts->getAllCategories(),
            'statuses' => $this->ModelProducts->getAllStatuses(),
        ];

        $this->load->view('products/formProduct', $data);
    }

    public function addProductData() {
        //ambil id_produk terbesar dari database
        $id_produk = $this->ModelProducts->getMaxIdProduct();
        $id_produk = $id_produk + 1;

        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');
        $this->form_validation->set_rules('kategori_id', 'Kategori', 'required');
        $this->form_validation->set_rules('status_id', 'Status', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->addProduct();
        } else {
            $data = [
                'id_produk' => $id_produk,
                'nama_produk' => $this->input->post('nama_produk'),
                'harga' => $this->input->post('harga'),
                'kategori_id' => $this->input->post('kategori_id'),
                'status_id' => $this->input->post('status_id'),
            ];

            $this->ModelProducts->insertProduct($data);

            $this->session->set_flashdata('success', 'Produk berhasil ditambahkan.');
            redirect('ControllerProducts/viewAllProducts');
        }
    }

    public function updateProduct($id) {
        $title = 'Edit Produk';
        $product = $this->ModelProducts->getProductById($id);

        if ($product) {
            $data = [
                'title' => $title,
                'product' => $product,
                'categories' => $this->ModelProducts->getAllCategories(),
                'statuses' => $this->ModelProducts->getAllStatuses(),
            ];

            $this->load->view('products/formProduct', $data);
        } else {
            $this->session->set_flashdata('error', 'Produk tidak ditemukan.');
            redirect('ControllerProducts/viewAllProducts');
        }
    }

    public function updateProductData($id) {
        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'required');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');
        $this->form_validation->set_rules('kategori_id', 'Kategori', 'required');
        $this->form_validation->set_rules('status_id', 'Status', 'required');

        if ($this->form_validation->run() == FALSE) {
            $this->updateProduct($id);
        } else {
            $data = [
                'nama_produk' => $this->input->post('nama_produk'),
                'harga' => $this->input->post('harga'),
                'kategori_id' => $this->input->post('kategori_id'),
                'status_id' => $this->input->post('status_id'),
            ];

            $this->ModelProducts->updateProduct($id, $data);

            $this->session->set_flashdata('success', 'Produk berhasil diperbarui.');
            redirect('ControllerProducts/viewAllProducts');
        }
    }

    public function deleteProduct($id) {
        $product = $this->ModelProducts->getProductById($id);

        if ($product) {
            $this->ModelProducts->deleteProduct($id);
            $this->session->set_flashdata('success', 'Produk berhasil dihapus.');
        } else {
            $this->session->set_flashdata('error', 'Produk tidak ditemukan.');
        }

        redirect('ControllerProducts/viewAllProducts');
    }
}