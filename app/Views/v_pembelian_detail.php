<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="pagetitle">
    <h1>Detail Pembelian</h1>

    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('/') ?>">
                    Home
                </a>
            </li>

            <li class="breadcrumb-item">
                <a href="<?= base_url('pembelian') ?>">
                    Pembelian
                </a>
            </li>

            <li class="breadcrumb-item active">
                Detail
            </li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">

                    <h5 class="card-title">
                        Informasi Pembelian
                    </h5>

                    <div class="row mb-2">
                        <div class="col-md-3">
                            ID Pembelian
                        </div>

                        <div class="col-md-9">
                            :
                            <?= esc($transaction['id']) ?>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3">
                            Pembeli
                        </div>

                        <div class="col-md-9">
                            :
                            <?= esc(
                                $transaction['username']
                            ) ?>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3">
                            Alamat
                        </div>

                        <div class="col-md-9">
                            :
                            <?= esc(
                                $transaction['alamat']
                            ) ?>
                        </div>
                    </div>

                    <div class="row mb-2">
                        <div class="col-md-3">
                            Ongkir
                        </div>

                        <div class="col-md-9">
                            :
                            <?= number_to_currency(
                                $transaction['ongkir'],
                                'IDR'
                            ) ?>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            Total Bayar
                        </div>

                        <div class="col-md-9">
                            :
                            <strong>
                                <?= number_to_currency(
                                    $transaction['total_harga'],
                                    'IDR'
                                ) ?>
                            </strong>
                        </div>
                    </div>

                    <hr>

                    <h5 class="card-title">
                        Produk yang Dibeli
                    </h5>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Produk</th>
                                    <th>Jumlah</th>
                                    <th>Diskon</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (!empty($products)) : ?>

                                    <?php $nomor = 1; ?>

                                    <?php foreach (
                                        $products as $product
                                    ) : ?>

                                        <tr>
                                            <td>
                                                <?= $nomor++ ?>
                                            </td>

                                            <td>
                                                <?= esc(
                                                    $product['nama']
                                                    ?? $product['name']
                                                    ?? $product['product_name']
                                                    ?? '-'
                                                ) ?>
                                            </td>

                                            <td>
                                                <?= (int) (
                                                    $product['jumlah']
                                                    ?? 0
                                                ) ?>
                                            </td>

                                            <td>
                                                <?= number_to_currency(
                                                    $product['diskon']
                                                    ?? 0,
                                                    'IDR'
                                                ) ?>
                                            </td>

                                            <td>
                                                <?= number_to_currency(
                                                    $product['subtotal_harga']
                                                    ?? 0,
                                                    'IDR'
                                                ) ?>
                                            </td>
                                        </tr>

                                    <?php endforeach; ?>

                                <?php else : ?>

                                    <tr>
                                        <td
                                            colspan="5"
                                            class="text-center">

                                            Detail produk tidak ditemukan.
                                        </td>
                                    </tr>

                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <a
                        href="<?= base_url('pembelian') ?>"
                        class="btn btn-secondary">

                        Kembali
                    </a>

                </div>
            </div>

        </div>
    </div>
</section>

<?= $this->endSection() ?>