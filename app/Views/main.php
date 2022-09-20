<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Laravel">
    <meta name="author" content="Laravel">

    <title><?= $this->renderSection('title') ?></title>
    <link href="<?= base_url('/css/bootstrap.min.css') ?>" rel="stylesheet"></link>
    <script src="<?= base_url('/js/jquery-3.6.1.min.js') ?>"></script>
    <script src="<?= base_url('/js/bootstrap.bundle.min.js') ?>"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>

<body class="navigation-toggle-one" id="page-top">
    <main style="padding-top: 120px;">
        <?= $this->renderSection('content') ?>
    </main>

    <?= $this->renderSection('js') ?>
</body>
</html>

