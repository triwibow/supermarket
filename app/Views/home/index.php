<?= $this->extend('main') ?>
<?= $this->section('title') ?>
    <?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        Data Produk
                    </div>
                    <div class="card-body">
                        <div class="mb-4 d-flex align-items-center justify-content-between">
                            <a class="btn btn-success btn-sm" href="<?php echo base_url('home/add'); ?>">Tambah</a>
                            <a class="btn btn-warning btn-sm text-white" href="<?php echo base_url('cart'); ?>">Cart</a>
                        </div>

                        <div class="data">
                            <table class="table table-light table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Gambar</th>
                                        <th>Kode</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php foreach($data as $key => $item): ?>
                                    <tr>
                                        <td><?= $no++ ?></td>
                                        <td><?= $item->name; ?></td>
                                        <td><?= rupiah($item->price); ?></td>
                                        <td><?= $item->stock; ?></td>
                                        <td>
                                            <img src="<?= base_url($item->image) ?>" alt="img" style="width:50px; height:50px;">
                                        </td>
                                        <td><?= $item->code; ?></td>
                                        <td>
                                            <button onclick="saveToCart('<?= $item->id ?>', 'plus')" class="btn btn-warning btn-sm text-white">
                                                Beli
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                                </tbody>
                            </table>
                            <?= $pager->links('default', 'bootstrap_pagination') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>

<script>
    const saveToCart = async (id, type) => {
        try {
            const requestData = new FormData();
            requestData.append('id', id);
            requestData.append('type', type)

            const response = await fetch('<?= url_to("cart.save") ?>',{
                method: "POST",
                body: requestData
            });

            const data = await response.json();

            if(data.code != 200)
            {
                swal({
                    title: "Perhatian",
                    text: data.message,
                    icon: "warning",
                    button: {
                        text:"Tutup",
                        className:"btn btn-success"
                    },
                });
                return;
            }

            window.location.replace(data.url);

        } catch(e)
        {
            console.log(e);
        }
    }
</script>

<?= $this->endSection() ?>