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
                    Tambah Produk
                </div>
                <div class="card-body">
                    <form id="form-content">
                        <div class="form-group">
                            <label>Nama Barang</label>
                            <input type="text" class="form-control" name="name">
                        </div>
                        <div class="form-group">
                            <label>Harga</label>
                            <input type="number" class="form-control number" name="price">
                        </div>
                        <div class="form-group">
                            <label>Stok</label>
                            <input type="number" class="form-control number" name="stock">
                        </div>
                        <div class="form-group">
                            <label>Gambar</label>
                            <input type="file" class="form-control" name="image">
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="id" value="<?= $data->id ?>">
                            <a class="btn btn-warning" href="<?= url_to('home.index') ?>">Kembali</a>
                            <button onclick="handleSubmit()" type="button" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('js') ?>
<script>

    $('.number').on("change", function(){
        let val = $(this).val();

        if(val < 1){
            $(this).val(1);
        }
    })

    const handleSubmit = async () => {
        try {
            const form = document.getElementById("form-content");
            const requestData = new FormData(form);

            const response = await fetch('<?= url_to("home.save") ?>',{
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
                icon: "success",
                button: {
                    text:"Tutup",
                    className:"btn btn-success"
                },
            });
            
            if(requestData.get('id') == ""){
                form.reset();
            }
            
        } catch(e)
        {
            console.log(e);
        }
    }

</script>
<?= $this->endSection() ?>