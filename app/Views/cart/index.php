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
                        Cart
                    </div>
                    <div class="card-body">
                        <div class="data">
                            <table class="table table-light table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nama Barang</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                        <th>Opsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php if(count($data) > 0): ?>
                                    <?php foreach($data as $key => $item): ?>
                                        <tr>
                                            <td><?= $item->name; ?></td>
                                            <td><?= rupiah($item->price); ?></td>
                                            <td><?= $item->quantity; ?></td>
                                            <td><?= rupiah($item->sub_total); ?></td>
                                            <td>
                                                <button onclick="saveToCart('<?= $item->product_id ?>', 'minus')" class="btn btn-danger btn-sm">-</button>
                                                <button onclick="saveToCart('<?= $item->product_id ?>', 'plus')" class="btn btn-success btn-sm">+</button>
                                                <button onclick="discardItem('<?= $item->product_id ?>')" class="btn btn-light">discard</button>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                        <tr>
                                            <td colspan="3">Total</td>
                                            <td colspan="3"><?= rupiah($total) ?></td>
                                        </tr>
                                <?php else: ?>
                                    <tr>
                                        <td class="text-center" colspan="5">Tidak ada item</td>
                                    </tr>
                                <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex align-items-center justify-content-between">
                            <a class="btn btn-warning text-white" href="<?= url_to('home.index') ?>">Kembali</a>
                            <?php if(count($data) > 0): ?>
                                <button onclick="checkout()" class="btn btn-success text-white">Checkout</button>
                            <?php endif; ?>
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


const discardItem = async (id) => {
    try {
        const requestData = new FormData();
        requestData.append('id', id);

        const response = await fetch('<?= url_to("cart.discard") ?>',{
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

        swal({
            title: "Perhatian",
            text: data.message,
            icon: "warning",
            button: {
                text:"Tutup",
                className:"btn btn-success"
            },
        }).then(val => {
            window.location.replace(data.url);
        });
        
    } catch(e)
    {
        console.log(e);
    }
}

const checkout = async () => {
    try {
        const requestData = new FormData();
        const response = await fetch('<?= url_to("cart.checkout") ?>',{
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

        swal({
            title: "Perhatian",
            text: data.message,
            icon: "warning",
            button: {
                text:"Tutup",
                className:"btn btn-success"
            },
        }).then(val => {
            window.location.replace(data.url);
        });
        
    } catch(e)
    {
        console.log(e);
    }
}
</script>

<?= $this->endSection() ?>