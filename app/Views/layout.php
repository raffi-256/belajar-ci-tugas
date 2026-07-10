<?php
$hlm = "Home";
if (uri_string() != "") {
    $hlm = ucwords(uri_string());
}

if ($hlm == "Discount") {
    $hlm = "Diskon";
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>Toko | <?= $hlm ?></title>

    <link href="<?= base_url('NiceAdmin/assets/img/favicon.png') ?>" rel="icon">
    <link href="<?= base_url('NiceAdmin/assets/img/apple-touch-icon.png') ?>" rel="apple-touch-icon">

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600|Nunito:300,400,600,700|Poppins:300,400,500,600,700" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="<?= base_url('NiceAdmin/assets/vendor/bootstrap/css/bootstrap.min.css') ?>" rel="stylesheet">

    <!-- Icons -->
    <link href="<?= base_url('NiceAdmin/assets/vendor/bootstrap-icons/bootstrap-icons.css') ?>" rel="stylesheet">

    <!-- Vendor CSS -->
    <link href="<?= base_url('NiceAdmin/assets/vendor/boxicons/css/boxicons.min.css') ?>" rel="stylesheet">
    <link href="<?= base_url('NiceAdmin/assets/vendor/quill/quill.snow.css') ?>" rel="stylesheet">
    <link href="<?= base_url('NiceAdmin/assets/vendor/quill/quill.bubble.css') ?>" rel="stylesheet">
    <link href="<?= base_url('NiceAdmin/assets/vendor/remixicon/remixicon.css') ?>" rel="stylesheet">
    <link href="<?= base_url('NiceAdmin/assets/vendor/simple-datatables/style.css') ?>" rel="stylesheet">

    <!-- DataTables -->
    <link rel="stylesheet"
          href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css">

    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/css/select2.min.css" rel="stylesheet"/>

    <!-- Main CSS -->
    <link href="<?= base_url('NiceAdmin/assets/css/style.css') ?>" rel="stylesheet">

</head>

<body>

<?= $this->include('components/header') ?>

<?= $this->include('components/sidebar') ?>

<main id="main" class="main">

    <div class="pagetitle">

        <h1><?= $hlm ?></h1>

        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="<?= base_url('/') ?>">Home</a>
                </li>

                <?php if ($hlm != "Home") : ?>

                    <li class="breadcrumb-item active">

                        <?= $hlm ?>

                    </li>

                <?php endif; ?>

            </ol>
        </nav>

    </div>

    <section class="section">

        <div class="row">

            <div class="col-lg-12">

                <div class="card">

                    <div class="card-body">

                        <h5 class="card-title">

                            <?= $hlm ?>

                        </h5>

                        <?= $this->renderSection('content') ?>

                    </div>

                </div>

            </div>

        </div>

    </section>

</main>

<?= $this->include('components/footer') ?>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
</a>

<!-- JQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Bootstrap -->
<script src="<?= base_url('NiceAdmin/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

<!-- Vendor -->
<script src="<?= base_url('NiceAdmin/assets/vendor/apexcharts/apexcharts.min.js') ?>"></script>
<script src="<?= base_url('NiceAdmin/assets/vendor/chart.js/chart.umd.js') ?>"></script>
<script src="<?= base_url('NiceAdmin/assets/vendor/echarts/echarts.min.js') ?>"></script>
<script src="<?= base_url('NiceAdmin/assets/vendor/quill/quill.min.js') ?>"></script>
<script src="<?= base_url('NiceAdmin/assets/vendor/simple-datatables/simple-datatables.js') ?>"></script>
<script src="<?= base_url('NiceAdmin/assets/vendor/tinymce/tinymce.min.js') ?>"></script>
<script src="<?= base_url('NiceAdmin/assets/vendor/php-email-form/validate.js') ?>"></script>

<!-- Select2 -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0/dist/js/select2.min.js"></script>

<!-- DataTables -->
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>

<?= $this->renderSection('script') ?>

<script src="<?= base_url('NiceAdmin/assets/js/main.js') ?>"></script>

</body>
</html>