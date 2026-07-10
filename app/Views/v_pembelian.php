<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="pagetitle">
    <h1>Pembelian</h1>

    <nav>
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="<?= base_url('/') ?>">
                    Home
                </a>
            </li>

            <li class="breadcrumb-item active">
                Pembelian
            </li>
        </ol>
    </nav>
</div>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <?php if (
                session()->getFlashdata('success')
            ) : ?>
                <div
                    class="alert alert-success alert-dismissible fade show"
                    role="alert">

                    <?= esc(
                        session()->getFlashdata('success')
                    ) ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>
                </div>
            <?php endif; ?>

            <?php if (
                session()->getFlashdata('error')
            ) : ?>
                <div
                    class="alert alert-danger alert-dismissible fade show"
                    role="alert">

                    <?= esc(
                        session()->getFlashdata('error')
                    ) ?>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="alert">
                    </button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">

                    <h5 class="card-title">
                        Pembelian
                    </h5>

                    <p>History Transaksi Pembelian</p>

                    <hr>

                    <div class="table-responsive">
                        <table class="table datatable align-middle">

                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>ID Pembelian</th>
                                    <th>Pembeli</th>
                                    <th>Waktu Pembelian</th>
                                    <th>Total Bayar</th>
                                    <th>Alamat</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php if (
                                    !empty($transactions)
                                ) : ?>

                                    <?php $nomor = 1; ?>

                                    <?php foreach (
                                        $transactions as $transaction
                                    ) : ?>

                                        <?php
                                        $status = (int) (
                                            $transaction['status'] ?? 0
                                        );
                                        ?>

                                        <tr>
                                            <td>
                                                <?= $nomor++ ?>
                                            </td>

                                            <td>
                                                <?= esc(
                                                    $transaction['id']
                                                ) ?>
                                            </td>

                                            <td>
                                                <?= esc(
                                                    $transaction['username']
                                                ) ?>
                                            </td>

                                            <td>
                                                <?= esc(
                                                    $transaction['created_at']
                                                    ?? '-'
                                                ) ?>
                                            </td>

                                            <td>
                                                <?= number_to_currency(
                                                    $transaction['total_harga'],
                                                    'IDR'
                                                ) ?>
                                            </td>

                                            <td>
                                                <?= esc(
                                                    $transaction['alamat']
                                                ) ?>
                                            </td>

                                            <td>
                                                <?php if (
                                                    $status === 1
                                                ) : ?>

                                                    <span
                                                        class="badge bg-primary">

                                                        Sudah Selesai
                                                    </span>

                                                <?php else : ?>

                                                    <span
                                                        class="badge bg-warning text-dark">

                                                        Belum Selesai
                                                    </span>

                                                <?php endif; ?>
                                            </td>

                                            <td class="text-nowrap">

                                                <a
                                                    href="<?= base_url(
                                                        'pembelian/detail/'
                                                        . $transaction['id']
                                                    ) ?>"
                                                    class="btn btn-success btn-sm">

                                                    Detail
                                                </a>

                                                <button
                                                    type="button"
                                                    class="btn btn-info btn-sm"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#modalStatus<?= $transaction['id'] ?>">

                                                    Ubah Status
                                                </button>
                                            </td>
                                        </tr>

                                    <?php endforeach; ?>

                                <?php else : ?>

                                    <tr>
                                        <td
                                            colspan="8"
                                            class="text-center">

                                            Belum ada data pembelian.
                                        </td>
                                    </tr>

                                <?php endif; ?>
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>
</section>

<!-- Modal ubah status -->
<?php if (!empty($transactions)) : ?>

    <?php foreach (
        $transactions as $transaction
    ) : ?>

        <div
            class="modal fade"
            id="modalStatus<?= $transaction['id'] ?>"
            tabindex="-1"
            aria-hidden="true">

            <div class="modal-dialog">
                <div class="modal-content">

                    <form
                        action="<?= base_url(
                            'pembelian/status/'
                            . $transaction['id']
                        ) ?>"
                        method="post">

                        <?= csrf_field() ?>

                        <div class="modal-header">
                            <h5 class="modal-title">
                                Ubah Status Pesanan
                            </h5>

                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal">
                            </button>
                        </div>

                        <div class="modal-body">

                            <p>
                                ID Pembelian:
                                <strong>
                                    <?= esc(
                                        $transaction['id']
                                    ) ?>
                                </strong>
                            </p>

                            <div class="mb-3">
                                <label
                                    for="status<?= $transaction['id'] ?>"
                                    class="form-label">

                                    Status pesanan
                                </label>

                                <select
                                    name="status"
                                    id="status<?= $transaction['id'] ?>"
                                    class="form-select"
                                    required>

                                    <option
                                        value="0"
                                        <?= (int) $transaction['status'] === 0
                                            ? 'selected'
                                            : '' ?>>

                                        Belum Selesai
                                    </option>

                                    <option
                                        value="1"
                                        <?= (int) $transaction['status'] === 1
                                            ? 'selected'
                                            : '' ?>>

                                        Sudah Selesai
                                    </option>
                                </select>
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button
                                type="button"
                                class="btn btn-secondary"
                                data-bs-dismiss="modal">

                                Batal
                            </button>

                            <button
                                type="submit"
                                class="btn btn-primary">

                                Simpan
                            </button>
                        </div>

                    </form>

                </div>
            </div>
        </div>

    <?php endforeach; ?>

<?php endif; ?>

<?= $this->endSection() ?>