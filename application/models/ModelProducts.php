<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ModelProducts extends CI_Model {
    public function saveData($data) {
        foreach ($data as $item) {
            // Cek atau masukkan kategori
            $kategori_id = $this->insertGetId('kategori', [
                'nama_kategori' => $item['kategori'],
            ]);

            // Cek atau masukkan status
            $status_id = $this->insertGetId('status', [
                'nama_status' => $item['status'],
            ]);

            // Masukkan produk
            $this->db->insert('produk', [
                'id_produk'    => $item['id_produk'],
                'nama_produk'  => $item['nama_produk'],
                'harga'        => $item['harga'],
                'kategori_id'  => $kategori_id,
                'status_id'    => $status_id,
            ]);
        }

        $this->session->set_flashdata('success', 'Data berhasil disinkronisasi.');
        redirect('ControllerProducts/viewAllProducts');
    }

    public function insertGetId($table, $data) {
        // Cek apakah data sudah ada
        $this->db->where($data);
        $query = $this->db->get($table);

        if ($query->num_rows() > 0) {
            // Jika ada, kembalikan ID
            $row = $query->row();
            return $row->{"id_$table"};
        } else {
            // Jika tidak ada, masukkan data baru
            $this->db->insert($table, $data);
            return $this->db->insert_id();
        }
    }

    public function getAllProducts() {
        $this->db->select('produk.id_produk, produk.nama_produk, produk.harga, kategori.nama_kategori, status.nama_status');
        $this->db->from('produk');
        $this->db->join('kategori', 'produk.kategori_id = kategori.id_kategori');
        $this->db->join('status', 'produk.status_id = status.id_status');
        $this->db->order_by('produk.nama_produk', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function getProductsByStatus($status) {
        $this->db->select('produk.id_produk, produk.nama_produk, produk.harga, kategori.nama_kategori, status.nama_status');
        $this->db->from('produk');
        $this->db->join('kategori', 'produk.kategori_id = kategori.id_kategori');
        $this->db->join('status', 'produk.status_id = status.id_status');
        $this->db->where('status.nama_status', $status);
        $this->db->order_by('produk.nama_produk', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function getAllCategories() {
        $query = $this->db->get('kategori');
        return $query->result_array();
    }

    public function getAllStatuses() {
        $query = $this->db->get('status');
        return $query->result_array();
    }

    public function getMaxIdProduct() {
        $this->db->select_max('id_produk');
        $query = $this->db->get('produk');
        $row = $query->row();
        return $row->id_produk;
    }

    public function insertProduct($data) {
        $this->db->insert('produk', $data);
    }

    public function getProductById($id) {
        $this->db->select('produk.id_produk, produk.nama_produk, produk.harga, produk.kategori_id, produk.status_id, kategori.nama_kategori, status.nama_status');
        $this->db->from('produk');
        $this->db->join('kategori', 'produk.kategori_id = kategori.id_kategori');
        $this->db->join('status', 'produk.status_id = status.id_status');
        $this->db->where('produk.id_produk', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function updateProduct($id, $data) {
        $this->db->where('id_produk', $id);
        $this->db->update('produk', $data);
    }

    public function deleteAllProducts() {
        $this->db->empty_table('produk');
        $this->db->empty_table('kategori');
        $this->db->empty_table('status');
    }

    public function deleteProduct($id) {
        $this->db->where('id_produk', $id);
        $this->db->delete('produk');
    }
}