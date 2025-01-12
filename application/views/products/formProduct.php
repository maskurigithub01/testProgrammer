<?php $this->load->view('templates/header'); ?>

<h1><?= isset($product) ? 'Edit Produk' : 'Tambah Produk'; ?></h1>

<form action="<?= isset($product) ? base_url('ControllerProducts/updateProductData/' . $product['id_produk']) : base_url('ControllerProducts/addProductData'); ?>" method="post">
    <div class="mb-3">
        <label for="nama_produk" class="form-label">Nama Produk</label>
        <input type="text" class="form-control" id="nama_produk" name="nama_produk" 
            value="<?= isset($product) ? $product['nama_produk'] : set_value('nama_produk'); ?>" required>
        <?= form_error('nama_produk', '<small class="text-danger">', '</small>'); ?>
    </div>
    <div class="mb-3">
        <label for="harga" class="form-label">Harga</label>
        <input type="number" class="form-control" id="harga" name="harga" 
            value="<?= isset($product) ? $product['harga'] : set_value('harga'); ?>" required>
        <?= form_error('harga', '<small class="text-danger">', '</small>'); ?>
    </div>
    <div class="mb-3">
        <label for="kategori_id" class="form-label">Kategori</label>
        <select class="form-select" id="kategori_id" name="kategori_id" required>
            <option value="">Pilih Kategori</option>
            <?php foreach ($categories as $category): ?>
                <option value="<?= $category['id_kategori']; ?>" <?= isset($product) && $product['kategori_id'] == $category['id_kategori'] ? 'selected' : ''; ?>>
                    <?= $category['nama_kategori']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="mb-3">
        <label for="status_id" class="form-label">Status</label>
        <select class="form-select" id="status_id" name="status_id" required>
            <option value="">Pilih Status</option>
            <?php foreach ($statuses as $status): ?>
                <option value="<?= $status['id_status']; ?>" <?= isset($product) && $product['status_id'] == $status['id_status'] ? 'selected' : ''; ?>>
                    <?= $status['nama_status']; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary"><?= isset($product) ? 'Simpan Perubahan' : 'Tambah Produk'; ?></button>
    <a href="<?= base_url('ControllerProducts/viewAllProducts'); ?>" class="btn btn-secondary">Kembali</a>
</form>

<?php $this->load->view('templates/footer'); ?>
