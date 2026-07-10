<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="pagetitle">
    <h1>Keranjang</h1>

    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('/') ?>">Home</a>
            </li>

            <li class="breadcrumb-item active">
                Keranjang
            </li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <?php if (session()->getFlashdata('success')) : ?>
                <div
                    class="alert alert-success alert-dismissible fade show"
                    role="alert">

                    <?= session()->getFlashdata('success') ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                    </button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')) : ?>
                <div
                    class="alert alert-danger alert-dismissible fade show"
                    role="alert">

                    <?= esc(session()->getFlashdata('error')) ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert"
                        aria-label="Close">
                    </button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">

                    <h5 class="card-title">Keranjang</h5>

                    <?php if (!empty($items)) : ?>

                        <form
                            action="<?= base_url('keranjang/edit') ?>"
                            method="post">

                            <?= csrf_field() ?>

                            <div class="table-responsive">
                                <table class="table datatable align-middle">

                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th>Foto</th>
                                            <th>Harga</th>
                                            <th>Jumlah</th>
                                            <th>Subtotal</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php $i = 1; ?>

                                        <?php foreach ($items as $item) : ?>

                                            <?php
                                            // Harga asli dari session keranjang.
                                            $hargaAsli = (int) (
                                                $item['harga_asli']
                                                ?? $item['price']
                                            );

                                            // Harga setelah dikurangi diskon.
                                            $hargaDiskon = (int) (
                                                $item['harga_diskon']
                                                ?? $hargaAsli
                                            );

                                            // Subtotal setelah diskon.
                                            $subtotalDiskon = (int) (
                                                $item['subtotal_diskon']
                                                ?? (
                                                    $hargaDiskon
                                                    * (int) $item['qty']
                                                )
                                            );

                                            $foto = $item['options']['foto']
                                                ?? '';
                                            ?>

                                            <tr>
                                                <td>
                                                    <?= esc($item['name']) ?>
                                                </td>

                                                <td>
                                                    <?php if ($foto !== '') : ?>
                                                        <img
                                                            src="<?= esc(
                                                                base_url(
                                                                    'img/' . $foto
                                                                )
                                                            ) ?>"
                                                            alt="<?= esc(
                                                                $item['name']
                                                            ) ?>"
                                                            width="100">
                                                    <?php else : ?>
                                                        <span>
                                                            Tidak ada foto
                                                        </span>
                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <?php if (
                                                        isset($diskonHariIni)
                                                        && $diskonHariIni > 0
                                                        && $hargaDiskon < $hargaAsli
                                                    ) : ?>

                                                        <div>
                                                            <del class="text-danger">
                                                                <?= number_to_currency(
                                                                    $hargaAsli,
                                                                    'IDR'
                                                                ) ?>
                                                            </del>
                                                        </div>

                                                        <div>
                                                            <?= number_to_currency(
                                                                $hargaDiskon,
                                                                'IDR'
                                                            ) ?>
                                                        </div>

                                                    <?php else : ?>

                                                        <?= number_to_currency(
                                                            $hargaAsli,
                                                            'IDR'
                                                        ) ?>

                                                    <?php endif; ?>
                                                </td>

                                                <td>
                                                    <input
                                                        type="number"
                                                        name="qty<?= $i++ ?>"
                                                        class="form-control"
                                                        value="<?= (int) $item['qty'] ?>"
                                                        min="1"
                                                        required>
                                                </td>

                                                <td>
                                                    <?= number_to_currency(
                                                        $subtotalDiskon,
                                                        'IDR'
                                                    ) ?>
                                                </td>

                                                <td>
                                                    <a
                                                        href="<?= base_url(
                                                            'keranjang/delete/'
                                                            . $item['rowid']
                                                        ) ?>"
                                                        class="btn btn-danger btn-sm"
                                                        onclick="return confirm(
                                                            'Yakin ingin menghapus produk ini?'
                                                        )">

                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>

                                        <?php endforeach; ?>
                                    </tbody>

                                </table>
                            </div>

                            <hr>

                            <div class="alert alert-info">
                                Total =
                                <strong>
                                    <?= number_to_currency(
                                        $total,
                                        'IDR'
                                    ) ?>
                                </strong>
                            </div>

                            <button
                                type="submit"
                                class="btn btn-primary">

                                Perbarui Keranjang
                            </button>

                            <a
                                href="<?= base_url(
                                    'keranjang/clear'
                                ) ?>"
                                class="btn btn-warning"
                                onclick="return confirm(
                                    'Yakin ingin mengosongkan keranjang?'
                                )">

                                Kosongkan Keranjang
                            </a>

                            <a
                                href="<?= base_url('checkout') ?>"
                                class="btn btn-success">

                                Selesai Belanja
                            </a>

                        </form>

                    <?php else : ?>

                        <div class="alert alert-info">
                            Keranjang belanja masih kosong.
                        </div>

                        <a
                            href="<?= base_url('/') ?>"
                            class="btn btn-primary">

                            Kembali Belanja
                        </a>

                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>
</section>

<?= $this->endSection() ?>