<?php $this->load->view('templates/header'); ?>

<div class="d-flex justify-content-between mb-3">
    <h1>Daftar Produk</h1>
    <div>
        <a href="<?= base_url('ControllerProducts/fetchData'); ?>" class="btn btn-primary" onclick="return confirm('Apakah Anda yakin ingin menyinkronisasi data produk?');">Sinkronisasi Data</a>
        <a href="<?= base_url('ControllerProducts/addProduct'); ?>" class="btn btn-success">Tambah Produk</a>
    </div>
</div>

<?php if ($this->session->flashdata('success')): ?>
    <div class="alert alert-success"><?= $this->session->flashdata('success'); ?></div>
<?php elseif ($this->session->flashdata('error')): ?>
    <div class="alert alert-danger"><?= $this->session->flashdata('error'); ?></div>
<?php endif; ?>

<form method="POST" action="<?= site_url('controllerProducts'); ?>">
    <label for="status">Filter Produk:</label>
    <select name="status" id="status" onchange="this.form.submit()">
        <option value="semua" <?= ($selected_status == 'semua') ? 'selected' : ''; ?>>Semua Produk</option>
        <option value="bisa dijual" <?= ($selected_status == 'bisa dijual') ? 'selected' : ''; ?>>Bisa Dijual</option>
    </select>
</form>


<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Produk</th>
            <th>Harga</th>
            <th>Kategori</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $index => $product): ?>
                <tr>
                    <td><?= $index + 1; ?></td>
                    <td><?= $product->nama_produk; ?></td>
                    <td><?= 'Rp' . number_format($product->harga, 0, ',', '.'); ?></td>
                    <td><?= $product->nama_kategori; ?></td>
                    <td><?= $product->nama_status; ?></td>
                    <td>
                        <a href="<?= base_url('ControllerProducts/updateProduct/' . $product->id_produk); ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="<?= base_url('ControllerProducts/deleteProduct/' . $product->id_produk); ?>" class="btn btn-danger btn-sm" onclick="return confirmDelete();">Hapus</a>
                        <script>
                            function confirmDelete() {
                                return confirm('Apakah Anda yakin ingin menghapus produk?');
                            }
                        </script>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="6" class="text-center">Tidak ada data produk.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php $this->load->view('templates/footer'); ?>
